<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher;

use Membrane\MockServer\DTO;
use Membrane\MockServer\Field;

final readonly class Regex implements \Membrane\MockServer\Matcher
{
    public function __construct(
        private Field $field,
        private string $regex,
    ) {
    }

    public function matches(DTO $dto): bool
    {
        $value = $this->field->find($dto->request);

        return is_string($value)
            && preg_match($this->regex, $value) === 1;
    }
}
