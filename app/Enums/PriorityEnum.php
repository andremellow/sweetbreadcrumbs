<?php

namespace App\Enums;

enum PriorityEnum: string
{
    case LOW = 'Low';
    case MID = 'Mid';
    case HIGH = 'High';
    case URGENT = 'Urgent';

    public function color(): string
    {
        return match ($this) {
            PriorityEnum::LOW => 'green',
            PriorityEnum::MID => 'yellow',
            PriorityEnum::HIGH => 'pink',
            PriorityEnum::URGENT => 'red',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            PriorityEnum::LOW => 'arrow-down',
            PriorityEnum::MID => 'minus',
            PriorityEnum::HIGH => 'arrow-up',
            PriorityEnum::URGENT => 'exclamation-triangle',
        };
    }
}
