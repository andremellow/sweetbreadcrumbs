import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import ProjectLayout from '@/layouts/project-layout';
import { ProjectDashboard, type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import { MeetingTable } from './meetings/meeting-table';
import { MeetingFilters } from './meetings/meeting-filters';



export default function Meetings() {

    const { organization, project } = usePage<ProjectDashboard>().props

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: '/dashboard',
        },
        {
            title: project.name,
            href: route('projects.dashboard', { organization: organization.slug, project: project.id }),
        },
        {
            title: 'Meetings',
            href: route('projects.meetings', { organization: organization.slug, project: project.id }),
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
        <ProjectLayout>
            <MeetingTable />
        </ProjectLayout>
    </AppLayout>
    );
}

