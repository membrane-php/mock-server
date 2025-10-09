<?php

declare(strict_types=1);

namespace Membrane\MockServer\Database\Model;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Attribute\Subtype;
use Atto\Orm\Attribute\Id;

/**
 * @phpstan-import-type OperationConfig from \Membrane\MockServer\Mocking\Module
 */
#[\Atto\Orm\Attribute\Entity]
#[\Atto\Hydrator\Attribute\Hydratable]
final readonly class Operation implements \JsonSerializable
{
    private string $body;

    /**
     * @param array<string, string|string[]> $headers
     * @param mixed[] $body
     */
    public function __construct(
        #[Id]
        public string $operationId,
        private int $responseCode,
        #[Subtype('array')]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private array $headers = [],
        array|string $body = '',
    ) {
        $this->body = is_string($body)
            ? $body
            : (json_encode($body)
                ?: throw new \RuntimeException(json_last_error_msg()));
    }

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
