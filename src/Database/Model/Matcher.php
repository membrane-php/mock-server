<?php

declare(strict_types=1);

namespace Membrane\MockServer\Database\Model;

use Atto\Hydrator\Attribute\SerializationStrategy;
use Atto\Hydrator\Attribute\SerializationStrategyType;
use Atto\Hydrator\Attribute\Subtype;
use Atto\Orm\Attribute\Id;

/**
 * @phpstan-import-type MatcherFactoryConfig from \Membrane\MockServer\Mocking\Module
 * @phpstan-import-type ResponseConfig from \Membrane\MockServer\Mocking\Module
 */
#[\Atto\Orm\Attribute\Entity]
#[\Atto\Hydrator\Attribute\Hydratable]
final readonly class Matcher implements \JsonSerializable
{
    private string $body;

    /**
     * @param array<mixed> $matcherArgs
     * @param array<string, string|list<string>> $headers
     * @param array<mixed> $body
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
        private array $headers = [],
        array|string $body = '',
    ) {
        $this->body = is_string($body)
            ? $body
            : (json_encode($body)
                ?: throw new \RuntimeException(json_last_error_msg()));
    }

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
