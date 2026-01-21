<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private User $assignedUser;

    private TaskStatus $taskStatus;

    private Task $task;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->assignedUser = User::factory()->create();
        $this->taskStatus = TaskStatus::factory()->create();
        Task::factory()->count(10)->create();
        $this->task = Task::query()->first();
    }



    // user tests
    public function testUserCanSeeTasks(): void
    {
        $response = $this->get('/tasks');
        $response->assertOk();
    }

    public function testUserCanSeeCreationForm(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/tasks/create');
        $response->assertOk();
    }

    public function testUserCanSeeTask(): void
    {
        $response = $this->get("/tasks/{$this->task->id}");
        $response->assertOk();
    }

    public function testUserCanSeeEditForm(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get("/tasks/{$this->task->id}/edit");

        $response->assertOk();
    }

    public function testUserCanCreateTask(): void
    {

        $response = $this
            ->actingAs($this->user)
            ->post('/tasks', [
                'name' => 'NEW NAME',
                'description' => 'NEW DESCRIPTION',
                'status_id' => $this->taskStatus->id,
                'assigned_to_id' => $this->assignedUser->id
            ]);

        $response->assertRedirect('/tasks');

        $this->assertDatabaseHas('tasks', [
            'name' => 'NEW NAME'
        ]);
    }

    public function testUserCanUpdateTask(): void
    {
        $initialName = $this->task->name;

        $response = $this
            ->actingAs($this->user)
            ->patch("/tasks/{$this->task->id}", [
                'name' => 'CHANGED NAME',
                'description' => 'CHANGED DESCRIPTION',
                'status_id' => $this->taskStatus->id,
                'assigned_to_id' => $this->assignedUser->id
            ]);

        $response->assertRedirect('/tasks');

        $this->assertDatabaseMissing('tasks', [
            'name' => $initialName
        ]);

        $this->assertDatabaseHas('tasks', [
            'name' => 'CHANGED NAME'
        ]);
    }

    public function testUserCanDeleteOwnTask(): void
    {
        $this->task->creator()->associate($this->user);
        $this->task->save();

        $response = $this
            ->actingAs($this->user)
            ->delete("/tasks/{$this->task->id}");

        $this->assertDatabaseMissing('tasks', [
            'id' => $this->task->id
        ]);
        $response->assertRedirect('/tasks');
    }

    public function testUserCantDeleteForeignUsersTask(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->delete("/tasks/{$this->task->id}");

        $response->assertRedirect('/tasks');

        $this->assertDatabaseHas('tasks', [
            'id' => $this->task->id
        ]);
    }


    // guest tests
    public function testGuestCanSeeTask(): void
    {
        $response = $this->get("/tasks/{$this->task->id}");
        $response->assertOk();
    }

    public function testGuestCanSeeTasks(): void
    {
        $response = $this->get("/tasks");
        $response->assertOk();
    }

    public function testGuestCantCreateTask(): void
    {
        $taskCount = Task::count();
        $response = $this
            ->post("/tasks", [
                'name' => 'NEW NAME',
                'description' => 'NEW DESCRIPTION',
                'status_id' => $this->taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->assignedUser->id,
            ]);

        $response->assertStatus(403);

        $this->assertDatabaseCount('tasks', $taskCount);
    }

    public function testGuestCantUpdateTask(): void
    {
        $response = $this
            ->patch('/tasks', [
                'id' => $this->task->id,
                'name' => 'NEW NAME',
                'description' => 'NEW DESCRIPTION',
                'status_id' => $this->taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->assignedUser->id,
            ]);

        $response->assertStatus(405);

        $this->assertDatabaseMissing('tasks', [
            'id' => $this->task->id,
            'name' => 'NEW NAME'
        ]);
    }

    public function testGuestCantDeleteTask(): void
    {
        $response = $this
            ->delete("/tasks/{$this->task->id}");

        $response->assertStatus(302);

        $this->assertDatabaseHas('tasks', [
            'id' =>  $this->task->id
        ]);
    }
}
