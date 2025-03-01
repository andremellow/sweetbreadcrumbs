import Heading from '@/components/heading';
import AppLayout from '@/layouts/app-layout';
import PageLayout from '@/layouts/page-layout';
import { SharedData, type BreadcrumbItem } from '@/types';
import { usePage } from '@inertiajs/react';
import ProjectForm from './project-form';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

export default function CreateProject() {
    const { organization } = usePage<SharedData>().props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <PageLayout>
                <Heading title="Create a new Project" description="A project is anything that you need to control" />
                <div className="space-y-6">
                    <ProjectForm organizationSlug={organization.slug} />
                </div>
            </PageLayout>
        </AppLayout>
    );
}
