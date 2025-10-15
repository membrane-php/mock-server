<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api\Handler;

use Atto\Db\Migrator;
use Membrane\MockServer\Api\Command;
use Membrane\MockServer\Api\Response;

final readonly class Reset
{
    public function __construct(
        private string $storagePath,
        private Migrator $migrator,
    ) {}

    public function __invoke(Command\Reset $command): Response
    {
        if (file_exists($this->storagePath)) {
            file_put_contents($this->storagePath, '');
        }

        $this->migrator->migrate();

        return new Response(204);
    }
}
