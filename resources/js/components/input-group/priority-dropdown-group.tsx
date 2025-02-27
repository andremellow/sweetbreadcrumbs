import { PageWithPriorities } from "@/types";
import { usePage } from "@inertiajs/react";
import { SelectGroup } from "./select-group";

export function PriorityDropdownGroup({
    name = 'priority',
    label = 'Priority',
    error,
    placeholder,
    value,
    onChange,
}:{
    name?: string,
    label?: string,
    error?: string,
    placeholder?: string,
    value?: string
    onChange?: (value: string) => void
}) {

    const { priorities } = usePage<PageWithPriorities>().props

  return (
    <SelectGroup
        onChange={onChange}
        error={error}
        placeholder={placeholder}
        name={name}
        label={label}
        value={value}
        options={priorities}  
    />
        
  )
}