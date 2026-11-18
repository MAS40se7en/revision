<?php

use App\Models\Application;
use App\Models\Interview;
use App\Models\User;

test('guests cannot add interviews', function () {
    $application = Application::factory()->create();

    $this->post(route('applications.interviews.store', $application), [])->assertRedirect(route('login'));
});

test('users can add an interview to their own application', function () {
    $user = User::factory()->create();
    $application = Application::factory()->for($user)->create();

    $this->actingAs($user)->post(route('applications.interviews.store', $application), [
        'scheduled_at' => '2026-12-01 10:00:00',
        'type' => 'technical',
        'notes' => 'Bring a laptop',
    ]);

    $interview = $application->interviews()->firstOrFail();

    expect($interview->type)->toBe('technical')->and($interview->scheduled_at->toDateString())->toBe('2026-12-01')->and($interview->notes)->toBe('Bring a laptop');
});

test('adding an interview validates its input', function (array $payload, string $field) {
    $user = User::factory()->create();
    $application = Application::factory()->for($user)->create();

    $this->actingAs($user)->post(route('applications.interviews.store', $application), $payload)->assertSessionHasErrors($field);
})->with([
    'missing date' => [['type' => 'technical'], 'scheduled_at'],
    'invalid date' => [['scheduled_at' => 'tomorrow-ish', 'type' => 'technical'], 'scheduled_at'],
    'missing_type' => [['scheduled_at' => '2026-12-01 10:00:00'], 'type'],
]);

test("users cannot add interviews to another user\'s application", function () {
    $application = Application::factory()->create();

    $this->actingAs(User::factory()->create())->post(route('applications.interviews.store', $application), [
        'scheduled_at' => '2026-12-01 10:00:00',
        'type' => 'technical',
    ])->assertForbidden();

    expect($application->interviews()->count())->toBe(0);
});

test('users can delete an interview on their own application', function () {
    $user = User::factory()->create();
    $application = Application::factory()->for($user)->create();
    $interview = Interview::factory()->for($application)->create();

    $this->actingAs($user)->delete(route('interviews.destroy', $interview))->assertRedirect(route('applications.show', $application));

    $this->assertModelMissing($interview);
});

test("users cannot delete an interview on another user\'s application", function () {
    $application = Application::factory()->create();
    $interview = Interview::factory()->for($application)->create();

    $this->actingAs(User::factory()->create())->delete(route('interviews.destroy', $interview))->assertForbidden();

    $this->assertModelExists($interview);
});

test('deleting an application deletes its interviews', function () {
    $user = User::factory()->create();
    $application = Application::factory()->for($user)->create();
    $interview = Interview::factory()->for($application)->create();

    $this->actingAs($user)->delete(route('applications.destroy', $application));

    $this->assertModelMissing($interview);
});
