<?php

declare(strict_types=1);

namespace Fixture\Exception;

use Membrane\MockServer\Exception;

final class Unexpected extends \RuntimeException implements Exception {}
