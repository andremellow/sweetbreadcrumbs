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
import { PageListProject } from "@/types";
import { usePage } from "@inertiajs/react";
import { format } from "date-fns";

  
  
  export function ProjectTable() {

    const {         
      projects,
      sortable: {
          sorted_by,
          sorted_direction
      },
    } = usePage<PageListProject>().props;
    const { data } = projects;
    
    return (
        <Card>
        <Table >
          <TableHeader>
            <TableRow>
              <TableHead >ID</TableHead>
              <TableHead sort_by='name' sorted_by={sorted_by} sorted_direction={sorted_direction}>Name</TableHead>
              <TableHead sort_by='priority_id' sorted_by={sorted_by} sorted_direction={sorted_direction}>Priority</TableHead>
              <TableHead sort_by='created_at' sorted_by={sorted_by} sorted_direction={sorted_direction}>Created At</TableHead>
              <TableHead className="text-right">Action</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {data.map((project) => (
              <TableRow key={project.id}>
                <TableCell >{project.id}</TableCell>
                <TableCell className="font-medium">{project.name}</TableCell>
                <TableCell >{project.priority?.name}</TableCell>
                <TableCell >{project.created_at ? format(project.created_at, 'M/d/Y h:m:s a') : ''}</TableCell>
                <TableCell className="text-right"></TableCell>
              </TableRow>
            ))}
          </TableBody>
          {projects.last_page > 1 &&
          <TableFooter>
            <TableRow>
              <TableCell colSpan={4}>
                <TablePagination meta={projects} />
              </TableCell>
            </TableRow>
          </TableFooter>
          }
        </Table>
      </Card>
    )
  }
  