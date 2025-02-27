import { PageProps, SharedData } from "@/types";
import { usePage } from "@inertiajs/react";
import { useEffect, useState } from "react";


export default function FlashMessage() {
    const { flash } = usePage<SharedData>().props;
    const [visible, setVisible] = useState<boolean>(false);

    useEffect(() => {
        if (flash?.success || flash?.error) {
            setVisible(true);
            const timer = setTimeout(() => setVisible(false), 5000);
            return () => clearTimeout(timer);
        }
    }, [flash]);

    if (!visible || (!flash?.success && !flash?.error)) return null;

    return (

       <div>aaa</div>
    );
}
