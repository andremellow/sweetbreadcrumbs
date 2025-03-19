<?php

namespace App\Enums;

enum EventEnum: string
{
    case TASK_CREATED = 'task-created';
    case TASK_UPDATED = 'task-updated';
    case TASK_DELETED = 'task-deleted';
    case TASK_OPENED = 'task-opened';
    case TASK_CLOSED = 'task-closed';

    case MEETING_CREATED = 'meeting-created';
    case MEETING_UPDATED = 'meeting-updated';
    case MEETING_DELETED = 'meeting-deleted';
    case LOAD_MEETING_FORM_MODAL = 'load-meeting-form-modal';

    case WORKSTREAM_CREATED = 'workstream-created';
    case WORKSTREAM_UPDATED = 'workstream-updated';
    case WORKSTREAM_DELETED = 'workstream-deleted';
    case LOAD_WORKSTREAM_FORM_MODAL = 'load-workstream-form-modal';

    case INVITE_CREATED = 'invite-created';
    case INVITE_DELETED = 'invite-deleted';

    case LOAD_MEETING_VIEW_MODAL = 'load-meeting-view-modal';

    case PROFILE_UPDATED = 'profile-updated';

    case RESET = 'reset';

}
