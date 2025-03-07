import InputError from '@/components/input-error';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export function InputGroup({
    label,
    error,
    ...rest
}: {
    name: string;
    label: string;
    error?: string;
    value: string | number | readonly string[];
    // onChange?: (value: string) => void
} & React.InputHTMLAttributes<HTMLInputElement>) {
    return (
        <div className="grid w-full max-w-sm items-center gap-1.5">
            <Label htmlFor={rest.name}>{label}</Label>
            <Input {...rest} />

            <InputError className="mt-2" message={error} />
        </div>
    );
}
