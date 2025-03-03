import { FilterColumn, FilterForm } from '@/components/forms/FilterForm';
import { InputGroup } from '@/components/input-group/input-group';
import { PriorityDropdownGroup } from '@/components/input-group/priority-dropdown-group';
import { PageListProject } from '@/types';
import { useForm, usePage } from '@inertiajs/react';
import { FormEventHandler } from 'react';
export function ProjectFilters() {
    const { filters, organization } = usePage<PageListProject>().props;

    const { data, setData, get, errors } = useForm({
        name: filters?.name || '',
        priority_id: filters?.priority_id || '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        get(route('projects.index', { organization: organization.slug }));
    };

    return (
        <FilterForm onSubmit={submit}>
            <FilterColumn>
                <InputGroup
                    label="Name"
                    name="name"
                    value={data.name}
                    autoComplete="name"
                    placeholder="Name"
                    error={errors.name}
                    onChange={(e) => setData('name', e.target.value)}
                />
            </FilterColumn>

            <FilterColumn span={2}>
                <PriorityDropdownGroup
                    value={data.priority_id.toString()}
                    error={errors.priority_id}
                    onChange={(value) => setData('priority_id', value)}
                />
            </FilterColumn>
        </FilterForm>
    );
}
