<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher\Matcher;

use Membrane\Attribute\Placement;
use Membrane\Attribute\SetFilterOrValidator;
use Membrane\Filter\CreateObject\WithNamedArguments;
use Membrane\MockServer\Mocking\DTO;
use Membrane\MockServer\Mocking\Field;

/**
 * Equals performs non-strict equality checks.
 *
 * This is necessary for 'path' and 'query' parameters encoded in URI strings
 */
#[SetFilterOrValidator(new WithNamedArguments(Equals::class), Placement::AFTER)]
final readonly class Equals implements \Membrane\MockServer\Matcher\Matcher
{
    public function __construct(
        private Field $field,
        private bool|float|int|string|null $value,
    ) {}

    public function matches(DTO $dto): bool
    {
        $value = $this->field->find($dto);

        // Non-strict comparison for 'path' and 'query' parameters
        return $value == $this->value;
    }
}
