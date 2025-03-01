import { EditSheet } from '@/components/edit-sheet';
import { SharedData } from '@/types';
import { EditMeeting, Meeting } from '@/types/meeting.types';
import { useForm, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import MeetingForm from './meeting-form';

export function EditMeetingSheet({
    meetingId,
    projectId,
    onEditSuccess,
    onClose,
}: {
    meetingId?: number;
    projectId: number;
    onEditSuccess?: () => void;
    onClose?: () => void;
}) {
    usePage();
    const { get } = useForm();
    const [title, setTitle] = useState('');
    const { organization } = usePage<SharedData>().props;
    const [open, setOpen] = useState(false);
    const [isDirty, setIsDirty] = useState(false);
    const [meeting, setMeeting] = useState<Meeting | undefined>(undefined);

    useEffect(() => {
        if (meetingId) {
            get(route(`projects.meetings.edit`, { organization: organization.slug, project: projectId, meeting: meetingId }), {
                preserveState: true,
                preserveUrl: true,
                only: ['meeting'],
                onSuccess: (page) => {
                    const props = page.props as unknown as EditMeeting;
                    if (!props.meeting) {
                        console.error('Error: meeting data is missing from response', page.props);
                        return;
                    }
                    setTitle(`Editing meeting - ${props.meeting.name}`);
                    setMeeting(props.meeting);
                    setOpen(true);
                },
            });
        } else {
            setOpen(false);
        }
    }, [meetingId, get, organization.slug, projectId]);

    return (
        <EditSheet title={title} isDirty={isDirty} onClose={onClose} isOpen={open}>
            <MeetingForm
                organizationSlug={organization.slug}
                projectId={projectId}
                meeting={meeting}
                onIsDirtyChange={(_isDirty) => setIsDirty(_isDirty)}
                onEditSuccess={onEditSuccess}
            />
        </EditSheet>
    );
}
