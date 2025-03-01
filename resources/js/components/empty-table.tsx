import { Card } from '@/components/ui/card';
import { ReactNode } from 'react';

export default function EmptyTable({ children }: { children?: ReactNode }) {
    return (
        <Card className="p-10">
            <div className="flex min-h-1/3 flex-col items-center justify-center gap-y-5">
                <div>There are no data for your search criteria</div>
                {children}
            </div>
        </Card>
    );
}
