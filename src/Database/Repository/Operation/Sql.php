<?php

declare(strict_types=1);

namespace Membrane\MockServer\Database\Repository\Operation;

use Membrane\MockServer\Database\Repository;
use Membrane\MockServer\Generated\Repository\MockServer\Model\SQLite;

final class Sql extends Sqlite\OperationRepository implements Repository\Operation {}
