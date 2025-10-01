<?php

declare(strict_types=1);

namespace Membrane\MockServer;

use Psr\Http\Message\ResponseInterface;

final readonly class Handler
{
    /**
     * @param array{
     *     matchers?: array{response: ResponseInterface, matcher: Matcher},
     *     default?: array{response: ResponseInterface}
     * } $config
     */
    public function __construct(
        private ConfigLocator $configLocator,
    ) {}

    public function __invoke(DTO $dto): ?ResponseInterface
    {
        $operationId = $dto->request['request']['operationId']
            ?? throw new \RuntimeException('No operation id'); // in practice this cannot happen.

        $operationConfig = $this->configLocator->getOperationConfig($operationId);

        foreach ($operationConfig['matchers'] ?? [] as ['matcher' => $matcher, 'response' => $response]) {
            if ($matcher->matches($dto)) {
                return $response;
            }
        }

        return $operationConfig['default']['response'] ?? null;
    }
}
