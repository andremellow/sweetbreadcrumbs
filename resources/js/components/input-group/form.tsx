export default function Form({
    children,
    buttons,
    ...rest
}:{
    buttons?: React.ReactNode
} & React.FormHTMLAttributes<HTMLFormElement>){

    return (
        
        <form {...rest} className="space-y-6">
            {children}
            {buttons && <div className="flex items-center gap-4">
                {buttons}
            </div>}
        </form>

    );
}

