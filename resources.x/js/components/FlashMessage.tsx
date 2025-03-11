import { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import { toast } from 'sonner';

export default function FlashMessage() {
    const { flash } = usePage<SharedData>().props;

    if (flash?.success || flash?.error) {
        toast(flash?.success);
    }

    return;
}
