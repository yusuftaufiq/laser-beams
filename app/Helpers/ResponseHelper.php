<?php

declare(strict_types=1);

namespace App\Helpers;

use Swoole\Http\Response;

final class ResponseHelper
{
    public function __construct(
        public ?string $content = null,
    ) {
    }

    final public static function format(
        string $status,
        string $message,
        mixed $data = new \stdClass(),
    ): string {
        return json_encode([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ]);
    }

    final public static function setContent(?string $content = null): self
    {
        return new self($content);
    }

    final public function send(Response $response, int $statusCode): void
    {
        $response->setHeader('Content-Type', 'application/json');
        $response->setStatusCode($statusCode);
        $response->end($this->content);
    }
}
