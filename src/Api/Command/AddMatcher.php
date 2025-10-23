<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api\Command;

use Membrane\Attribute\Ignored;
use Membrane\Attribute\Placement;
use Membrane\Attribute\SetFilterOrValidator;
use Membrane\Attribute\Subtype;
use Membrane\Filter\CreateObject\WithNamedArguments;
use Membrane\Filter\Shape\Delete;
use Membrane\Filter\Shape\Pluck;
use Membrane\Filter\Shape\Rename;

#[SetFilterOrValidator(new Pluck('matcher', 'type', 'args'), Placement::BEFORE)]
#[SetFilterOrValidator(new Delete('matcher'), Placement::BEFORE)]
#[SetFilterOrValidator(new Rename('type', 'alias'), Placement::BEFORE)]
#[SetFilterOrValidator(new Pluck('response', 'code', 'headers', 'body'), Placement::BEFORE)]
#[SetFilterOrValidator(new Delete('response'), Placement::BEFORE)]
#[SetFilterOrValidator(new Rename('code', 'responseCode'), Placement::BEFORE)]
#[SetFilterOrValidator(new Rename('headers', 'responseHeaders'), Placement::BEFORE)]
#[SetFilterOrValidator(new Rename('body', 'responseBody'), Placement::BEFORE)]
#[SetFilterOrValidator(new WithNamedArguments(AddMatcher::class), Placement::AFTER)]
final readonly class AddMatcher
{
    /**
     * @param array<mixed> $args
     * @param array<string, string|list<string>> $responseHeaders
     */
    public function __construct(
        public string $operationId,
        public string $alias,
        #[Ignored]
        public array $args,
        public int $responseCode,
        #[Subtype('string')]
        public array $responseHeaders = [],
        public string $responseBody = '',
    ) {}
}
