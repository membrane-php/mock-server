<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher\Matcher\Array;

use Membrane\MockServer\Mocking\DTO;
use Membrane\MockServer\Mocking\Field;

final readonly class Contains implements \Membrane\MockServer\Matcher\Matcher
{
    /** @var mixed[]  */
    private array $values;

    public function __construct(
        private Field $field,
        mixed $value,
        mixed ...$values,
    ) {
        $this->values = [$value, ...$values];
    }

    public function matches(DTO $dto): bool
    {
        $fieldValues = $this->field->find($dto);

        if (!is_array($fieldValues)) {
            return false;
        }

        foreach ($this->values as $value) {
            foreach ($fieldValues as $fieldValue) {
                if ($value === $fieldValue) {
                    continue 2;
                }
            }
            return false;
        }

        return true;
    }
}
