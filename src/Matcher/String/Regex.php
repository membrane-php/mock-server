<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher\String;

use Membrane\MockServer\DTO;
use Membrane\MockServer\Field;

final readonly class Regex implements \Membrane\MockServer\Matcher
{
    public function __construct(
        private Field $field,
        private string $pattern,
    ) {}

    public function matches(DTO $dto): bool
    {
        $value = $this->field->find($dto);

        return is_string($value)
            && preg_match($this->pattern, $value) === 1;
    }
}
