<?php

namespace App\Enums;

enum ConfigEnum: int
{
    case TASK_DEFAULT_PRIORITY_ID = 1;

    case WORKSTREAM_DEFAULT_PRIORITY_ID = 2;

    case PAGINATION_ITEMS = 3;
}
