<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Fixture;

use Membrane\MockServer\Database\Model\Operation;

final readonly class ProvidesOperations
{
    /** @return \Generator<Operation> */
    public static function generate(): \Generator
    {
        yield new Operation(
            'findPetById',
            200,
            ['Cache-control' => ['age=180']],
            '{"name":"Gato","age":6,"id":5}',
        );

        yield new Operation(
            'add-pet',
            201,
            [],
            ['name' => 'Custard', 'age' => 12, 'id' => 2],
        );

        yield new Operation(
            'list-pets',
            200,
            ['Cache-control' => ['age=300']],
            [
                ['name' => 'Toffee', 'age' => 3, 'id' => 1],
                ['name' => 'Custard', 'age' => 12, 'id' => 2],
                ['name' => 'Mustard', 'age' => 53, 'id' => 3],
            ],
        );
    }


}
