import { useForm, usePage } from '@inertiajs/react';
import { FormEventHandler } from 'react';

import { DatePickerGroup } from '@/components/input-group/datepicker-group';
import Form from '@/components/input-group/form';
import { InputGroup } from '@/components/input-group/input-group';
import { PriorityDropdownGroup } from '@/components/input-group/priority-dropdown-group';
import { ReleaseDropdownGroup } from '@/components/input-group/release-dropdown-group';
import { TextareaGroup } from '@/components/input-group/textarea-group';
import { Button } from '@/components/ui/button';
import { PageProjectForm } from '@/types';
import { format } from 'date-fns';

export default function ProjectForm({ organizationSlug }: { organizationSlug: string }) {
    const { project } = usePage<PageProjectForm>().props;

    const { data, setData, post, patch, errors, processing } = useForm({
        name: project?.name || '',
        priority_id: project?.priority_id ?? '',
        release_plan: project?.release_plan || '',
        technical_documentation: project?.technical_documentation || '',
        needs_to_start_by: project?.needs_to_start_by || '',
        needs_to_deployed_by: project?.needs_to_deployed_by || '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        if (project?.id) {
            patch(route('projects.update', { organization: organizationSlug, project: project.id }));
        } else {
            post(route('projects.store', { organization: organizationSlug }));
        }
    };

    const renderButtons = () => <Button disabled={processing}>Create</Button>;

    const onSetDate = (field: 'needs_to_start_by' | 'needs_to_deployed_by', date: Date | undefined) => {
        setData(field, date ? format(date, 'y/M/d') : '');
    };

    const getDate = (date: Date | string) => {
        if (date instanceof Date) {
            return date;
        } else if (date === '') {
            return undefined;
        }

        return format(date, 'y/M/d');
    };

    return (
        <Form onSubmit={submit} buttons={renderButtons()}>
            <InputGroup
                label="Name"
                name="name"
                value={data.name}
                autoComplete="name"
                placeholder="Name"
                error={errors.name}
                onChange={(e) => setData('name', e.target.value)}
            />

            <PriorityDropdownGroup
                value={data.priority_id.toString()}
                error={errors.priority_id}
                onChange={(value) => setData('priority_id', value)}
            />

            <TextareaGroup
                label="Release plan"
                name="release_plan"
                value={data.release_plan}
                autoComplete="release_plan"
                placeholder="Release plan"
                error={errors.release_plan}
                onChange={(e) => setData('release_plan', e.target.value)}
            />

            <TextareaGroup
                label="Technical documentation"
                name="technical_documentation"
                value={data.technical_documentation}
                autoComplete="technical_documentation"
                placeholder="Technical documentation"
                error={errors.technical_documentation}
                onChange={(e) => setData('technical_documentation', e.target.value)}
            />

            <DatePickerGroup
                name="needs_to_start_by"
                label="Needs to start by"
                value={getDate(data.needs_to_start_by)}
                onChange={(value) => onSetDate('needs_to_start_by', value)}
            />

            <DatePickerGroup
                name="needs_to_deployed_by"
                label="Needs to deployed by"
                value={getDate(data.needs_to_deployed_by)}
                onChange={(value) => onSetDate('needs_to_deployed_by', value)}
            />
        </Form>
    );
}
