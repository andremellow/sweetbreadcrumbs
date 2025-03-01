import { Button } from '@/components/ui/button';
import { Sheet, SheetContent, SheetDescription, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import ProjectForm from './project-form';

export function ProjectSheet() {
    const { organization } = usePage<SharedData>().props;

    return (
        <Sheet>
            <SheetTrigger asChild>
                <Button variant="default">Add Project</Button>
            </SheetTrigger>
            <SheetContent>
                <SheetHeader>
                    <SheetTitle>Create a new Project</SheetTitle>
                    <SheetDescription>A project is anything that you need to control</SheetDescription>
                </SheetHeader>
                <ProjectForm organizationSlug={organization.slug} />
                {/* <SheetFooter>
          <SheetClose asChild>
            <Button type="submit">Save changes</Button>
          </SheetClose>
        </SheetFooter> */}
            </SheetContent>
        </Sheet>
    );
}
