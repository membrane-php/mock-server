<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api\Handler;

use Atto\Db\Migrator;
use Doctrine\DBAL\Exception\TableNotFoundException;
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
        $this->migrator->drop();
        $this->migrator->migrate();

        return new Response(204);
    }
}
