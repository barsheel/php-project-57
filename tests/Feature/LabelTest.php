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


    public function test_user_can_see_labels(): void
    {
        $response = $this->get('/labels');
        $response->assertOk();
    }

    public function test_user_can_see_labels_create_form(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/labels/create');
        $response->assertOk();
    }

    public function test_user_can_see_labels_edit_form(): void
    {
        $this
            ->actingAs($this->user)
            ->post('/labels', [
                'name' => 'test',
            ]);

        $response = $this->get('/labels/1/edit');
        $response->assertOk();
    }


    public function test_user_can_store_label(): void
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

    public function test_user_can_update_label(): void
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

    public function test_user_can_delete_label(): void
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
    public function test_guest_can_see_labels(): void
    {
        Auth::logout();

        $response = $this->get('/labels');
        $response->assertOk('/labels');
    }


    public function test_guest_cannot_see_label_create_form(): void
    {
        Auth::logout();

        $response = $this->get('/labels/create');
        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_see_label_edit_form(): void
    {
        Auth::logout();

        $response = $this->get('/labels/1/edit');
        $response->assertRedirect('/login');
    }


    public function test_guest_cannot_store_label(): void
    {
        Auth::logout();

        $response = $this->post('/labels', ['name' => 'new status']);
        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_update_label(): void
    {
        Auth::logout();

        $response = $this->patch('/labels/1', ['name' => 'updated']);
        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_delete_label(): void
    {
        Auth::logout();

        $response = $this->delete('/labels/1');
        $response->assertRedirect('/login');
    }


    public function test_guest_cannot_delete_task_if_used(): void
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
