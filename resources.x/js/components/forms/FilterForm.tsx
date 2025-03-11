import * as React from 'react';

import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { Filter, FilterX } from 'lucide-react';

interface FilterFormProps extends React.ComponentProps<'form'> {
    isDirty?: boolean;
    onReset?: () => void;
}

const FilterForm = React.forwardRef<HTMLFormElement, FilterFormProps>(({ onReset, isDirty = false, children, className, ...props }, ref) => {
    return (
        <form className={cn('grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6', className)} ref={ref} {...props}>
            {children}
            <div className="flex h-full items-end justify-start gap-x-2 sm:col-span-1 sm:justify-end">
                <Button size="sm">
                    <Filter />
                    Filter
                </Button>
                {isDirty && (
                    <Button size="sm" onClick={() => onReset && onReset()}>
                        <FilterX />
                    </Button>
                )}
            </div>
        </form>
    );
});
FilterForm.displayName = 'FilterForm';

export { FilterForm };

interface FilterColumnProps extends React.ComponentProps<'div'> {
    span?: 1 | 2 | 3 | 4 | 5;
}

const FilterColumn = React.forwardRef<HTMLFormElement, FilterColumnProps>(({ span = 3, className, ...props }, ref) => {
    const colSpanClasses: Record<number, string> = {
        1: 'sm:col-span-1',
        2: 'sm:col-span-2',
        3: 'sm:col-span-3',
        4: 'sm:col-span-4',
        5: 'sm:col-span-5',
    };
    return <div className={cn(colSpanClasses[span], className)} ref={ref} {...props} />;
});
FilterColumn.displayName = 'FilterColumn';

export { FilterColumn };
