<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api\Command;

use Membrane\Attribute\Placement;
use Membrane\Attribute\SetFilterOrValidator;
use Membrane\Attribute\Subtype;
use Membrane\Filter\CreateObject\WithNamedArguments;
use Membrane\Filter\Shape\Delete;
use Membrane\Filter\Shape\Pluck;
use Membrane\Filter\Shape\Rename;

#[SetFilterOrValidator(new Pluck('default', 'response'), Placement::BEFORE)]
#[SetFilterOrValidator(new Delete('default'), Placement::BEFORE)]
#[SetFilterOrValidator(new Pluck('response', 'code', 'headers', 'body'), Placement::BEFORE)]
#[SetFilterOrValidator(new Delete('response'), Placement::BEFORE)]
#[SetFilterOrValidator(new Rename('code', 'defaultResponseCode'), Placement::BEFORE)]
#[SetFilterOrValidator(new Rename('headers', 'defaultResponseHeaders'), Placement::BEFORE)]
#[SetFilterOrValidator(new Rename('body', 'defaultResponseBody'), Placement::BEFORE)]
#[SetFilterOrValidator(new WithNamedArguments(AddOperation::class), Placement::AFTER)]
final readonly class AddOperation
{
    /**
     * @param array<string, string|list<string>> $defaultResponseHeaders
     */
    public function __construct(
        public string $operationId,
        public int $defaultResponseCode,
        #[Subtype('string')]
        public array $defaultResponseHeaders = [],
        public string $defaultResponseBody = '',
    ) {}
}
