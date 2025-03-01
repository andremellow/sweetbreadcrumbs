import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationLink,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';
import { Pagination as PaginationType } from '@/types';

export function TablePagination({ meta }: { meta: PaginationType }) {
    if (!meta || meta.total <= meta.per_page) return null;

    const links = meta.links.slice(1, meta.links.length - 1);

    return (
        <Pagination>
            <PaginationContent>
                <PaginationItem>
                    <PaginationPrevious href={meta.prev_page_url} />
                </PaginationItem>
                {links.map((link) =>
                    link.url ? (
                        <PaginationLink key={link.label} href={link.url} isActive={link.active}>
                            {link.label}
                        </PaginationLink>
                    ) : (
                        <PaginationEllipsis key={'ellipsis'} />
                    ),
                )}
                <PaginationItem>
                    <PaginationNext href={meta.next_page_url} />
                </PaginationItem>
            </PaginationContent>
        </Pagination>
    );
}
