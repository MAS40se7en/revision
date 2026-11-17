import ApplicationController from '@/actions/App/Http/Controllers/ApplicationController';
import ApplicationForm from '@/components/application-form';
import Heading from '@/components/heading';
import { Application } from '@/types';
import { Head } from '@inertiajs/react';

type Props = {
    application: Application;
    statuses: string[];
};

export default function EditApplication({ application, statuses }: Props) {
    return (
        <>
            <Head title={`Edit ${application.company}`} />

            <div className="max-w-2xl space-y-6 p-4">
                <Heading
                    title="Edit application"
                    description={`${application.role} at ${application.company}`}
                />

                <ApplicationForm
                    formProps={ApplicationController.update.form(
                        application.id,
                    )}
                    statuses={statuses}
                    application={application}
                    submitLabel="Save changes"
                />
            </div>
        </>
    );
}

EditApplication.layout = {
    breadcrumbs: [
        {
            title: 'Applications',
            href: ApplicationController.index(),
        },
    ],
};
