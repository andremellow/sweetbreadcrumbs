import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { EllipsisIcon, FilePenLine, SquareX } from 'lucide-react';

export function TableContextMenu({ id, onEdit, onDelete }: { id: number; onEdit?: (id: number) => void; onDelete?: (id: number) => void }) {
    return (
        <DropdownMenu>
            <DropdownMenuTrigger>
                <EllipsisIcon />
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
                {onEdit && (
                    <DropdownMenuItem onSelect={() => onEdit(id)}>
                        <FilePenLine className="mr-2" />
                        Edit
                    </DropdownMenuItem>
                )}
                {onDelete && (
                    <DropdownMenuItem onSelect={() => onDelete(id)}>
                        <SquareX className="mr-2" />
                        Delete
                    </DropdownMenuItem>
                )}
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
