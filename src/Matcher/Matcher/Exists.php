<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher\Matcher;

use Membrane\Attribute\Placement;
use Membrane\Attribute\SetFilterOrValidator;
use Membrane\Filter\CreateObject\FromArray;
use Membrane\MockServer\Mocking\DTO;
use Membrane\MockServer\Mocking\Field;

/**
 * @phpstan-type Config array{
 *     fields: non-empty-list<non-empty-list<string>>,
 * }
 */
#[SetFilterOrValidator(new FromArray(Exists::class), Placement::AFTER)]
final class Exists implements \Membrane\MockServer\Matcher\Matcher
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

    /**
     * @param Config $args
     */
    public static function fromArray(array $args): self
    {
        $fields = [];
        foreach ($args as $fieldConfig) {
            $fields [] = Field::fromArray($fieldConfig);
        }

        return new self(...$fields);
    }
}
