<?php

declare(strict_types=1);

namespace Membrane\MockServer\Tests\Fixture;

use Membrane\MockServer\Mocking\DTO;

final readonly class Matcher implements \Membrane\MockServer\Matcher\Matcher
{
    public function __construct(
        private ?DTO $expects = null,
        private bool $matches = true,
    ) {}

    /**
     * @throws Exception\FailedExpectation if DTO does not match expected
     */
    public function matches(DTO $dto): bool
    {
        if (
            isset($this->expects)
            && $this->expects->request != $dto->request
        ) {
            throw new Exception\FailedExpectation(sprintf(
                <<<MESSAGE
                Unexpected DTO.

                Expected:
                %s

                Actual:
                %s
                MESSAGE,
                json_encode($this->expects->request, JSON_PRETTY_PRINT),
                json_encode($dto->request, JSON_PRETTY_PRINT),
            ));
        }

        return $this->matches;
    }
}
