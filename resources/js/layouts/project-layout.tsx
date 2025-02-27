import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { cn } from '@/lib/utils';
import { ProjectDashboard, type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/react';



export default function ProjectLayout({ children }: { children: React.ReactNode }) {
    const currentPath = window.location.pathname;

    const { organization, project } = usePage<ProjectDashboard>().props

    const sidebarNavItems: NavItem[] = [
        {
            title: 'Dashboard',
            url: route('projects.dashboard', { organization: organization.slug, project: project.id }),
            icon: null,
        },
        {
            title: 'Meetings',
            url: route('projects.meetings', { organization: organization.slug, project: project.id }),
            icon: null,
        },
    ];

    return (
        <div className="px-4 py-6">
            <Heading title={project.name} description="Manage your projects" />

            <div className="flex flex-col space-y-8 lg:flex-row lg:space-y-0 lg:space-x-12">
                <aside className="w-full max-w-xl lg:w-48">
                    <nav className="flex flex-col space-y-1 space-x-0">
                        {sidebarNavItems.map((item) => (
                            <Button
                                key={item.url}
                                size="sm"
                                variant="ghost"
                                asChild
                                className={cn('w-full justify-start', {
                                    'bg-muted': currentPath === item.url,
                                })}
                            >
                                <Link href={item.url} prefetch>
                                    {item.title}
                                </Link>
                            </Button>
                        ))}
                    </nav>
                </aside>

                <Separator className="my-6 md:hidden" />

                <div className="flex-1 ">
                    <section className="space-y-12">{children}</section>
                </div>
            </div>
        </div>
    );
}
