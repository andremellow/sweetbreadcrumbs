import Heading from '@/components/heading';
import AppLayout from '@/layouts/app-layout';
import PageLayout from '@/layouts/page-layout';
import { type BreadcrumbItem } from '@/types';
import { ProjectFilters } from './project-filters';
import { ProjectTable } from './project-table';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

export default function ProjectList() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <PageLayout>
                <Heading title="Create a new Project" description="A project is anything that you need to control" />
                <div className="space-y-6">
                    <ProjectFilters />
                    <ProjectTable />
                </div>
            </PageLayout>
        </AppLayout>
    );
}
