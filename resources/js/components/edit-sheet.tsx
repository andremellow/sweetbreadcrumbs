import {
  Sheet,
  SheetContent,
  SheetHeader,
  SheetTitle,
} from "@/components/ui/sheet"
import { ReactNode, useState } from "react"
import UnsaveDataConfirmation from "@/components/unsaved-data-confirmation"


export function EditSheet({
  title,
  children,
  isDirty = false,
  isOpen = false,
  onClose
}: {
    title: string
    children: ReactNode
    isDirty?: boolean 
    isOpen?: boolean 
    onEditSuccess?: () => void
    onClose?: () => void
}) {
    
    const [ isAlertDialogOpen, setIsAlertDialogOpen ] = useState(false);
    

    const _onOpenChange = (open: boolean) => {
      if(!open ) {
        if(isDirty) {
          setIsAlertDialogOpen(true);
        } else if (onClose) { 
          onClose();
        }
      }
    }

    const onDialogContinue = () => {
      if (onClose) { 
        onClose();
      }
      setIsAlertDialogOpen(false);
    }

    const onDialogCancel = () => {
      setIsAlertDialogOpen(false);
    }

  return (
    <>
      <UnsaveDataConfirmation isOpen={isAlertDialogOpen} onContinue={onDialogContinue} onCancel={onDialogCancel}  />
      <Sheet open={isOpen} onOpenChange={(open) => _onOpenChange(open) }>
        <SheetContent >
          <SheetHeader>
            <SheetTitle>{title}</SheetTitle>
          </SheetHeader>
            {children}
        </SheetContent>
      </Sheet>
    </>
  )
}
