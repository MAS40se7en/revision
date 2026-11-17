import ApplicationController from '@/actions/App/Http/Controllers/ApplicationController';
import Heading from '@/components/heading';
import StatusBadge from '@/components/status-badge';
import { Button } from '@/components/ui/button';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Application, Paginated } from '@/types';
import { Head, Link, router } from '@inertiajs/react';

type Props = {
    applications: Paginated<Application>;
    filters: { status: string | null };
    statuses: string[];
};

export default function ApplicationsIndex({
    applications,
    filters,
    statuses,
}: Props) {
    function filterByStatus(value: string) {
        router.get(
            ApplicationController.index.url(),
            value === 'all' ? {} : { status: value },
            { preserveState: true, replace: true },
        );
    }

    return (
        <>
            <Head title="Applications" />

            <div className="flex h-full flex-1 flex-col gap-4 p-4">
                <div className="flex items-center justify-between gap-4">
                    <Heading
                        title="Applications"
                        description="Track every role you've applied for"
                    />
                    <Button asChild>
                        <Link href={ApplicationController.create()}>
                            New Application
                        </Link>
                    </Button>
                </div>

                <div className="w-48">
                    <Select
                        value={filters.status ?? 'all'}
                        onValueChange={filterByStatus}
                    >
                        <SelectTrigger aria-label="Filter by status">
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">All Statuses</SelectItem>
                            {statuses.map((status) => (
                                <SelectItem
                                    key={status}
                                    value={status}
                                    className="capitalize"
                                >
                                    {status}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                </div>

                {applications.data.length === 0 ? (
                    <p className="text-sm text-muted-foreground">
                        No applications found
                    </p>
                ) : (
                    <div className="overflow-x-auto rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                        <table className="w-full text-left text-sm">
                            <thead className="w-full text-left text-sm">
                                <tr>
                                    <th className="p-3 font-medium">Company</th>
                                    <th className="p-3 font-medium">Role</th>
                                    <th className="p-3 font-medium">Status</th>
                                    <th className="p-3 font-medium">Applied</th>
                                    <th className="p-3 font-medium">
                                        Interviews
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {applications.data.map((application) => (
                                    <tr
                                        key={application.id}
                                        className="border-b last:border-0"
                                    >
                                        <td className="p-3">
                                            <Link
                                                href={ApplicationController.show(
                                                    application.id,
                                                )}
                                                classID="font-medium underline-offset-4 hover:underline"
                                            >
                                                {application.company}
                                            </Link>
                                        </td>
                                        <td className="p-3">
                                            {application.role}
                                        </td>
                                        <td className="p-3">
                                            <StatusBadge
                                                status={application.status}
                                            />
                                        </td>
                                        <td className="p-3">
                                            {application.applied_at?.slice(
                                                0,
                                                10,
                                            ) ?? '--'}
                                        </td>
                                        <td className="p-3">
                                            {application.interview_count ?? 0}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                )}

                <div className="flex items-center justify-between text-sm text-muted-foreground">
                    <span>
                        Page {applications.current_page} of{' '}
                        {applications.last_page} · {applications.total} total
                    </span>
                    <div className="flex gap-2">
                        {applications.prev_page_url && (
                            <Button variant="outline" asChild>
                                <Link href={applications.prev_page_url}>
                                    Previous
                                </Link>
                            </Button>
                        )}
                        {applications.next_page_url && (
                            <Button variant="outline" asChild>
                                <Link href={applications.next_page_url}>
                                    Next
                                </Link>
                            </Button>
                        )}
                    </div>
                </div>
            </div>
        </>
    );
}

ApplicationsIndex.layout = {
    Breadcrumbs: [
        {
            title: 'Applications',
            href: ApplicationController.index(),
        },
    ],
};
