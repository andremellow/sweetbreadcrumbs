import { SharedData, Project, PaginatedData, Sortable } from '.';

export interface ListMeetings extends SharedData {
    project: Project;
    meetings: PaginatedData<Meeting>;
    sortable: Sortable;
    filters?: {
        name?: string;
    };
}

export interface Meeting {
    id: number,
    name:  string
    description:  string
    date?: Date
    created_at?: Date
    updated_at?: Date
}


export interface EditMeeting {
    meeting: Meeting
}
