import InputError from '@/components/input-error';
import AppLayout from '@/layouts/app-layout';
import AuthLayoutTemplate from '@/layouts/auth/auth-card-layout';
import { type BreadcrumbItem } from '@/types';
import { Transition } from '@headlessui/react';
import { Head, useForm } from '@inertiajs/react';
import { FormEventHandler, useRef } from 'react';

import HeadingSmall from '@/components/heading-small';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Password settings',
        href: '/settings/password',
    },
];

export default function Organization() {
    const { data, setData, errors, post, processing } = useForm({
        name: '',
    });

    const updatePassword: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('welcome.organization.store'), {
            preserveScroll: true,
        });
    };

    return (
        <AuthLayoutTemplate >
                <HeadingSmall title="Organization" description="Let's create your organization" />
                <div className="space-y-6">
                    <form onSubmit={updatePassword} className="space-y-6">
                        <div className="grid gap-2">
                            <Label htmlFor="name">Name</Label>

                            <Input
                                id="name"
                                className="mt-1 block w-full"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                autoComplete="name"
                                placeholder="Name"
                            />

                            <InputError className="mt-2" message={errors.name} />
                        </div>
                        <div className="flex items-center gap-4">
                            <Button disabled={processing}>Create</Button>
                        </div>
                    </form>
                </div>
        </AuthLayoutTemplate>
    );
}
