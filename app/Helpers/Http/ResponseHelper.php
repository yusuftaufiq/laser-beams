<?php

declare(strict_types=1);

namespace App\Helpers\Http;

use Phpro\ApiProblem\Http\NotFoundProblem;
use Phpro\ApiProblem\Http\ValidationApiProblem;
use Swoole\Http\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ResponseHelper
{
    final public function __construct(
        private ?string $content = null,
        private ?int $statusCode = null,
    ) {
    }

    final public static function success(string $message, array $data = []): self
    {
        $result = self::format(StatusCodeHelper::HTTP_OK, title: 'OK', detail: $message, data: $data);

        return new self(content: $result, statusCode: StatusCodeHelper::HTTP_OK);
    }

    final public static function created(string $message, array $data = []): self
    {
        $result = self::format(StatusCodeHelper::HTTP_CREATED, title: 'Created', detail: $message, data: $data);

        return new self(content: $result, statusCode: StatusCodeHelper::HTTP_CREATED);
    }

    final public static function badRequest(ConstraintViolationListInterface $violations): self
    {
        $result = new ValidationApiProblem($violations);

        return new self(content: json_encode($result->toArray()), statusCode: StatusCodeHelper::HTTP_BAD_REQUEST);
    }

    final public static function notFound(string $message): self
    {
        $result = new NotFoundProblem($message);

        return new self(content: json_encode($result->toArray()), statusCode: StatusCodeHelper::HTTP_NOT_FOUND);
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

    final public function setContent(?string $content = null): self
    {
        $this->content = $content;

        return $this;
    }

    final public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    final public function send(Response $response): self
    {
        $response->setHeader('Content-Type', 'application/json');
        $response->setStatusCode($this->statusCode);
        $response->end($this->content);

        return $this;
    }
}
