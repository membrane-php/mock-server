<?php

declare(strict_types=1);

namespace Membrane\MockServer\Mocking;

use Membrane\MockServer\Matcher\FactoryLocator;
use Psr\Http\Message\ResponseInterface;

final readonly class Handler
{
    public function __construct(
        private ConfigLocator $configLocator,
        private FactoryLocator $factoryLocator,
        private ResponseFactory $responseFactory,
    ) {}

    public function __invoke(DTO $dto): ?ResponseInterface
    {
        $operationId = $dto->request['request']['operationId']
            ?? throw new \RuntimeException('No operation id'); // in practice this cannot happen.

        $operationConfig = $this->configLocator->getOperationConfig($operationId);

        foreach ($operationConfig['matchers'] ?? []
            as ['matcher' => $matcherConfig, 'response' => $responseConfig]
        ) {
            $matcher = $this->factoryLocator
                ->locate($matcherConfig['type'])
                ->create($matcherConfig['args'] ?? []);

            if ($matcher->matches($dto)) {
                return $this->responseFactory->create($responseConfig);
            }
        }

        $defaultResponseConfig = $operationConfig['default']['response']
            ?? [
                'code' => 522,
                'headers' => [
                    'Content-type' => 'application/problem+json',
                ],
                'body' => [
                    'title' => 'Response Not Defined',
                    'detail' => <<<DETAIL
                         Request is valid against your OpenAPI spec,
                         but no response has been defined for this operation.
                         DETAIL,
                ],
            ];

        return $this->responseFactory->create($defaultResponseConfig);
    }
}
