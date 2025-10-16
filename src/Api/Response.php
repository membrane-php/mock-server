<?php

declare(strict_types=1);

namespace Membrane\MockServer\Api;

use Atto\Framework\Response\HasResponseInfo;

final readonly class Response implements \JsonSerializable, HasResponseInfo
{
    public function __construct(
        private int $statusCode,
        private ?\JsonSerializable $model = null,
    ) {}

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function jsonSerialize(): ?\JsonSerializable
    {
        return $this->model;
    }
}
