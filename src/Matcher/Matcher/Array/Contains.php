<?php

declare(strict_types=1);

namespace Membrane\MockServer\Matcher\Matcher\Array;

use Membrane\Attribute\Ignored;
use Membrane\Attribute\Placement;
use Membrane\Attribute\SetFilterOrValidator;
use Membrane\Filter\CreateObject\FromArray;
use Membrane\MockServer\Mocking\DTO;
use Membrane\MockServer\Mocking\Field;

/**
 * @phpstan-type Config array{
 *     field: non-empty-list<string>,
 *     values: list<mixed>,
 * }
 */
#[SetFilterOrValidator(new FromArray(Contains::class), Placement::AFTER)]
final readonly class Contains implements \Membrane\MockServer\Matcher\Matcher
{
    /** @var mixed[]  */
    #[Ignored]
    private array $values;

    public function __construct(
        #[Ignored]
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

    /** @param Config $config */
    public static function fromArray(array $config): self
    {
        return new self(
            Field::fromArray($config['field']),
            ...$config['values'],
        );
    }
}
