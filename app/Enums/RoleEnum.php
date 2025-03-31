<?php

namespace App\Enums;

enum RoleEnum: int
{
    case ADMIN = 1;
    case CONTRIBUTOR = 2;
    case VIEWER = 3;

    public function capabilities(): array
    {
        return match ($this) {
            self::ADMIN => [
                // WORKSTREAMS
                CapabilityEnum::CREATE_WORKSTREAMS,
                CapabilityEnum::EDIT_WORKSTREAMS,
                CapabilityEnum::DELETE_WORKSTREAMS,
                CapabilityEnum::VIEW_WORKSTREAMS,
                CapabilityEnum::MANAGE_ALL_WORKSTREAMS,
                // BLOCKERS
                CapabilityEnum::CREATE_BLOCKERS,
                CapabilityEnum::EDIT_BLOCKERS,
                CapabilityEnum::DELETE_BLOCKERS,
                CapabilityEnum::VIEW_BLOCKERS,
                CapabilityEnum::MANAGE_ALL_BLOCKERS,
                // INVTES
                CapabilityEnum::CREATE_INVITES,
                CapabilityEnum::EDIT_INVITES,
                CapabilityEnum::DELETE_INVITES,
                CapabilityEnum::VIEW_INVITES,
                CapabilityEnum::MANAGE_ALL_INVITES,
                // MEETINGS
                CapabilityEnum::CREATE_MEETINGS,
                CapabilityEnum::EDIT_MEETINGS,
                CapabilityEnum::DELETE_MEETINGS,
                CapabilityEnum::VIEW_MEETINGS,
                CapabilityEnum::MANAGE_ALL_MEETINGS,
                // ORGANIZATION
                CapabilityEnum::MANAGER_ORGANIZATION_SETTINGS,
                CapabilityEnum::EDIT_ORGANIZATION,
                CapabilityEnum::DELETE_ORGANIZATION,
                CapabilityEnum::VIEW_ORGANIZATION,
                CapabilityEnum::MANAGER_ORGANIZATION_TEAM,
                // RISKS
                CapabilityEnum::CREATE_RISKS,
                CapabilityEnum::EDIT_RISKS,
                CapabilityEnum::DELETE_RISKS,
                CapabilityEnum::VIEW_RISKS,
                CapabilityEnum::MANAGE_ALL_RISKS,
                // TASKS
                CapabilityEnum::CREATE_TASKS,
                CapabilityEnum::EDIT_TASKS,
                CapabilityEnum::DELETE_TASKS,
                CapabilityEnum::VIEW_TASKS,
                CapabilityEnum::MANAGE_ALL_TASKS,
            ],
            self::CONTRIBUTOR => [
                // WORKSTREAMS
                CapabilityEnum::CREATE_WORKSTREAMS,
                CapabilityEnum::EDIT_WORKSTREAMS,
                CapabilityEnum::DELETE_WORKSTREAMS,
                CapabilityEnum::VIEW_WORKSTREAMS,
                // BLOCKERS
                CapabilityEnum::CREATE_BLOCKERS,
                CapabilityEnum::EDIT_BLOCKERS,
                CapabilityEnum::DELETE_BLOCKERS,
                CapabilityEnum::VIEW_BLOCKERS,
                // INVITES
                CapabilityEnum::CREATE_INVITES,
                CapabilityEnum::EDIT_INVITES,
                CapabilityEnum::DELETE_INVITES,
                CapabilityEnum::VIEW_INVITES,
                // MEETINGS
                CapabilityEnum::CREATE_MEETINGS,
                CapabilityEnum::EDIT_MEETINGS,
                CapabilityEnum::DELETE_MEETINGS,
                CapabilityEnum::VIEW_MEETINGS,
                // RISKS
                CapabilityEnum::CREATE_RISKS,
                CapabilityEnum::EDIT_RISKS,
                CapabilityEnum::DELETE_RISKS,
                CapabilityEnum::VIEW_RISKS,
                // TASKS
                CapabilityEnum::CREATE_TASKS,
                CapabilityEnum::EDIT_TASKS,
                CapabilityEnum::DELETE_TASKS,
                CapabilityEnum::VIEW_TASKS,
            ],
            self::VIEWER => [
                // WORKSTREAMS
                CapabilityEnum::VIEW_WORKSTREAMS,
                // BLOCKERS
                CapabilityEnum::VIEW_BLOCKERS,
                // MEETINGS
                CapabilityEnum::VIEW_MEETINGS,
                // ORGANIZATION
                CapabilityEnum::VIEW_ORGANIZATION,
                // RISKS
                CapabilityEnum::VIEW_RISKS,
                // TASKS
                CapabilityEnum::VIEW_TASKS,
            ]
        };
    }
}
