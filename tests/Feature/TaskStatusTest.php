<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TaskStatusTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private TaskStatus $taskStatus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        TaskStatus::factory()->count(10)->create();
        $this->taskStatus = TaskStatus::query()->first();
    }

    // -------------------------
    // User tests
    // -------------------------

    public function testUserCanSeeTaskStatuses(): void
    {
        $response = $this->get(route('task_statuses.index'));

        $response->assertOk();
    }

    public function testUserCanSeeTaskStatusesCreateForm(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('task_statuses.create'));

        $response->assertOk();
    }

    public function testUserCanSeeTaskStatusesEditForm(): void
    {
        $this
            ->actingAs($this->user)
            ->post(route('task_statuses.store'), ['name' => 'test']);

        $response = $this->get(route('task_statuses.edit', $this->taskStatus->id));
        $response->assertOk();
    }

    public function testUserCanStoreTaskStatus(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->post(route('task_statuses.store'), [
                'name' => 'test',
            ]);

        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas('task_statuses', [
            'name' => 'test',
        ]);
    }

    public function testUserCanUpdateTaskStatus(): void
    {
        $oldName = $this->taskStatus->name;

        $response = $this
            ->actingAs($this->user)
            ->patch(route('task_statuses.update', $this->taskStatus->id), [
                'name' => 'new status',
            ]);

        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas('task_statuses', [
            'name' => 'new status',
        ]);
        $this->assertDatabaseMissing('task_statuses', [
            'name' => $oldName,
        ]);
    }

    public function testUserCanDeleteTaskStatus(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->delete(route('task_statuses.destroy', $this->taskStatus->id));

        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseMissing('task_statuses', [
            'id' => $this->taskStatus->id,
        ]);
    }

    // -------------------------
    // Guest tests
    // -------------------------

    public function testGuestCanSeeTaskStatuses(): void
    {
        $response = $this
            ->get(route('task_statuses.index'));

        $response->assertOk();
    }

    public function testGuestCannotSeeTaskStatusCreateForm(): void
    {
        $response = $this
            ->get(route('task_statuses.create'));

        $response->assertStatus(403);
    }

    public function testGuestCannotSeeTaskStatusEditForm(): void
    {
        $response = $this
            ->get(route('task_statuses.edit', $this->taskStatus->id));

        $response->assertStatus(403);
    }

    public function testGuestCannotStoreTaskStatus(): void
    {
        $response = $this
            ->post(route('task_statuses.store'), ['name' => 'new status']);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('task_statuses', ['name' => 'new status']);
    }

    public function testGuestCannotUpdateTaskStatus(): void
    {
        $response = $this
            ->patch(route('task_statuses.update', $this->taskStatus->id), ['name' => 'updated']);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('task_statuses', ['name' => 'updated']);
    }

    public function testGuestCannotDeleteTaskStatus(): void
    {
        $response = $this
            ->delete(route('task_statuses.destroy', $this->taskStatus->id));

        $response->assertStatus(403);
        $this->assertDatabaseHas('task_statuses', ['id' => $this->taskStatus->id]);
    }
}
