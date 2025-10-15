<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Fixture;

use Membrane\MockServer\Database\Model\Matcher;

final readonly class ProvidesMatchers
{
    /** @return \Generator<Matcher> */
    public static function generate(): \Generator
    {
        yield new Matcher(
            'abc123',
            'findPetById',
            'equals',
            ['field' => ['path', 'id'], 'value' => 5],
            200,
            ['Cache-control' => ['age=180']],
            '{"name":"Gato","age":6,"id":5}',
        );

        yield new Matcher(
            'def456',
            'list-pets',
            'less-than',
            ['field' => ['query', 'page-limit'], 'value' => 10],
            200,
            ['Cache-control' => ['age=300']],
            [
                ['name' => 'Toffee', 'age' => 3, 'id' => 5],
                ['name' => 'Custard', 'age' => 12, 'id' => 4],
            ],
        );

        yield new Matcher(
            'ghi789',
            'list-pets',
            'string.regex',
            ['field' => ['query', 'name'], 'pattern' => '#^[A-Z][a-z]$#'],
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
