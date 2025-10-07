<?php

declare(strict_types=1);

namespace Membrane\MockServer;

use Membrane\Attribute\Ignored;
use Membrane\Attribute\Placement;
use Membrane\Attribute\SetFilterOrValidator;
use Membrane\Filter\CreateObject\FromArray;

/**
 * @phpstan-type DTOArray array{
 *     request: array{
 *         method: string,
 *         operationId: string,
 *     },
 *     path?: array<string, mixed>,
 *     query?: array<string, mixed>,
 *     header?: array<string, mixed>,
 *     cookie?: array<string, mixed>,
 *     body?: mixed,
 *  }
 */
#[SetFilterOrValidator(new FromArray(DTO::class), Placement::AFTER)]
final class DTO
{
    /** @param DTOArray $request */
    public function __construct(
        #[Ignored]
        public array $request,
    ) {}

    /** @param DTOArray $value */
    public static function fromArray(array $value): DTO
    {
        return new DTO($value);
    }
}
