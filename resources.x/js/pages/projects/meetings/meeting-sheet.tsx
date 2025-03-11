import { Button } from '@/components/ui/button';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import MeetingForm from './meeting-form';

export function MeetingSheet({ organizationSlug, projectId }: { organizationSlug: string; projectId: number }) {
    return (
        <Sheet>
            <SheetTrigger asChild>
                <Button variant="default">Add Meeting</Button>
            </SheetTrigger>
            <SheetContent>
                <SheetHeader>
                    <SheetTitle>Create a meeting</SheetTitle>
                </SheetHeader>
                <MeetingForm organizationSlug={organizationSlug} projectId={projectId} />
            </SheetContent>
        </Sheet>
    );
}
