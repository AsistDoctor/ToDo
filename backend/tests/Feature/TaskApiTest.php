<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_task_goes_through_its_whole_lifecycle(): void
    {
        $created = $this->postJson('/api/tasks', ['title' => 'Выучить MVC'])->assertCreated();

        $this->assertSame(false, $created->json('done'));
        $this->getJson('/api/tasks')->assertOk()->assertJsonCount(1);

        $id = $created->json('id');

        $this->putJson("/api/tasks/{$id}", ['done' => true])
            ->assertOk()
            ->assertJson(['title' => 'Выучить MVC', 'done' => true]);
        $this->getJson("/api/tasks/{$id}")->assertOk()->assertJson(['done' => true]);

        $this->deleteJson("/api/tasks/{$id}")->assertNoContent();
        $this->getJson('/api/tasks')->assertOk()->assertJsonCount(0);
    }

    public function test_an_empty_title_is_rejected_and_nothing_is_saved(): void
    {
        $this->postJson('/api/tasks', ['title' => ''])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('title');

        $this->assertDatabaseCount('tasks', 0);

        // Клиент без заголовка Accept: application/json (так приходит через прокси Nuxt) тоже должен получить JSON, а не редирект
        $this->post('/api/tasks', ['title' => ''])
            ->assertStatus(422)
            ->assertJsonPath('errors.title.0', 'The title field is required.');
    }

    public function test_an_unknown_task_returns_404(): void
    {
        $this->putJson('/api/tasks/999', ['done' => true])->assertNotFound();
    }
}
