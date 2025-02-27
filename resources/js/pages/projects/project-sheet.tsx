import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import {
  Sheet,
  SheetClose,
  SheetContent,
  SheetDescription,
  SheetFooter,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from "@/components/ui/sheet"
import ProjectForm from "./project-form"
import { usePage } from "@inertiajs/react"
import { SharedData } from "@/types"

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
          <SheetDescription>
          A project is anything that you need to control
          </SheetDescription>
        </SheetHeader>
        <ProjectForm organizationSlug={organization.slug} />
        {/* <SheetFooter>
          <SheetClose asChild>
            <Button type="submit">Save changes</Button>
          </SheetClose>
        </SheetFooter> */}
      </SheetContent>
    </Sheet>
  )
}
