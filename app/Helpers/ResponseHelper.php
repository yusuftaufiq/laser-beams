<?php

declare(strict_types=1);

namespace App\Helpers;

final class ResponseHelper
{
    final public const HTTP_CONTINUE = 100;

    final public const HTTP_SWITCHING_PROTOCOLS = 101;

    final public const HTTP_PROCESSING = 102;            // RFC2518

    final public const HTTP_EARLY_HINTS = 103;           // RFC8297

    final public const HTTP_OK = 200;

    final public const HTTP_CREATED = 201;

    final public const HTTP_ACCEPTED = 202;

    final public const HTTP_NON_AUTHORITATIVE_INFORMATION = 203;

    final public const HTTP_NO_CONTENT = 204;

    final public const HTTP_RESET_CONTENT = 205;

    final public const HTTP_PARTIAL_CONTENT = 206;

    final public const HTTP_MULTI_STATUS = 207;          // RFC4918

    final public const HTTP_ALREADY_REPORTED = 208;      // RFC5842

    final public const HTTP_IM_USED = 226;               // RFC3229

    final public const HTTP_MULTIPLE_CHOICES = 300;

    final public const HTTP_MOVED_PERMANENTLY = 301;

    final public const HTTP_FOUND = 302;

    final public const HTTP_SEE_OTHER = 303;

    final public const HTTP_NOT_MODIFIED = 304;

    final public const HTTP_USE_PROXY = 305;

    final public const HTTP_RESERVED = 306;

    final public const HTTP_TEMPORARY_REDIRECT = 307;

    final public const HTTP_PERMANENTLY_REDIRECT = 308;  // RFC7238

    final public const HTTP_BAD_REQUEST = 400;

    final public const HTTP_UNAUTHORIZED = 401;

    final public const HTTP_PAYMENT_REQUIRED = 402;

    final public const HTTP_FORBIDDEN = 403;

    final public const HTTP_NOT_FOUND = 404;

    final public const HTTP_METHOD_NOT_ALLOWED = 405;

    final public const HTTP_NOT_ACCEPTABLE = 406;

    final public const HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;

    final public const HTTP_REQUEST_TIMEOUT = 408;

    final public const HTTP_CONFLICT = 409;

    final public const HTTP_GONE = 410;

    final public const HTTP_LENGTH_REQUIRED = 411;

    final public const HTTP_PRECONDITION_FAILED = 412;

    final public const HTTP_REQUEST_ENTITY_TOO_LARGE = 413;

    final public const HTTP_REQUEST_URI_TOO_LONG = 414;

    final public const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;

    final public const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;

    final public const HTTP_EXPECTATION_FAILED = 417;

    final public const HTTP_I_AM_A_TEAPOT = 418;                                               // RFC2324

    final public const HTTP_MISDIRECTED_REQUEST = 421;                                         // RFC7540

    final public const HTTP_UNPROCESSABLE_ENTITY = 422;                                        // RFC4918

    final public const HTTP_LOCKED = 423;                                                      // RFC4918

    final public const HTTP_FAILED_DEPENDENCY = 424;                                           // RFC4918

    final public const HTTP_TOO_EARLY = 425;                                                   // RFC-ietf-httpbis-replay-04

    final public const HTTP_UPGRADE_REQUIRED = 426;                                            // RFC2817

    final public const HTTP_PRECONDITION_REQUIRED = 428;                                       // RFC6585

    final public const HTTP_TOO_MANY_REQUESTS = 429;                                           // RFC6585

    final public const HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;                             // RFC6585

    final public const HTTP_UNAVAILABLE_FOR_LEGAL_REASONS = 451;

    final public const HTTP_INTERNAL_SERVER_ERROR = 500;

    final public const HTTP_NOT_IMPLEMENTED = 501;

    final public const HTTP_BAD_GATEWAY = 502;

    final public const HTTP_SERVICE_UNAVAILABLE = 503;

    final public const HTTP_GATEWAY_TIMEOUT = 504;

    final public const HTTP_VERSION_NOT_SUPPORTED = 505;

    final public const HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;                        // RFC2295

    final public const HTTP_INSUFFICIENT_STORAGE = 507;                                        // RFC4918

    final public const HTTP_LOOP_DETECTED = 508;                                               // RFC5842

    final public const HTTP_NOT_EXTENDED = 510;                                                // RFC2774

    final public const HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;                             // RFC6585

    final public static function success(mixed $data): string
    {
        return json_encode([
            'status' => 'Success',
            'message' => 'OK',
            'data' => $data,
        ]);
    }

    final public static function notFound(string $message): string
    {
        return json_encode([
            'status' => 'Not Found',
            'message' => $message,
            'data' => (object) [],
        ]);
    }

    final public static function badRequest(string $message): string
    {
        return json_encode([
            'status' => 'Bad Request',
            'message' => $message,
            'data' => (object) [],
        ]);
    }
}
