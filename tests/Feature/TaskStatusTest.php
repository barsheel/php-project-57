<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TaskStatusTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }


    public function testUserCanSeeTaskStatuses(): void
    {
        $response = $this->get('/task_statuses');
        $response->assertOk();
    }

    public function testUserCanSeeTaskStatusesCreateForm(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/task_statuses/create');
        $response->assertOk();
    }

    public function testUserCanSeeTaskStatusesEditForm(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/task_statuses', [
                'name' => 'test',
            ]);

        $response = $this->get('/task_statuses/1/edit');
        $response->assertOk();
    }


    public function testUserCanStoreTaskStatus(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->post('/task_statuses', [
                'name' => 'test',
            ]);

        $response->assertRedirect('/task_statuses');

        $this->assertDatabaseHas('task_statuses', [
            'name' => 'test',
        ]);
    }

    public function testUserCanUpdateTaskStatus(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/task_statuses', [
                'name' => 'old status',
            ]);

        $response = $this
            ->actingAs($this->user)
            ->patch('/task_statuses/1', [
                'name' => 'new status',
            ]);

        $response->assertRedirect('/task_statuses');

        $this->assertDatabaseHas('task_statuses', [
            'name' => 'new status',
        ]);

        $this->assertDatabaseMissing('task_statuses', [
            'name' => 'old status',
        ]);
    }

    public function testUserCanDeleteTaskStatus(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/task_statuses', [
                'name' => 'test',
            ]);

        $this->assertDatabaseHas('task_statuses', [
            'id' => '1',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->delete('/task_statuses/1');

        $response->assertRedirect('/task_statuses');

        $this->assertDatabaseMissing('task_statuses', [
            'id' => '1',
        ]);
    }



    // guest
    public function testGuestCanSeeTaskStatuses(): void
    {
        Auth::logout();

        $response = $this->get('/task_statuses');
        $response->assertOk('/task_statuses');
    }


    public function testGuestCannotSeeTaskStatusCreateForm(): void
    {
        Auth::logout();

        $response = $this->get('/task_statuses/create');
        $response->assertRedirect('/login');
    }

    public function testGuestCannotSeeTaskStatusEditForm(): void
    {
        Auth::logout();

        $response = $this->get('/task_statuses/1/edit');
        $response->assertRedirect('/login');
    }


    public function testGuestCannotStoreTaskStatus(): void
    {
        Auth::logout();

        $response = $this->post('/task_statuses', ['name' => 'new status']);
        $response->assertRedirect('/login');
    }

    public function testGuestCannotUpdateTaskStatus(): void
    {
        Auth::logout();

        $response = $this->patch('/task_statuses/1', ['name' => 'updated']);
        $response->assertRedirect('/login');
    }

    public function testGuestCannotDeleteTaskStatus(): void
    {
        Auth::logout();

        $response = $this->delete('/task_statuses/1');
        $response->assertRedirect('/login');
    }
}
