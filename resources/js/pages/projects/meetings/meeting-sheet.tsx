import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import {
  Sheet,
  SheetContent,
  SheetDescription,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from "@/components/ui/sheet"
import { usePage } from "@inertiajs/react"
import { SharedData } from "@/types"
import MeetingForm from "./meeting-form"

export function MeetingSheet() {

    const { organization } = usePage<SharedData>().props;

  return (
    <Sheet>
      <SheetTrigger asChild>
        <Button variant="default">Add Meeting</Button>
      </SheetTrigger>
      <SheetContent>
        <SheetHeader>
          <SheetTitle>Create a meeting</SheetTitle>
        </SheetHeader>
        <MeetingForm organizationSlug={organization.slug} />
        {/* <SheetFooter>
          <SheetClose asChild>
            <Button type="submit">Save changes</Button>
          </SheetClose>
        </SheetFooter> */}
      </SheetContent>
    </Sheet>
  )
}
