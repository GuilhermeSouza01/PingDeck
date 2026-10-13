<?php

namespace App;

enum ApiMethod: string
{
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case PATCH = 'PATCH';
    case DELETE = 'DELETE';

    public function badgeColor(): string
    {
        return match ($this) {
            self::GET => 'badge-info',
            self::POST => 'badge-primary',
            self::PUT, self::PATCH => 'badge-warning',
            self::DELETE => 'badge-error',
        };
    }
}
