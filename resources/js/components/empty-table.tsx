import { Card } from "@/components/ui/card";
import { ReactNode } from "react";

export default function EmptyTable({ children }: { children? : ReactNode }) {
    return (
        <Card className="p-10">
            <div className="flex justify-center items-center flex-col min-h-1/3 gap-y-5">
                <div>There are no data for your search criteria</div>
                {children}
            </div>
      </Card>
    );
}
