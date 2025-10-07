<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher;

use Membrane\MockServer\DTO;
use Membrane\MockServer\Field;

/**
 * Equals performs non-strict equality checks.
 *
 * This is necessary for 'path' and 'query' parameters encoded in URI strings
 */
final readonly class Equals implements \Membrane\MockServer\Matcher
{
    public function __construct(
        private Field $field,
        private mixed $value,
    ) {}

    public function matches(DTO $dto): bool
    {
        $value = $this->field->find($dto);

        // Non-strict comparison for 'path' and 'query' parameters
        return $value == $this->value;
    }
}
