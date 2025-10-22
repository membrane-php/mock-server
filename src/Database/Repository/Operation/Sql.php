<?php

declare(strict_types=1);

namespace Membrane\MockServer\Database\Repository\Operation;

use Membrane\MockServer\Database\Repository;
use Membrane\MockServer\Generated\Api\Repository\MockServer\Database\Model\SQLite;

final class Sql extends Sqlite\OperationRepository implements Repository\Operation {}
