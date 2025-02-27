import {  useForm, usePage } from '@inertiajs/react';
import { FormEventHandler } from 'react';

import { Button } from '@/components/ui/button';
import { PageProjectMeetingForm } from '@/types';
import { InputGroup } from '@/components/input-group/input-group';
import Form from '@/components/input-group/form';
import { TextareaGroup } from '@/components/input-group/textarea-group';
import { DatePickerGroup } from '@/components/input-group/datepicker-group';

export default function MeetingForm({ organizationSlug } : {organizationSlug: string}) {
    const {
        project,
        meeting
    } = usePage<PageProjectMeetingForm>().props;


    const { data, setData, post, patch, errors, processing } =
        useForm({
            name: meeting?.name || '',
            description: meeting?.description || '',
            date: meeting?.date,
            
        });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        if(meeting?.id) {
            patch(route('projects.meetings.update', { organization: organizationSlug, project: project.id, meeting: meeting.id }));
        } else {
            post(route('projects.meetings.store', { organization: organizationSlug, project: project.id }));
        }

    };

    const renderButtons = () => (
        <Button disabled={processing}>Create</Button>
    )

    return (
        <Form onSubmit={submit} buttons={renderButtons()}>
            <InputGroup 
                label='Name' 
                name='name'
                value={data.name}
                autoComplete="name"
                placeholder="Name"
                error={errors.name}
                onChange={(e) => setData('name', e.target.value)}
            />

            <TextareaGroup 
                label='Description' 
                name='description'
                value={data.description}
                autoComplete="description"
                placeholder="Description"
                error={errors.description}
                onChange={(e) => setData('description', e.target.value)}
            />

            <DatePickerGroup
                name='date'
                label='Date'
                value={data.date}
                onChange={value => setData('date', value)}
             />

        </Form>

    );
}
