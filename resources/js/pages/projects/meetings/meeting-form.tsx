import {  useForm } from '@inertiajs/react';
import { FormEventHandler, useEffect } from 'react';
import { Button } from '@/components/ui/button';
import { InputGroup } from '@/components/input-group/input-group';
import Form from '@/components/input-group/form';
import { TextareaGroup } from '@/components/input-group/textarea-group';
import { DatePickerGroup } from '@/components/input-group/datepicker-group';
import { Meeting } from '@/types/meeting.types';

export default function MeetingForm({ 
        organizationSlug,
        projectId,
        meeting,
        onEditSuccess,
        onIsDirtyChange
    } : {
        organizationSlug: string,
        projectId: number,
        meeting?: Meeting,
        onEditSuccess?: () => void
        onIsDirtyChange?: (isDirty: boolean) => void
    }) {


    const { data, setData, post, patch, errors, processing, isDirty } =
        useForm({
            name: meeting?.name || '',
            description: meeting?.description || '',
            date: meeting?.date,
            
        });

    useEffect(() => {
        if(onIsDirtyChange) {
            onIsDirtyChange(isDirty);
        }
    }, [isDirty, onIsDirtyChange])

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        if(meeting?.id) {
            const urlParams = new URLSearchParams(window.location.search);
            const paramsObject = Object.fromEntries(urlParams.entries());
            patch(route('projects.meetings.update', { organization: organizationSlug, project: projectId, meeting: meeting.id, redirect_parameters: paramsObject }), {
                onSuccess: () => onEditSuccess && onEditSuccess()
            });

        } else {
            post(route('projects.meetings.store', { organization: organizationSlug, project: projectId }));
        }

    };

    const renderButtons = () => (
        <Button disabled={processing}>Save</Button>
    )

    return (
        <Form onSubmit={submit} buttons={renderButtons()} isDirty={isDirty}>
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
