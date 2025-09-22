<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher;

use Membrane\MockServer\DTO;
use Membrane\MockServer\Field;

final readonly class Equals implements \Membrane\MockServer\Matcher
{
    public function __construct(
        private Field $field,
        private mixed $value
    ) {
    }

    public function matches(DTO $dto): bool
    {
        $value = $this->field->find($dto->request);

        return $value === $this->value;
    }
}
