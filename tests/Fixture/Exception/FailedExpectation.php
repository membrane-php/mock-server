<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Fixture\Exception;

use Membrane\MockServer\Exception;

final class FailedExpectation extends \RuntimeException implements Exception {}
