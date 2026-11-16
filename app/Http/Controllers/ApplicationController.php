<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Http\Requests\ApplicationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ApplicationController extends Controller
{
    /**
     * Display a paginated, optionally filtered list of the user's applications
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Application::class);

        $status = ApplicationStatus::tryFrom($request->string('status')->toString());

        $applications = $request->user()
            ->applications()
            ->withCount('interviews')
            ->when($status, fn ($query, ApplicationStatus $status) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();
        
        return Inertia::render('applications/index', [
            'applications' => $applications,
            'filters' => ['status' => $status?->value],
            'statuses' => array_column(ApplicationStatus::cases(), 'value')
        ]);
    }

    /**
     * Show the form for creating a new application
     */
    public function create(): Response
    {
        Gate::authorize('create', Application::class);

        return Inertia::render('applications/create', [
            'statuses' => array_column(ApplicationStatus::cases(), 'value')
        ]);
    }

    /**
     * Store a newly created application.
     */
    public function store(ApplicationRequest $request): RedirectResponse
    {
        Gate::authorize('create', Application::class);
        $application = $request->user()->applications()->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Application created.')]);

        return to_route('applications.show', $application);
    }

    /**
     * Display the application and its interviews.
     */
    public function show(Application $application): Response
    {
        Gate::authorize('view', $application);

        $application->load(['interviews' => fn ($query) => $query->orderBy('scheduled_at')]);

        return Inertia::render('applications/show', [
            'application' => $application
        ]);
    }

    /**
     * Show the form for editing the application.
     */
    public function edit(Application $application): Response
    {
        Gate::authorize('update', $application);

        return Inertia::render('applications/edit', [
            'application' => $application,
            'statuses' => array_column(ApplicationStatus::cases(), 'value')
        ]);
    }

    /**
     * Update the application
     */
    public function update(ApplicationRequest $request, Application $application): RedirectResponse
    {
        Gate::authorize('update', $application);

        $application->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Application updated.')]);

        return to_route('applications.show', $application);
    }

    /**
     * Delete the application.
     */
    public function destroy(Application $application): RedirectResponse
    {
        Gate::authorize('delete', $application);

        $application->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Application deleted.')]);

        return to_route('applications.index');
    }
}
