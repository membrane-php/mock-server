<?php

declare(strict_types=1);

namespace Membrane\MockServer\Repository\Operation;

use Membrane\MockServer\Generated\Repository\MockServer\Model\SQLite;
use Membrane\MockServer\Repository\Operation;

/**
 * @TODO this cannot handle shorthand `int` responses, they must be arrays
 * @TODO this cannot handle shorthand `string` response bodies, they must be arrays
 */
final class Sql extends Sqlite\OperationRepository implements Operation {}
