<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api\Handler;

final readonly class Reset
{
    public function __construct(
        private string $storagePath,
    ) {}

    public function __invoke(): void
    {
        unlink($this->storagePath);
        eval(file_get_contents(__DIR__ . '../../../bin/migrate.php'));
    }
}
