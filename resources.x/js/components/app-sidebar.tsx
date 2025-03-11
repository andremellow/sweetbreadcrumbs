import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { LayoutGrid, List, PlusSquare } from 'lucide-react';
import AppLogo from './app-logo';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        url: '/dashboard',
        icon: LayoutGrid,
    },
];

export function AppSidebar() {
    const { organization, featuredProjects } = usePage<SharedData>().props;

    featuredProjects.forEach((project) => {
        mainNavItems.push({
            key: `mainNavItems_${project.id}`,
            title: project.name,
            url: route('projects.dashboard', { organization: organization.slug, project: project.id }),
        });
    });

    const footerNavItems: NavItem[] = [
        {
            title: 'Manage Projects',
            url: route('projects.index', { organization: organization.slug }),
            icon: List,
        },
        {
            title: 'Add project',
            url: route('projects.create', { organization: organization.slug }),
            icon: PlusSquare,
        },
    ];

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/dashboard" prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
