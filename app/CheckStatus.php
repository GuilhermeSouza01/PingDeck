<?php

namespace App;

enum CheckStatus: string
{
    case ONLINE = 'online';
    case OFFLINE = 'offline';
    case TIMEOUT = 'timeout';

    public function label(): string
    {
        return match ($this) {
            self::ONLINE => 'Online',
            self::OFFLINE => 'Offline',
            self::TIMEOUT => 'Timeout',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ONLINE => 'success',
            self::OFFLINE => 'error',
            self::TIMEOUT => 'warning',
        };
    }
}
