<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher\Matcher;

use Membrane\Attribute\Placement;
use Membrane\Attribute\SetFilterOrValidator;
use Membrane\Filter\CreateObject\WithNamedArguments;
use Membrane\MockServer\Mocking\DTO;
use Membrane\MockServer\Mocking\Field;

#[SetFilterOrValidator(new WithNamedArguments(GreaterThan::class), Placement::AFTER)]
final readonly class LessThan implements \Membrane\MockServer\Matcher\Matcher
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
