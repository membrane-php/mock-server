<?php

declare(strict_types=1);

namespace Membrane\MockServer\Model;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Attribute\Subtype;
use Atto\Orm\Attribute\Id;

/**
 * @phpstan-import-type OperationConfig from \Membrane\MockServer\Module
 * @phpstan-import-type ResponseBodyConfig from \Membrane\MockServer\Module
 */
#[\Atto\Orm\Attribute\Entity]
#[\Atto\Hydrator\Attribute\Hydratable]
final readonly class Operation implements \JsonSerializable
{
    /**
     * @param array<string, string|string[]> $headers
     * @param ResponseBodyConfig $body
     */
    public function __construct(
        #[Id]
        public string $operationId,
        private int $responseCode,
        #[Subtype('array')]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private array $headers,
        #[Subtype('array')]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private array $body,
    ) {}

    /**
     * @return OperationConfig
     */
    public function jsonSerialize(): array
    {
        return [
            'operationId' => $this->operationId,
            'default' => [
                'response' => [
                    'code' => $this->responseCode,
                    'headers' => $this->headers,
                    'body' => $this->body,
                ],
            ],
        ];
    }
}
