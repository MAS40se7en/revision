import ApplicationController from "@/actions/App/Http/Controllers/ApplicationController";
import Heading from "@/components/heading";
import StatusBadge from "@/components/status-badge";
import { Button } from "@/components/ui/button";
import { Application } from "@/types";
import { Head, Link, router } from "@inertiajs/react";

export default function ShowApplication({
    application,
}: {
    application: Application;
}) {
    function handleDelete() {
        if (window.confirm("Delete this application?")) {
            router.delete(ApplicationController.destroy.url(application.id));
        }
    }

    return (
        <>
            <Head title={application.company} />

            <div className="max-w-2xl space-y-6 p-4">
                <div className="flex items-start justify-between gap-4">
                    <Heading
                        title={application.company}
                        description={application.role}
                    />
                    <StatusBadge status={application.status} />
                </div>

                <dl className="grid gap-4 text-sm">
                    <div>
                        <dt className="text-muted-foreground">Date applied</dt>
                        <dd>{application.applied_at?.slice(0, 10) ?? "--"}</dd>
                    </div>
                    {application.url && (
                        <div>
                            <dt className="text-muted-foreground">
                                Job posting
                            </dt>
                            <dd>
                                <a
                                    href={application.url}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="underline underline-offset-4"
                                >
                                    {application.url}
                                </a>
                            </dd>
                        </div>
                    )}
                    {application.notes && (
                        <div>
                            <dt className="text-muted-foreground">Notes</dt>
                            <dd className="whitespace-pre-wrap">
                                {application.notes}
                            </dd>
                        </div>
                    )}
                </dl>

                <div className="space-y-2">
                    <h2 className="text-sm font-medium">Interviews</h2>
                    {application.interviews?.length ? (
                        <ul className="space-y-2">
                            {application.interviews.map((interview) => (
                                <li
                                    key={interview.id}
                                    className="rounded-lg border p-3 text-sm"
                                >
                                    <span className="font-medium capitalize">
                                        {interview.type}
                                    </span>{" "}
                                    ·{" "}
                                    {new Date(
                                        interview.scheduled_at,
                                    ).toLocaleString()}
                                    {interview.notes && (
                                        <p className="mt-1 text-muted-foreground">
                                            {interview.notes}
                                        </p>
                                    )}
                                </li>
                            ))}
                        </ul>
                    ) : (
                        <p className="text-sm text-muted-foreground">
                            No interview yet.
                        </p>
                    )}
                </div>

                <div className="flex gap-2">
                    <Button asChild>
                        <Link href={ApplicationController.edit(application.id)}>
                            Edit
                        </Link>
                    </Button>
                    <Button variant="destructive" onClick={handleDelete}>
                        Delete
                    </Button>
                </div>
            </div>
        </>
    );
}

ShowApplication.layout = {
    breadcrumbs: [
        {
            title: 'Applications',
            href: ApplicationController.index()
        }
    ]
}