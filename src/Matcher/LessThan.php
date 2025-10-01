<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher;

use Membrane\MockServer\DTO;
use Membrane\MockServer\Field;

final readonly class LessThan implements \Membrane\MockServer\Matcher
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
                ? $value <= $this->limit
                : $value < $this->limit);
    }
}
