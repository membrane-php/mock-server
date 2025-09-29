<?php

declare(strict_types=1);

namespace Membrane\MockServer;

final readonly class Field
{
    /** @var string[] */
    private array $path;

    public function __construct(
        private string $name,
        string ...$path
    ) {
        $this->path = $path;
    }

    public function find(DTO $dto): mixed
    {
        $data = $dto->request;

        foreach ($this->path as $item) {
            $data = $data[$item] ?? [];
        }

        return $data[$this->name] ?? null;
    }
}
