import UnsavedDataNotification from "../unsaved-data-notification";

export default function Form({
    children,
    buttons,
    isDirty = false,
    ...rest
}:{
    buttons?: React.ReactNode
    isDirty?: boolean
} & React.FormHTMLAttributes<HTMLFormElement>){

    return (
        
        <form {...rest} className="space-y-6 mt-5">
            {children}
            {buttons && <div className="flex items-center gap-4">
                {buttons}
            </div>}
            <UnsavedDataNotification show={isDirty} />
        </form>

    );
}

