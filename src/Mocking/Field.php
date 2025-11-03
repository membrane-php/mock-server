<?php

declare(strict_types=1);

namespace Membrane\MockServer\Mocking;

use Membrane\Attribute\Ignored;
use Membrane\Attribute\Placement;
use Membrane\Attribute\SetFilterOrValidator;
use Membrane\Filter\CreateObject\FromArray;

// try catch around method call, any throwable == invalid result with throwable message
//#[SetFilterOrValidator(new CallMethod(Field::class, 'filterConfig'), Placement::BEFORE)]
#[SetFilterOrValidator(new FromArray(Field::class), Placement::BEFORE)]
final readonly class Field
{
    /** @var list<string> */
    #[Ignored]
    private array $path;

    public function __construct(
        #[Ignored]
        private string $name,
        string ...$path,
    ) {
        $this->path = array_values($path);
    }

    public function find(DTO $dto): mixed
    {
        $data = $dto->request;

        foreach ([...$this->path, $this->name] as $key) {
            if (!is_array($data) || !array_key_exists($key, $data)) {
                return null;
            }

            $data = $data[$key];
        }

        return $data;
    }

    /** @param array{field: non-empty-list<string>} $config */
    public static function fromArray(array $config): self
    {
        $name = array_pop($config);
        return new self($name, ...$config);
    }
}
