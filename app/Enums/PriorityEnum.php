<?php

namespace App\Enums;

enum PriorityEnum: string
{
    case HIGHEST = 'Highest';
    case HIGH = 'High';
    case MIDIUM = 'Midium';
    case LOW = 'Low';
    case LOWEST = 'Lowest';


    public function color(): string
    {
        return match ($this) {
            PriorityEnum::HIGHEST => 'red',
            PriorityEnum::HIGH => 'orange',
            PriorityEnum::MIDIUM => 'yellow',
            PriorityEnum::LOW => 'lime',
            PriorityEnum::LOWEST => 'green',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            PriorityEnum::HIGHEST => 'chevron-double-up',
            PriorityEnum::HIGH => 'chevron-up',
            PriorityEnum::MIDIUM => 'bars-2',
            PriorityEnum::LOW => 'chevron-down',
            PriorityEnum::LOWEST => 'chevron-double-down',
        };
    }
}
