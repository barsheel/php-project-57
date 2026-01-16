<?php

namespace Tests\Feature;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class LabelTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }


    public function testUserCanSeeLabels(): void
    {
        $response = $this->get('/labels');
        $response->assertOk();
    }

    public function testUserCanSeeLabelsCreateForm(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/labels/create');
        $response->assertOk();
    }

    public function testUserCanSeeLabelsEditForm(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/labels', [
                'name' => 'test',
            ]);

        $response = $this->get('/labels/1/edit');
        $response->assertOk();
    }


    public function testUserCanStoreLabel(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->post('/labels', [
                'name' => 'test',
            ]);

        $response->assertRedirect('/labels');

        $this->assertDatabaseHas('labels', [
            'name' => 'test',
        ]);
    }

    public function testUserCanUpdateLabel(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/labels', [
                'name' => 'old name',
            ]);

        $response = $this
            ->actingAs($this->user)
            ->patch('/labels/1', [
                'name' => 'new name',
            ]);

        $response->assertRedirect('/labels');

        $this->assertDatabaseHas('labels', [
            'name' => 'new name',
        ]);

        $this->assertDatabaseMissing('labels', [
            'name' => 'old name',
        ]);
    }

    public function testUserCanDeleteLabel(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/labels', [
                'name' => 'test',
            ]);

        $this->assertDatabaseHas('labels', [
            'id' => '1',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->delete('/labels/1');

        $response->assertRedirect('/labels');

        $this->assertDatabaseMissing('labels', [
            'id' => '1',
        ]);
    }



    // guest
    public function testGuestCanSeeLabels(): void
    {
        Auth::logout();

        $response = $this->get('/labels');
        $response->assertOk('/labels');
    }


    public function testGuestCannotSeeLabelCreateForm(): void
    {
        Auth::logout();

        $response = $this->get('/labels/create');
        $response->assertRedirect('/login');
    }

    public function testGuestCannotSeeLabelEditForm(): void
    {
        Auth::logout();

        $response = $this->get('/labels/1/edit');
        $response->assertRedirect('/login');
    }


    public function testGuestCannotStoreLabel(): void
    {
        Auth::logout();

        $response = $this->post('/labels', ['name' => 'new status']);
        $response->assertRedirect('/login');
    }

    public function testGuestCannotUpdateLabel(): void
    {
        Auth::logout();

        $response = $this->patch('/labels/1', ['name' => 'updated']);
        $response->assertRedirect('/login');
    }

    public function testGuestCannotDeleteLabel(): void
    {
        Auth::logout();

        $response = $this->delete('/labels/1');
        $response->assertRedirect('/login');
    }


    public function testGuestCannotDeleteTaskIfUsed(): void
    {
        $label = Label::create(['name' => 'test']);
        $this->assertDatabaseHas('labels', ['name' => 'test']);

        $taskStatus = TaskStatus::create(['name' => 'test']);
        $this->assertDatabaseHas('task_statuses', ['name' => 'test']);

        $response = $this
            ->actingAs($this->user)
            ->post('/tasks', [
                'name' => 'test',
                'description' => 'test',
                'status_id' => $taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->user->id,
            ]);

        $this->assertDatabaseHas('tasks', ['name' => 'test']);
        $response = $this->delete('/labels/1');
        $response->assertRedirect('/labels');
        $this->assertDatabaseMissing('labels', ['id' => '1']);
    }
}
