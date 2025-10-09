<?php

declare(strict_types=1);

namespace Membrane\MockServer\Database;

final class Module implements \Atto\Framework\Module\ModuleInterface
{
    /**
     * @return array<class-string, array{
     *     class?: class-string,
     *     args?: array<mixed>,
     * }>
     */
    public function getServices(): array
    {
        return [
            Repository\Matcher::class => [
                'class' => Repository\Matcher\Sql::class,
                'args' => [\Doctrine\DBAL\Connection::class],
            ],
            Repository\Operation::class => [
                'class' => Repository\Operation\Sql::class,
                'args' => [\Doctrine\DBAL\Connection::class],
            ],
        ];
    }

    /**
     * @return array{
     *     schemas: list<class-string<\Atto\Db\TableSchema>>,
     * }
     */
    public function getConfig(): array
    {
        return [
            'schemas' => [
                \Membrane\MockServer\Database\Schema\MatcherTable::class,
                \Membrane\MockServer\Database\Schema\OperationTable::class,
            ],
        ];
    }
}
