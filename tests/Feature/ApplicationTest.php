<?php

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests are redirected to the login page', function () {
    $this->get(route('applications.index'))->assertRedirect(route('login'));
});

test('users only see their own applications', function () {
    $user = User::factory()->create();
    Application::factory()->count(3)->for($user)->create();
    Application::factory()->count(2)->create();

    $this->actingAs($user)
        ->get(route('applications.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->has('applications.data', 3));
});

test('applications can be filtered by status', function () {
    $user = User::factory()->create();
    Application::factory()->for($user)->withStatus(ApplicationStatus::Offer)->create();
    Application::factory()->count(2)->for($user)->withStatus(ApplicationStatus::Rejected)->create();

    $this->actingAs($user)
        ->get(route('applications.index', ['status' => 'offer']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('applications.data', 1)
            ->where('filters.status', 'offer'));
});

test('users can create an application owned by themselves', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $response = $this->actingAs($user)->post(route('applications.store'), [
        'company' => 'Acme',
        'role' => 'Intern',
        'status' => 'applied',
        'user_id' => $otherUser->id,
    ]);

    $application = $user->applications()->firstOrFail();

    $response->assertRedirect(route('applications.show', $application));
    expect($application->company)->toBe('Acme');
    expect($otherUser->applications()->count())->toBe(0);
});

test('creating an application validates its input', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('applications.store'), [
            'status' => 'not-a-status',
            'url' => 'not-a-url',
        ])
        ->assertSessionHasErrors(['company', 'role', 'status', 'url']);
});

test('users can view their own application', function () {
    $user = User::factory()->create();
    $application = Application::factory()->for($user)->create();

    $this->actingAs($user)
        ->get(route('applications.show', $application))
        ->assertOk();
});

test('users can update their own application', function () {
    $user = User::factory()->create();
    $application = Application::factory()->for($user)->create();

    $this->actingAs($user)
        ->put(route('applications.update', $application), [
            'company' => 'New Company',
            'role' => $application->role,
            'status' => 'offer',
        ])
        ->assertRedirect(route('applications.show', $application));

    expect($application->refresh())
        ->company->toBe('New Company')
        ->status->toBe(ApplicationStatus::Offer);
});

test('users can delete their own application', function () {
    $user = User::factory()->create();
    $application = Application::factory()->for($user)->create();

    $this->actingAs($user)
        ->delete(route('applications.destroy', $application))
        ->assertRedirect(route('applications.index'));

    $this->assertModelMissing($application);
});

test('users cannot view another user\'s application', function () {
    $application = Application::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('applications.show', $application))
        ->assertForbidden();
});

test('users cannot update another user\'s application', function () {
    $application = Application::factory()->create(['company' => 'Original']);

    $this->actingAs(User::factory()->create())
        ->put(route('applications.update', $application), [
            'company' => 'Hacked',
            'role' => $application->role,
            'status' => 'offer',
        ])
        ->assertForbidden();

    expect($application->refresh()->company)->toBe('Original');
});

test('users cannot delete another user\'s application', function () {
    $application = Application::factory()->create();

    $this->actingAs(User::factory()->create())
        ->delete(route('applications.destroy', $application))
        ->assertForbidden();

    $this->assertModelExists($application);
});

test('Optional fields are saved when creating an application', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('applications.store'), [
        'company' => 'Acme',
        'role' => 'Intern',
        'status' => 'applied',
        'applied_at' => '2026-11-01',
        'url' => 'https://example.com/jobs/1',
        'notes' => 'Referred by a friend',
    ]);

    $application = $user->applications()->firstOrFail();

    expect($application->applied_at->toDateString())->toBe('2026-11-01')->and($application->url)->toBe('https://example.com/jobs/1')->and($application->notes)->toBe('Referred by a friend');
});
