<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher;

use Membrane\MockServer\DTO;
use Membrane\MockServer\Field;

final class HasField implements \Membrane\MockServer\Matcher
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
            if ($field->find($dto->request) === null) {
                return false;
            };
        }

        return true;
    }

}
