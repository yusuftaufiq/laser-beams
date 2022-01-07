<?php

declare(strict_types=1);

namespace App\Helpers;

use Swoole\Http\Response;

final class ResponseHelper
{
    final public function __construct(
        public ?string $content = null,
    ) {
    }

    final public static function success(Response $response, object|array $data = new \stdClass()): void
    {
        $result = self::format('Success', 'OK', $data);

        self::setContent($result)->send($response, StatusCodeHelper::HTTP_OK);
    }

    final public static function created(Response $response, object|array $data = new \stdClass()): void
    {
        $result = self::format('Success', 'OK', $data);

        self::setContent($result)->send($response, StatusCodeHelper::HTTP_CREATED);
    }

    final public static function badRequest(Response $response, string $message): void
    {
        $result = self::format('Bad Request', $message);

        self::setContent($result)->send($response, StatusCodeHelper::HTTP_BAD_REQUEST);
    }

    final public static function notFound(Response $response, string $message): void
    {
        $result = self::format('Not Found', $message);

        self::setContent($result)->send($response, StatusCodeHelper::HTTP_NOT_FOUND);
    }

    final public static function format(
        string $status,
        string $message,
        object|array $data = new \stdClass(),
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
