<?php

declare(strict_types=1);

namespace Membrane\MockServer\Repository\Operation;

use Membrane\MockServer\Generated\Repository\MockServer\Model\SQLite;
use Membrane\MockServer\Repository\Operation;

final class Sql extends Sqlite\OperationRepository implements Operation {}
