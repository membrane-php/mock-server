<?php

declare(strict_types=1);

namespace Membrane\MockServer\Schema;

use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;

final readonly class MatcherTable implements \Atto\Db\TableSchema
{
    public function __invoke(Schema $schema): void
    {
        $table = $schema->createTable('Matcher');

        $table->addColumn('id', 'string', ['length' => 50]);
        $table->addPrimaryKeyConstraint(PrimaryKeyConstraint::editor()
            ->setUnquotedColumnNames('id')
            ->create());

        $table->addColumn('operationId', 'string', ['length' => 255]);
        $table->addForeignKeyConstraint('Operation', ['operationId'], ['operationId']);

        $table->addColumn('alias', 'string', ['length' => 255]);

        $table->addColumn('args', 'jsonb');

        $table->addColumn('response', 'jsonb');
    }
}
