<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api;

use Membrane\MockServer\Database;

final class Module implements \Atto\Framework\Module\ModuleInterface
{
    /**
     * @return array<class-string, array{class?: class-string, args?: array<mixed>}>
     */
    public function getServices(): array
    {
        return [
            Handler\Reset::class
                => [
                    'args' => [\Atto\Db\Migrator::class],
                ],
            Handler\AddOperation::class
                => [
                    'args' => [Database\Repository\Operation::class],
                ],
            Handler\AddMatcher::class
                => [
                    'args' => [
                        Database\Repository\Matcher::class,
                        Database\IdGenerator::class,
                    ],
                ],
            Handler\DeleteOperation::class
                => [
                    'args' => [
                        Database\Repository\Operation::class,
                        Database\Repository\Matcher::class,
                    ],
                ],
            Handler\DeleteMatcher::class
                => [
                    'args' => [Database\Repository\Matcher::class],
                ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getConfig(): array
    {
        return [
            'membrane' => [
                'operationMap' => [
                    'reset' => [
                        'dto' => Command\Reset::class,
                        'handler' => Handler\Reset::class,
                    ],
                    'add-operation' => [
                        'dto' => Command\AddOperation::class,
                        'handler' => Handler\AddOperation::class,
                    ],
                    'delete-operation' => [
                        'dto' => Command\DeleteOperation::class,
                        'handler' => Handler\DeleteOperation::class,
                    ],
                    'add-matcher' => [
                        'dto' => Command\AddMatcher::class,
                        'handler' => Handler\AddMatcher::class,
                    ],
                    'delete-matcher' => [
                        'dto' => Command\DeleteMatcher::class,
                        'handler' => Handler\DeleteMatcher::class,
                    ],
                ],
            ],
        ];
    }
}
