<?php

declare(strict_types=1);

namespace Membrane\MockServer\Mocking\Matcher;

use Membrane\MockServer\Mocking\DTO;
use Membrane\MockServer\Mocking\Field;

final class Exists implements \Membrane\MockServer\Mocking\Matcher
{
    /** @var Field[] */
    private array $fields;

    public function __construct(
        Field $field,
        Field ...$fields,
    ) {
        $this->fields = [$field, ...$fields];
    }

    public function matches(DTO $dto): bool
    {
        foreach ($this->fields as $field) {
            if ($field->find($dto) === null) {
                return false;
            };
        }

        return true;
    }

}
