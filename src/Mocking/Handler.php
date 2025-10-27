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


        foreach ($operationConfig['matchers'] ?? [] as ['matcher' => $matcherConfig, 'response' => $responseConfig]) {
            $matcher = $this->factoryLocator
                ->locate($matcherConfig['type'])
                ->create($matcherConfig['args'] ?? []);


            if ($matcher->matches($dto)) {
                return $this->responseFactory->create($responseConfig);
            }
        }

        $defaultResponseConfig = $operationConfig['default']['response'] ?? null;
        if ($defaultResponseConfig === null) {
            return null;
        }

        return $this->responseFactory->create($defaultResponseConfig);
    }
}
