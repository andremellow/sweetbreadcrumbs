<?php

namespace App\Enums;

enum ConfigEnum: int
{
    case TASK_DEFAULT_PRIORITY_ID = 1;

    case WORKSTREAM_DEFAULT_PRIORITY_ID = 2;

    case PAGINATION_ITEMS = 3;

    case INVITE_EXPIRATION_IN_DAYS = 4;

    case INVITE_RESENT_WAIT_IN_MINUTES = 5;

    public function type(): string
    {
        return match ($this) {
            ConfigEnum::TASK_DEFAULT_PRIORITY_ID => 'int',
            ConfigEnum::WORKSTREAM_DEFAULT_PRIORITY_ID => 'int',
            ConfigEnum::PAGINATION_ITEMS => 'int',
            ConfigEnum::INVITE_EXPIRATION_IN_DAYS => 'int',
            ConfigEnum::INVITE_RESENT_WAIT_IN_MINUTES => 'int',
            default => 'mix'
        };
    }
}
