import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import PageLayout from '@/layouts/page-layout';
import Heading from '@/components/heading';
import { ProjectTable } from './project-table';
import { ProjectFilters } from './project-filters';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

export default function ProjectList() {

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <PageLayout >
                    <Heading title="Create a new Project" description="A project is anything that you need to control" />
                    <div className="space-y-6">
                        <ProjectFilters />
                        <ProjectTable  />
                    </div>
            </PageLayout>
        </AppLayout>
    );
}
