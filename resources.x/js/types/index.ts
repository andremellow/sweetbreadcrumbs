import { LucideIcon } from 'lucide-react';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    key?: string;
    title: string;
    url: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface ShortEntity {
    id: number;
    name: string;
}
export interface SelectOption {
    [key: number]: string;
}

export interface OrganizationShort {
    name: string;
    slug: string;
}

export type ProjectShort = ShortEntity;
export type PrioritiesShort = ShortEntity;
export type ReleaseShort = ShortEntity;

export interface ProjectDashboard extends SharedData {
    project: Project;
}

export interface Project {
    id: number;
    name: string;
    priority_id: number;
    release_plan?: string;
    technical_documentation?: string;
    needs_to_start_by?: Date;
    needs_to_deployed_by?: Date;
    created_at?: Date;
    updated_at?: Date;
    priority?: PrioritiesShort;
}

export interface SharedData extends PageProps {
    name: string;
    auth: Auth;
    organization: OrganizationShort;
    featuredProjects: ProjectShort[];
    priorities?: SelectOption;
    releases?: SelectOption;
    flash?: {
        success?: string;
        error?: string;
    };
}

export interface User {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface PageWithPriorities extends PageProps {
    priorities: SelectOption;
}

export interface PageWithReleases extends PageProps {
    releases: SelectOption;
}

export interface PageProjectForm extends PageProps {
    project?: Project;
    priorities: PrioritiesShort[];
    releases: ReleaseShort[];
}

export interface PageListProject extends SharedData {
    projects: PaginatedData<Project>;
    sortable: Sortable;
    filters?: {
        name?: string;
        priority_id?: number;
    };
}

export interface Sortable {
    sorted_by?: string;
    sorted_direction?: 'asc' | 'desc';
}

export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface PaginatedData<T> extends Pagination {
    data: T[];
    
}

export interface Pagination {
    current_page: number;
    from: number | null;
    last_page: number;
    links: PaginationLink[];
    path: string;
    per_page: number;
    to: number | null;
    total: number;
    first_page_url: string;
    last_page_url: string;
    next_page_url: string | null;
    prev_page_url: string | null;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {};
