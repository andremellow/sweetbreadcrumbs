import UnsavedDataNotification from '../unsaved-data-notification';

export default function Form({
    children,
    buttons,
    isDirty = false,
    ...rest
}: {
    buttons?: React.ReactNode;
    isDirty?: boolean;
} & React.FormHTMLAttributes<HTMLFormElement>) {
    return (
        <form {...rest} className="mt-5 space-y-6">
            {children}
            {buttons && <div className="flex items-center gap-4">{buttons}</div>}
            <UnsavedDataNotification show={isDirty} />
        </form>
    );
}
