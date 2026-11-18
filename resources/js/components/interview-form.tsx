import InterviewController from '@/actions/App/Http/Controllers/InterviewController';
import { Form } from '@inertiajs/react';
import { Label } from './ui/label';
import { Input } from './ui/input';
import InputError from './input-error';
import { Button } from './ui/button';

export default function InterviewForm({
    applicationId,
}: {
    applicationId: number;
}) {
    return (
        <Form
            {...InterviewController.store.form(applicationId)}
            options={{ preserveScroll: true }}
            resetOnSuccess
            className="space-y-4 rounded-lg border p-4"
        >
            {({ processing, errors }) => (
                <>
                    <div className="grid gap-2">
                        <Label htmlFor="scheduled_at">Date and time</Label>
                        <Input
                            id="scheduled_at"
                            name="scheduled_at"
                            type="datetime-local"
                            required
                        />
                        <InputError message={errors.scheduled_at} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="type">Type</Label>
                        <Input
                            id="type"
                            name="type"
                            required
                            placeholder="Phone screen, technical, onsite"
                        />
                        <InputError message={errors.type} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="interview-notes">Notes</Label>
                        <Input
                            id="interview-notes"
                            name="notes"
                            placeholder="Optional"
                        />
                        <InputError message={errors.notes} />
                    </div>

                    <Button disabled={processing}>Add interview</Button>
                </>
            )}
        </Form>
    );
}
