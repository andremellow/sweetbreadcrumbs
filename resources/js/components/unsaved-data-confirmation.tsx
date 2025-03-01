import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';

export default function UnsaveDataConfirmation({
    isOpen,
    onCancel,
    onContinue,
}: {
    isOpen: boolean;
    onCancel?: () => void;
    onContinue?: () => void;
}) {
    return (
        <AlertDialog open={isOpen}>
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>You have unsaved data?</AlertDialogTitle>
                    <AlertDialogDescription>Are you sure you close? Any unsved data will be lost.</AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel onClick={() => onCancel && onCancel()}>Cancel</AlertDialogCancel>
                    <AlertDialogAction onClick={() => onContinue && onContinue()}>Continue</AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    );
}
