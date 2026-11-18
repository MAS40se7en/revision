<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInterviewRequest;
use App\Models\Application;
use App\Models\Interview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class InterviewController extends Controller
{
    /**
     * Store a newly created interview for the application.
     */
    public function store(StoreInterviewRequest $request, Application $application): RedirectResponse
    {
        $application->interviews()->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Interview Added')]);

        return to_route('applications.show', $application);
    }

    /**
     * Delete the interview.
     */
    public function destroy(Interview $interview): RedirectResponse
    {
        Gate::authorize('delete', $interview);

        $application = $interview->application;

        $interview->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Interview deleted.')]);

        return to_route('applications.show', $application);
    }
}
