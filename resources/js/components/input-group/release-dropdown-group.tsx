import { PageWithReleases, SharedData } from "@/types";
import { router, usePage } from "@inertiajs/react";
import { SelectGroup } from "./select-group";

export function ReleaseDropdownGroup({
    name = 'release',
    label = 'Release',
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

    const { releases } = usePage<SharedData>().props

  return (
    <SelectGroup
        onChange={onChange}
        error={error}
        placeholder={placeholder}
        name={name}
        label={label}
        value={value}
        options={releases!}  
    />
        
  )
}