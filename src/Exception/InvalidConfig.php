<?php

declare(strict_types=1);

namespace Membrane\MockServer\Exception;

final class InvalidConfig extends \RuntimeException implements \Membrane\MockServer\Exception
{
    /** @param list<string> $field */
    public function __construct(
        public array $field = [],
        string $message = "",
    ) {
        parent::__construct($message);
    }

    /** @param list<string> $field */
    public static function field(array $field): self
    {
        return new self($field, <<<MESSAGE
                "field" is required and MUST be a non-empty list of strings.
                This list identifies a field in requests
                i.e.
                    - ["path", "id"]
                    - ["query", "tags"]
                    - ["requestBody", "pet", "species"]
                MESSAGE);
    }
}
