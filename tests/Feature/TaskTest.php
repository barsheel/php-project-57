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

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->assignedUser = User::factory()->create();
        $this->taskStatus = TaskStatus::factory()->create();

        Task::create([
            'name' => 'INITIAL NAME',
            'description' => 'INITIAL DESCRIPTION',
            'status_id' => $this->taskStatus->id,
            'created_by_id' => $this->user->id,
            'assigned_to_id' => $this->assignedUser->id
        ]);
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
        $response = $this->get('/tasks/1');
        $response->assertOk();
    }

    public function testUserCanSeeEditForm(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/tasks/1/edit');

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
        $this->assertDatabaseHas('tasks', [
            'name' => 'INITIAL NAME'
        ]);

        $response = $this
            ->actingAs($this->user)
            ->patch('/tasks/1', [
                'name' => 'CHANGED NAME',
                'description' => 'CHANGED DESCRIPTION',
                'status_id' => $this->taskStatus->id,
                'assigned_to_id' => $this->assignedUser->id
            ]);

        $response->assertRedirect('/tasks');

        $this->assertDatabaseMissing('tasks', [
            'name' => 'INITIAL NAME'
        ]);

        $this->assertDatabaseHas('tasks', [
            'name' => 'CHANGED NAME'
        ]);
    }

    public function testUserCanDeleteOwnTask(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->delete('/tasks/1');

        $this->assertDatabaseMissing('tasks', [
            'id' => 1
        ]);
        $response->assertRedirect('/tasks');
    }

    public function testUserCantDeleteForeignUsersTask(): void
    {
        $response = $this
            ->actingAs($this->assignedUser)
            ->delete('/tasks/1');

        $response->assertRedirect('/tasks');

        $this->assertDatabaseHas('tasks', [
            'id' => '1'
        ]);
    }


    // guest tests
    public function testGuestCanSeeTask(): void
    {
        $response = $this->get('/tasks/1/edit');
        $response->assertOk();
    }

    public function testGuestCanSeeTasks(): void
    {
        $response = $this->get('/tasks');
        $response->assertOk();
    }

    public function testGuestCantCreateTask(): void
    {
        $response = $this
            ->post('/tasks', [
                'name' => 'NEW NAME',
                'description' => 'NEW DESCRIPTION',
                'status_id' => $this->taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->assignedUser->id,
            ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('tasks', [
            'id' => '2'
        ]);
    }

    public function testGuestCantUpdateTask(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->patch('/tasks', [
                'id' => 1,
                'name' => 'NEW NAME',
                'description' => 'NEW DESCRIPTION',
                'status_id' => $this->taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->assignedUser->id,
            ]);

        $response->assertStatus(405);

        $this->assertDatabaseMissing('tasks', [
            'id' => '1',
            'name' => 'NEW NAME'
        ]);
    }

    public function testGuestCantDeleteTask(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->delete('/tasks', [
                'id' => 1
            ]);

        $response->assertStatus(405);

        $this->assertDatabaseHas('tasks', [
            'id' => '1'
        ]);
    }
}
