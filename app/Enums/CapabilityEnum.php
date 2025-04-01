<?php

namespace App\Enums;

enum CapabilityEnum: int
{
    // WORKSTREAMS Group
    case CREATE_WORKSTREAMS = 1;
    case EDIT_WORKSTREAMS = 2;
    case DELETE_WORKSTREAMS = 3;
    case VIEW_WORKSTREAMS = 4;
    case MANAGE_ALL_WORKSTREAMS = 5;

    // BLOCKERS Group
    case CREATE_BLOCKERS = 6;
    case EDIT_BLOCKERS = 7;
    case DELETE_BLOCKERS = 8;
    case VIEW_BLOCKERS = 9;
    case MANAGE_ALL_BLOCKERS = 10;

    // INVITES Group
    case CREATE_INVITES = 11;
    case EDIT_INVITES = 12;
    case DELETE_INVITES = 13;
    case VIEW_INVITES = 14;
    case MANAGE_ALL_INVITES = 15;

    // MEETINGS Group
    case CREATE_MEETINGS = 16;
    case EDIT_MEETINGS = 17;
    case DELETE_MEETINGS = 18;
    case VIEW_MEETINGS = 19;
    case MANAGE_ALL_MEETINGS = 20;

    // ORGANIZATION Group
    case MANAGER_ORGANIZATION_SETTINGS = 21;
    case EDIT_ORGANIZATION = 22;
    case DELETE_ORGANIZATION = 23;
    case VIEW_ORGANIZATION = 24;
    case MANAGER_ORGANIZATION_TEAM = 25;

    // RISKS Group
    case CREATE_RISKS = 26;
    case EDIT_RISKS = 27;
    case DELETE_RISKS = 28;
    case VIEW_RISKS = 29;
    case MANAGE_ALL_RISKS = 30;

    // TASKS Group
    case CREATE_TASKS = 31;
    case EDIT_TASKS = 32;
    case DELETE_TASKS = 33;
    case VIEW_TASKS = 34;
    case MANAGE_ALL_TASKS = 35;

    public function group(): CapabilityGroupEnum
    {
        return match ($this) {
            // WORKSTREAMS Group
            self::CREATE_WORKSTREAMS,
            self::EDIT_WORKSTREAMS,
            self::DELETE_WORKSTREAMS,
            self::VIEW_WORKSTREAMS,
            self::MANAGE_ALL_WORKSTREAMS => CapabilityGroupEnum::WORKSTREAMS,

            // BLOCKERS Group
            self::CREATE_BLOCKERS,
            self::EDIT_BLOCKERS,
            self::DELETE_BLOCKERS,
            self::VIEW_BLOCKERS,
            self::MANAGE_ALL_BLOCKERS => CapabilityGroupEnum::BLOCKERS,

            // INVITES Group
            self::CREATE_INVITES,
            self::EDIT_INVITES,
            self::DELETE_INVITES,
            self::VIEW_INVITES,
            self::MANAGE_ALL_INVITES => CapabilityGroupEnum::INVITES,

            // MEETINGS Group
            self::CREATE_MEETINGS,
            self::EDIT_MEETINGS,
            self::DELETE_MEETINGS,
            self::VIEW_MEETINGS,
            self::MANAGE_ALL_MEETINGS => CapabilityGroupEnum::MEETINGS,

            // ORGANIZATION Group
            self::MANAGER_ORGANIZATION_SETTINGS,
            self::EDIT_ORGANIZATION,
            self::DELETE_ORGANIZATION,
            self::VIEW_ORGANIZATION,
            self::MANAGER_ORGANIZATION_TEAM => CapabilityGroupEnum::ORGANIZATION,

            // RISKS Group
            self::CREATE_RISKS,
            self::EDIT_RISKS,
            self::DELETE_RISKS,
            self::VIEW_RISKS,
            self::MANAGE_ALL_RISKS => CapabilityGroupEnum::RISKS,

            // TASKS Group
            self::CREATE_TASKS,
            self::EDIT_TASKS,
            self::DELETE_TASKS,
            self::VIEW_TASKS,
            self::MANAGE_ALL_TASKS => CapabilityGroupEnum::TASKS,
        };
    }
}
