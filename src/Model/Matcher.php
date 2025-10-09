<?php

declare(strict_types=1);

namespace Membrane\MockServer\Model;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Attribute\Subtype;
use Atto\Orm\Attribute\Id;

/**
 * @phpstan-import-type MatcherFactoryConfig from \Membrane\MockServer\Module
 * @phpstan-import-type ResponseConfig from \Membrane\MockServer\Module
 * @phpstan-import-type ResponseBodyConfig from \Membrane\MockServer\Module
 */
#[\Atto\Orm\Attribute\Entity]
#[\Atto\Hydrator\Attribute\Hydratable]
final readonly class Matcher implements \JsonSerializable
{
    /**
     * @param array<mixed> $matcherArgs
     * @param array<string, string|string[]> $headers
     * @param ResponseBodyConfig $body
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

    /** @return array{matcher: MatcherFactoryConfig, response: ResponseConfig} */
    public function jsonSerialize(): array
    {
        return [
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
