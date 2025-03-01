import EmptyTable from '@/components/empty-table';
import { TableContextMenu } from '@/components/table-context-menu';
import { TablePagination } from '@/components/table-pagination';
import { Card } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableFooter, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { ListMeetings } from '@/types/meeting.types';
import { usePage } from '@inertiajs/react';
import { format } from 'date-fns';
import { useState } from 'react';
import { EditMeetingSheet } from './edit-meeting-sheet';
import { MeetingFilters } from './meeting-filters';
import { MeetingSheet } from './meeting-sheet';

export function MeetingTable() {
    const {
        organization,
        project,
        meetings,
        sortable: { sorted_by, sorted_direction },
    } = usePage<ListMeetings>().props;
    const { data } = meetings;
    const [idToEdit, setIdToEdit] = useState<number | undefined>(undefined);

    const onClose = () => {
        setIdToEdit(undefined);
    };

    return (
        <>
            <MeetingFilters />
            <Card>
                {data.length === 0 ? (
                    <EmptyTable>
                        <MeetingSheet organizationSlug={organization.slug} projectId={project.id} />
                    </EmptyTable>
                ) : (
                    <Table className="w-full">
                        <TableHeader>
                            <TableRow>
                                <TableHead>ID</TableHead>
                                <TableHead sort_by="name" sorted_by={sorted_by} sorted_direction={sorted_direction}>
                                    Name
                                </TableHead>
                                <TableHead sort_by="date" sorted_by={sorted_by} sorted_direction={sorted_direction}>
                                    Date
                                </TableHead>
                                <TableHead className="text-right">Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {data.map((meeting) => (
                                <TableRow key={meeting.id}>
                                    <TableCell>{meeting.id}</TableCell>
                                    <TableCell className="font-medium">{meeting.name}</TableCell>
                                    <TableCell>{meeting.date ? format(meeting.date, 'M/d/y') : ''}</TableCell>
                                    <TableCell>
                                        <TableContextMenu id={meeting.id} onEdit={(id) => setIdToEdit(id)} />
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                        {meetings.last_page > 1 && (
                            <TableFooter>
                                <TableRow>
                                    <TableCell colSpan={4}>
                                        <TablePagination meta={meetings} />
                                    </TableCell>
                                </TableRow>
                            </TableFooter>
                        )}
                    </Table>
                )}
            </Card>
            <EditMeetingSheet meetingId={idToEdit} projectId={project.id} onClose={onClose} onEditSuccess={onClose} />
        </>
    );
}
