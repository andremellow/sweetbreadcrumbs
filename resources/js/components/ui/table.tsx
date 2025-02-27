import * as React from "react"

import { cn } from "@/lib/utils"
import { Link } from "@inertiajs/react"
import { ArrowDownZA, ArrowUpAZ, ArrowUpDown } from "lucide-react"

const Table = React.forwardRef<
  HTMLTableElement,
  React.HTMLAttributes<HTMLTableElement>
>(({ className, ...props }, ref) => (
  <div className="relative w-full overflow-auto">
    <table
      ref={ref}
      className={cn("w-full caption-bottom text-sm", className)}
      {...props}
    />
  </div>
))
Table.displayName = "Table"

const TableHeader = React.forwardRef<
  HTMLTableSectionElement,
  React.HTMLAttributes<HTMLTableSectionElement>
>(({ className, ...props }, ref) => (
  <thead ref={ref} className={cn("[&_tr]:border-b", className)} {...props} />
))
TableHeader.displayName = "TableHeader"

const TableBody = React.forwardRef<
  HTMLTableSectionElement,
  React.HTMLAttributes<HTMLTableSectionElement>
>(({ className, ...props }, ref) => (
  <tbody
    ref={ref}
    className={cn("[&_tr:last-child]:border-0", className)}
    {...props}
  />
))
TableBody.displayName = "TableBody"

const TableFooter = React.forwardRef<
  HTMLTableSectionElement,
  React.HTMLAttributes<HTMLTableSectionElement>
>(({ className, ...props }, ref) => (
  <tfoot
    ref={ref}
    className={cn(
      "border-t bg-muted/50 font-medium [&>tr]:last:border-b-0",
      className
    )}
    {...props}
  />
))
TableFooter.displayName = "TableFooter"

const TableRow = React.forwardRef<
  HTMLTableRowElement,
  React.HTMLAttributes<HTMLTableRowElement>
>(({ className, ...props }, ref) => (
  <tr
    ref={ref}
    className={cn(
      "border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted",
      className
    )}
    {...props}
  />
))
TableRow.displayName = "TableRow"

;
type TableHeadProps = {
  sort_by?: string;
  sorted_by?: string;
  sorted_direction?: "asc" | "desc" | undefined;
} & React.ThHTMLAttributes<HTMLTableCellElement>;

const TableHead = React.forwardRef<
  HTMLTableCellElement,
  TableHeadProps
>(({ 
    sort_by,
    sorted_by,
    sorted_direction,
    className, 
    children,
    ...props 
  }, ref) => {
  let sortedIcon = null;
    if(sorted_by && sort_by && sorted_by === sort_by) {
      sortedIcon = sorted_direction === 'desc' ?
        <Link href={setNewDirection(sorted_by, 'asc')}><ArrowUpAZ aria-hidden="true" className="size-5" /></Link> :
        <Link href={setNewDirection(sorted_by, 'desc')}><ArrowDownZA aria-hidden="true" className="size-5" /> </Link>
    } else if( sort_by ) {
      sortedIcon =  <Link href={setNewDirection( sort_by, 'asc')}>
            <ArrowUpDown aria-hidden="true" className="text-gray-400 size-4 hover:text-gray-600" />
        </Link>
    }

    return (
      <th
        ref={ref}
        className={cn(
          "h-10 px-2 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-[2px]",
          className
        )}
        {...props}
    >
      <div className="flex gap-x-2">
        {children} {sortedIcon}
      </div>
    </th>);
})
TableHead.displayName = "TableHead"

const TableCell = React.forwardRef<
  HTMLTableCellElement,
  React.TdHTMLAttributes<HTMLTableCellElement>
>(({ className, ...props }, ref) => (
  <td
    ref={ref}
    className={cn(
      "p-2 align-middle [&:has([role=checkbox])]:pr-0 [&>[role=checkbox]]:translate-y-[2px]",
      className
    )}
    {...props}
  />
))
TableCell.displayName = "TableCell"

const TableCaption = React.forwardRef<
  HTMLTableCaptionElement,
  React.HTMLAttributes<HTMLTableCaptionElement>
>(({ className, ...props }, ref) => (
  <caption
    ref={ref}
    className={cn("mt-4 text-sm text-muted-foreground", className)}
    {...props}
  />
))
TableCaption.displayName = "TableCaption"

export {
  Table,
  TableHeader,
  TableBody,
  TableFooter,
  TableHead,
  TableRow,
  TableCell,
  TableCaption,
}


const setNewDirection = (sortBy: string, direction: string): string => {
  // Create a URL object from the URL string

  const url = new URL(window.location.href);

  // Access the query parameters via URLSearchParams
  const params = url.searchParams;

  // Update a value:
  params.set('page', '1');
  params.set('sort_by', sortBy);
  params.set('sort_direction', direction);

  url.search = params.toString();

  // Generate the new query string:
  return url.toString();
}