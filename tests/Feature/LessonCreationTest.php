<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

it('lets a tutor create a lesson with a description', function () {
    $tutor = User::factory()->create([
        'role' => 'tutor',
    ]);

    $payload = [
        'title' => 'Introduction to Databases',
        'course_id' => 1,
        'module' => 'Module 1',
        'description' => 'This lesson introduces the basic concepts of databases and tables.',
        'video_url' => 'https://example.com/video',
        'duration' => 45,
        'release_date' => now()->addDay()->toDateString(),
        'status' => 'draft',
    ];

    $response = actingAs($tutor)->post(route('lessons.store'), $payload);

    $response->assertRedirect(route('lessons.create'));
    $response->assertSessionHas('status', 'Lesson saved as draft.');

    assertDatabaseHas('lessons', [
        'user_id' => $tutor->id,
        'title' => 'Introduction to Databases',
        'description' => 'This lesson introduces the basic concepts of databases and tables.',
        'content' => 'This lesson introduces the basic concepts of databases and tables.',
        'course_id' => 1,
        'module' => 'Module 1',
        'status' => 'draft',
    ]);
});
