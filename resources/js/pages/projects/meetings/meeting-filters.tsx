import { FilterColumn, FilterForm } from "@/components/forms/FilterForm";
import { InputGroup } from "@/components/input-group/input-group";
import { PageProjectMeetings } from "@/types";
import { useForm, usePage } from "@inertiajs/react";
import { FormEventHandler } from "react";
  export function MeetingFilters() {

    const {         
      filters,
      organization,
      project 
    } = usePage<PageProjectMeetings>().props;
    

    const { data, setData, get, errors } =
        useForm({
            name: filters?.name || ''
        });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        get(route('projects.meetings', { organization: organization.slug, project: project.id }));
    };

    return (
        
        <FilterForm onSubmit={submit}>
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
    )
  }
  

  