<?php

declare(strict_types=1);

namespace Membrane\MockServer\Database\Schema;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;

final readonly class OperationTable implements \Atto\Db\TableSchema
{
    public function __invoke(Schema $schema): void
    {
        $table = $schema->createTable('Operation');

        $table->addColumn('operationId', 'string', ['length' => 255]);
        $table->addPrimaryKeyConstraint(PrimaryKeyConstraint::editor()
            ->setUnquotedColumnNames('operationId')
            ->create());

        $table->addColumn('defaultResponseCode', 'integer');
        $table->addColumn('defaultResponseHeaders', 'jsonb');
        $table->addColumn('defaultResponseBody', 'string');
    }
}
