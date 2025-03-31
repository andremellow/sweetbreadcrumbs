<?php

namespace App\Enums;

enum CapabilityGroupEnum: string
{
    case BLOCKERS = 'Blockers';
    case INVITES = 'Invites';
    case MEETINGS = 'Meetings';
    case ORGANIZATION = 'Organization';
    case RISKS = 'Risks';
    case TASKS = 'Tasks';
    case WORKSTREAMS = 'Workstreams';
}
