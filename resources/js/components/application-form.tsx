import { Application } from "@/types";
import { Form } from "@inertiajs/react"
import { ComponentProps } from "react"
import { Label } from "./ui/label";
import { Input } from "./ui/input";
import InputError from "./input-error";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "./ui/select";
import { Button } from "./ui/button";

type Props = {
    formProps: Pick<ComponentProps<typeof Form>, 'action' | 'method'>;
    statuses: string[];
    application?: Application;
    submitLabel: string;
};

export default function ApplicationForm({
    formProps,
    statuses,
    application,
    submitLabel
}: Props) {
    return (
        <Form {...formProps} className="space-y-6">
            {({ processing, errors }) => (
                <>
                    <div className="grid gap-2">
                        <Label htmlFor="company">Company</Label>
                        <Input 
                            id="company"
                            name="company"
                            defaultValue={application?.company}
                            required
                            placeholder="Company Name"
                        />
                        <InputError message={errors.company} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="role">Role</Label>
                        <Input 
                            id="role"
                            name="role"
                            defaultValue={application?.role}
                            required
                            placeholder="Job Title"
                        />
                        <InputError message={errors.role} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="status">Status</Label>
                        <Select
                            name="status"
                            defaultValue={application?.status ?? 'wishlist'}
                        >
                            <SelectTrigger id="status">
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
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
                        <InputError message={errors.status} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="applied_at">Date applied</Label>
                        <Input 
                            id="applied_at"
                            name="applied_at"
                            type="date"
                            defaultValue={application?.applied_at?.slice(0, 10)}
                        />
                        <InputError message={errors.applied_at} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="url">Job posting URL</Label>
                        <Input 
                            id="url"
                            name="url"
                            type="url"
                            defaultValue={application?.url ?? ''}
                            placeholder="https://"
                        />
                        <InputError message={errors.url} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="notes">Notes</Label>
                        <textarea 
                            name="notes" 
                            id="notes"
                            defaultValue={application?.notes ?? ''}
                            className="flex min-h-24 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:opacity-50"
                        />
                        <InputError message={errors.notes} />
                    </div>

                    <Button disabled={processing}>
                        {submitLabel}
                    </Button>
                </>
            )}
        </Form>
    )
}