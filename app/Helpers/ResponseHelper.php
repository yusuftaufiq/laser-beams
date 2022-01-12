<?php

declare(strict_types=1);

namespace App\Helpers;

use Phpro\ApiProblem\Http\BadRequestProblem;
use Phpro\ApiProblem\Http\NotFoundProblem;
use Swoole\Http\Response;

final class ResponseHelper
{
    final public function __construct(
        public ?string $content = null,
        public ?int $statusCode = null,
    ) {
    }

    final public static function success(string $message, array $data = []): self
    {
        $result = self::format(StatusCodeHelper::HTTP_OK, title: 'OK', detail: $message, data: $data);

        return self::setContent($result)->setStatusCode(StatusCodeHelper::HTTP_OK);
    }

    final public static function created(Response $response, string $message, array $data = []): void
    {
        $result = self::format(StatusCodeHelper::HTTP_CREATED, title: 'Created', detail: $message, data: $data);

        self::setContent($result)
            ->setStatusCode(StatusCodeHelper::HTTP_CREATED)
            ->send($response);
    }

    final public static function badRequest(Response $response, string $message): void
    {
        $result = new BadRequestProblem($message);

        self::setContent(json_encode($result->toArray()))
            ->setStatusCode(StatusCodeHelper::HTTP_BAD_REQUEST)
            ->send($response);
    }

    final public static function notFound(Response $response, string $message): void
    {
        $result = new NotFoundProblem($message);

        self::setContent(json_encode($result->toArray()))
            ->setStatusCode(StatusCodeHelper::HTTP_NOT_FOUND)
            ->send($response);
    }

    final public static function format(
        int $status,
        string $title,
        string $detail,
        string $type = 'about:blank',
        array $data = [],
    ): string {
        return json_encode(array_merge([
            'status' => $status,
            'type' => $type,
            'title' => $title,
            'detail' => $detail,
        ], $data));
    }

    final public static function setContent(?string $content = null): self
    {
        return new self($content);
    }

    final public function setStatusCode(int $statusCode): self
    {
        return new self($this->content, $statusCode);
    }

    final public function send(Response $response): void
    {
        $response->setHeader('Content-Type', 'application/json');
        $response->setStatusCode($this->statusCode);
        $response->end($this->content);
    }
}
