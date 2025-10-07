<?php

declare(strict_types=1);

namespace Membrane\MockServer;

final readonly class Field
{
    /** @var list<string> */
    private array $path;

    public function __construct(
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

    /** @param non-empty-list<string> $path */
    public static function fromConfig(array $path): self
    {
        $name = array_pop($path);
        return new self($name, ...$path);
    }
}
