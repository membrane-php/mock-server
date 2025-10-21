<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api\Command;

use Membrane\Attribute\Placement;
use Membrane\Attribute\SetFilterOrValidator;
use Membrane\Filter\CreateObject\WithNamedArguments;

#[SetFilterOrValidator(new WithNamedArguments(Reset::class), Placement::AFTER)]
final readonly class Reset {}
