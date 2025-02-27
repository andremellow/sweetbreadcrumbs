import { Label } from "@/components/ui/label"
import InputError from '@/components/input-error';
import { Textarea } from "../ui/textarea";
 
export function TextareaGroup({
    label,
    error,
    ...rest
}:{
    name: string,
    label: string,    error?: string,
    value: string | number | readonly string[]
    // onChange?: (value: string) => void
} & React.InputHTMLAttributes<HTMLInputElement>) {
  return (
    <div className="grid w-full max-w-sm items-center gap-1.5">
      <Label htmlFor={rest.name}>{label}</Label>
      <Textarea 
        // onChange={(e) => onChange && onChange(e.target.value)}
        {...rest}
      />
      <InputError className="mt-2" message={error} />
    </div>
  )
}