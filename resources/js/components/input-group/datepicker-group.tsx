import { Label } from "@/components/ui/label"
import InputError from '@/components/input-error';
import { DatePicker } from "../date-picker";
 
export function DatePickerGroup({
    name,
    label,
    error,
    value,
    onChange
}:{
    name: string,
    label: string,
    error?: string,
    value: Date | undefined
    onChange?: (date: Date | undefined) => void
}) {
  return (
    <div className="grid w-full max-w-sm items-center gap-1.5">
      <Label htmlFor={name}>{label}</Label>
      <DatePicker 
        value={value}
        onChange={onChange}
      />
      <InputError className="mt-2" message={error} />
    </div>
  )
}