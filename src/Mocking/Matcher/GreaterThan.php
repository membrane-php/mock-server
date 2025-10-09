<?php

declare(strict_types=1);

namespace Membrane\MockServer\Mocking\Matcher;

use Membrane\MockServer\Mocking\DTO;
use Membrane\MockServer\Mocking\Field;

final readonly class GreaterThan implements \Membrane\MockServer\Mocking\Matcher
{
    public function __construct(
        private Field $field,
        private int|float $limit,
        private bool $inclusive = true,
    ) {}

    public function matches(DTO $dto): bool
    {
        $value = $this->field->find($dto);

        return is_numeric($value)
            && ($this->inclusive
                ? $value >= $this->limit
                : $value > $this->limit);
    }
}
