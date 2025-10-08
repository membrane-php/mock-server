<?php

declare(strict_types=1);

namespace Membrane\MockServer\Model;

use Atto\Hydrator\Attribute\HydrationStrategy;
use Atto\Hydrator\Attribute\HydrationStrategyType;
use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Attribute\Subtype;
use Atto\Orm\Attribute\Id;

#[\Atto\Orm\Attribute\Entity]
#[\Atto\Hydrator\Attribute\Hydratable]
final readonly class Matcher implements \JsonSerializable
{
    /**
     * @param array<mixed> $matcherArgs
     * @param array<string, string|string[]> $headers
     * @param mixed[] $body
     */
    public function __construct(
        #[Id]
        public string $id,
        public string $operationId,
        private string $matcherAlias,
        #[Subtype('string')]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private array $matcherArgs,
        private int $responseCode,
        #[Subtype('string')]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private array $headers,
        #[Subtype('string')]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private array $body,
    ) {}

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'operationId' => $this->operationId,
            'matcher' => [
                'type' => $this->matcherAlias,
                'args' => $this->matcherArgs,
            ],
            'response' => [
                'code' => $this->responseCode,
                'headers' => $this->headers,
                'body' => $this->body,
            ],
        ];
    }
}
