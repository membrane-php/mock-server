<?php

declare(strict_types=1);

namespace Membrane\MockServer\Database\Model;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Attribute\Subtype;
use Atto\Orm\Attribute\Id;

/**
 * @phpstan-import-type ResponseConfig from \Membrane\MockServer\Mocking\Module
 */
#[\Atto\Orm\Attribute\Entity]
#[\Atto\Hydrator\Attribute\Hydratable]
final readonly class Operation implements \JsonSerializable
{
    private string $defaultResponseBody;

    /**
     * @param array<string, string|string[]> $defaultResponseHeaders
     * @param mixed[] $defaultResponseBody
     */
    public function __construct(
        #[Id]
        public string $operationId,
        private int $defaultResponseCode,
        #[Subtype('array')]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private array $defaultResponseHeaders = [],
        array|string $defaultResponseBody = '',
    ) {
        $this->defaultResponseBody = is_string($defaultResponseBody)
            ? $defaultResponseBody
            : (json_encode($defaultResponseBody)
                ?: throw new \RuntimeException(json_last_error_msg()));
    }

    /**
     * @return array{
     *     operationId: string,
     *     default: array{response: ResponseConfig},
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'operationId' => $this->operationId,
            'default' => [
                'response' => [
                    'code' => $this->defaultResponseCode,
                    'headers' => $this->defaultResponseHeaders,
                    'body' => $this->defaultResponseBody,
                ],
            ],
        ];
    }
}
