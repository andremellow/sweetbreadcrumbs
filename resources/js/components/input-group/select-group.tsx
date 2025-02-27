import { Label } from "@/components/ui/label"
import InputError from '@/components/input-error';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
    SelectSeparator
  } from "@/components/ui/select"
import { SelectOption } from "@/types";

export function SelectGroup({
    options,
    label,
    error,
    value,
    placeholder = "Select an option",
    onChange,
    ...rest
}:{
    name: string,
    label: string,
    error?: string,
    placeholder?: string,
    value: string | undefined
    options: SelectOption
    onChange?: (value: string) => void
} ) {
  const handleChange = (val: string) => {
    onChange && onChange(val === 'clear' ? '' : val);
    
  };
  return (
    <div className="grid w-full max-w-sm items-center gap-1.5">
      <Label htmlFor={rest.name}>{label}</Label>
      <Select onValueChange={handleChange} value={value}>
        <SelectTrigger >
            <SelectValue placeholder={placeholder} />
        </SelectTrigger>
        <SelectContent>
        <SelectItem value="clear">❌ Clear Selection</SelectItem> {/* Fake "None" option */}
          <SelectSeparator className="h-px bg-gray-300" />
            {Object.entries(options).map(([id, name]) => (
                <SelectItem value={id}>{name}</SelectItem>
            ))}
        </SelectContent>
        </Select>
      <InputError className="mt-2" message={error} />
    </div>
  )
}