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
    private string $responseBody;

    /**
     * @param array<mixed> $args
     * @param array<string, string|list<string>> $responseHeaders
     * @param array<mixed> $responseBody
     */
    public function __construct(
        #[Id]
        public string $id,
        public string $operationId,
        private string $alias,
        #[Subtype('string')]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private array $args,
        private int $responseCode,
        #[Subtype('string')]
        #[SerializationStrategy(SerializationStrategyType::Json)]
        private array $responseHeaders = [],
        array|string $responseBody = '', //TODO these are probably named wrong compared to the table and require the #name
        // attribute, or rename the model
    ) {
        $this->responseBody = is_string($responseBody)
            ? $responseBody
            : (json_encode($responseBody)
                ?: throw new \RuntimeException(json_last_error_msg()));
    }

    /** @return array{matcher: MatcherFactoryConfig, response: ResponseConfig} */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'operationId' => $this->operationId,
            'matcher' => [
                'type' => $this->alias,
                'args' => $this->args,
            ],
            'response' => [
                'code' => $this->responseCode,
                'headers' => $this->responseHeaders,
                'body' => $this->responseBody,
            ],
        ];
    }
}
