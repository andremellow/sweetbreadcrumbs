import { TablePagination } from "@/components/table-pagination";
import { Card } from "@/components/ui/card";
import {
    Table,
    TableBody,
    TableCell,
    TableFooter,
    TableHead,
    TableHeader,
    TableRow,
  } from "@/components/ui/table"
import { PageProjectMeetings } from "@/types";
import { usePage } from "@inertiajs/react";
import { format } from "date-fns";
import { MeetingFilters } from "./meeting-filters";
import { Button } from "@/components/ui/button";
import { MeetingSheet } from "./meeting-sheet";

  
  
  export function MeetingTable() {

    const {         
      meetings,
      sortable: {
          sorted_by,
          sorted_direction
      },
    } = usePage<PageProjectMeetings>().props;
    const { data } = meetings;

    if(data.length === 0) {
      return (
        <div className="flex justify-center items-center flex-col min-h-1/3 gap-y-5">
          <div>There are no meetings for this project</div>
          <MeetingSheet />
        </div>
      );
    }

    return (
        <>
        <MeetingFilters />
        <Card>
        <Table className="w-full" >
          <TableHeader>
            <TableRow>
              <TableHead >ID</TableHead>
              <TableHead sort_by='name' sorted_by={sorted_by} sorted_direction={sorted_direction}>Name</TableHead>
              <TableHead sort_by='date' sorted_by={sorted_by} sorted_direction={sorted_direction}>Date</TableHead>
              <TableHead className="text-right">Action</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {data.map((meeting) => (
              <TableRow key={meeting.id}>
                <TableCell >{meeting.id}</TableCell>
                <TableCell className="font-medium">{meeting.name}</TableCell>
                <TableCell >{meeting.date ? format(meeting.date, 'M/d/y') : ''}</TableCell>
                <TableCell className="text-right"></TableCell>
              </TableRow>
            ))}
          </TableBody>
          {meetings.last_page > 1 &&
          <TableFooter>
            <TableRow>
              <TableCell colSpan={4}>
                <TablePagination meta={meetings} />
              </TableCell>
            </TableRow>
          </TableFooter>
          }
        </Table>
      </Card>
      </>
    )
  }
  