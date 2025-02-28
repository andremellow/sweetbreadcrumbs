import { FilterColumn, FilterForm } from "@/components/forms/FilterForm";
import { InputGroup } from "@/components/input-group/input-group";
import { ListMeetings } from '@/types/meeting.types';
import { useForm, usePage } from "@inertiajs/react";
import { FormEventHandler, useCallback, useEffect } from "react";
  export function MeetingFilters() {

    const {         
      filters,
      organization,
      project 
    } = usePage<ListMeetings>().props;
    

    const { data, setData, get, errors, isDirty } =
        useForm({
            name: '',
            resetKey: ''
        });

    useEffect(() => {
      setData('name', filters?.name || '');
    }, [filters, setData])

    const _onReset = () => {
      setData({
        'name': '',
        'resetKey': 'reseted'
      });
    }

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        submitForm();
    };

    const submitForm = useCallback(() => {
      get(route("projects.meetings", { organization: organization.slug, project: project.id }));
    }, [organization.slug, project.id, get]); 

    useEffect(() => {
      if(data.resetKey === 'reseted') {
        submitForm();
      }
    }, [data.resetKey, submitForm])

    return (
        <>
        <FilterForm onSubmit={submit} isDirty={isDirty} onReset={_onReset}>
            <FilterColumn span={5}>
            <InputGroup
                  label='Name' 
                  name='name'
                  value={data.name}
                  autoComplete="name"
                  placeholder="Name"
                  error={errors.name}
                  onChange={(e) => setData('name', e.target.value)}
              />
            </FilterColumn>
        </FilterForm>
        </>
    )
  }
  

  