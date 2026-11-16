import ApplicationController from "@/actions/App/Http/Controllers/ApplicationController"
import ApplicationForm from "@/components/application-form"
import Heading from "@/components/heading"
import { Head } from "@inertiajs/react"
import { Heart } from "lucide-react"

export default function CreateApplication({
    statuses
}: {
    statuses: string[]
}) {
    return (
        <>
            <Head title="New application" />

            <div className="max-w-2xl space-y-6 p-4">
                <Heading 
                    title="New Application"
                    description="Add a role you're applying for"
                />

                <ApplicationForm 
                    formProps={ApplicationController.store.form()}
                    statuses={statuses}
                    submitLabel="Create application"
                />
            </div>
        </>
    )
}

CreateApplication.layout = {
    breadcrumbs: [
        {
            title: 'Applications',
            href: ApplicationController.index()
        },
        {
            title: 'New application',
            href: ApplicationController.create()
        }
    ]
}