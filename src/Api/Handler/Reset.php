<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api\Handler;

use Atto\Db\Migrator;
use Membrane\MockServer\Api\Command;

final readonly class Reset
{
    public function __construct(
        private string $storagePath,
        private Migrator $migrator,
    ) {}

    public function __invoke(Command\Reset $command): void
    {
        unlink($this->storagePath);
        $this->migrator->migrate();
    }
}
