<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher\Matcher\String;

use Membrane\Attribute\Placement;
use Membrane\Attribute\SetFilterOrValidator;
use Membrane\Filter\CreateObject\WithNamedArguments;
use Membrane\MockServer\Matcher\Matcher\GreaterThan;
use Membrane\MockServer\Mocking\DTO;
use Membrane\MockServer\Mocking\Field;

#[SetFilterOrValidator(new WithNamedArguments(GreaterThan::class), Placement::AFTER)]
final readonly class Regex implements \Membrane\MockServer\Matcher\Matcher
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
