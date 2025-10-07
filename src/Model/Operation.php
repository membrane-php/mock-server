<?php

declare(strict_types=1);

namespace Membrane\MockServer\Model;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Attribute\Subtype;
use Atto\Orm\Attribute\Id;

#[\Atto\Orm\Attribute\Entity]
#[\Atto\Hydrator\Attribute\Hydratable]
final readonly class Operation implements \JsonSerializable
{
    public function __construct(
        #[Id]
        private string $operationId,
        private int $responseCode,
        #[Subtype('array')]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private array $headers,
        #[Subtype('array')]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private array $body,
    ) {}

    public function jsonSerialize(): mixed
    {
        return [
            'operationId' => $this->operationId,
            'response' => [
                'code' => $this->responseCode,
                'headers' => $this->headers,
                'body' => $this->body,
            ],
        ];
    }
}
