<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api;

final class Module implements \Atto\Framework\Module\ModuleInterface
{
    /**
     * @return array<class-string, array{class?: class-string, args?: array<mixed>}>
     */
    public function getServices(): array
    {
        return [
            // each handler needs to have config to wire up with
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
                        // delete db sqlite
                        // run migrations again
                        'dto',
                        'handler',
                    ],
                    'add-operation' => [
                        // take operationid
                        // require operationrepository
                        // save
                    ],
                    'delete-operation' => [
                        // take operationid
                        // require operationrepository
                        // delete
                    ],
                    'add-matcher' => [

                    ],
                    'delete-matcher' => [

                    ],
                ]
            ]
        ];
    }
}
