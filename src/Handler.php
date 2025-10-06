<?php

declare(strict_types=1);

namespace Membrane\MockServer;

use Psr\Http\Message\ResponseInterface;

/**
 * @phpstan-import-type MatcherConfig from ConfigLocator
 * @phpstan-import-type ResponseConfig from ConfigLocator
 */
final readonly class Handler
{
    /**
     * @param array{
     *     matchers?: array{response: ResponseConfig, matcher: MatcherConfig},
     *     default?: array{response: ResponseConfig},
     * } $config
     */
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
                ->locate($matcherConfig)
                ->create($matcherConfig);


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
