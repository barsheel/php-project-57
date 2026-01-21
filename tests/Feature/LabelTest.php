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
    private Label $label;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Label::factory()->count(10)->create();
        $this->label = Label::query()->first();
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

        $response = $this->get("/labels/{$this->label->id}/edit");
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
        $oldName = $this->label->name;

        $response = $this
            ->actingAs($this->user)
            ->patch("/labels/{$this->label->id}", [
                'name' => 'NEW NAME',
            ]);

        $response->assertRedirect('/labels');

        $this->assertDatabaseHas('labels', [
            'name' => 'NEW NAME',
        ]);

        $this->assertDatabaseMissing('labels', [
            'name' => $oldName
        ]);
    }

    public function testUserCanDeleteLabel(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->delete("/labels/{$this->label->id}");

        $response->assertRedirect('/labels');

        $this->assertDatabaseMissing('labels', [
            'id' => $this->label->id,
        ]);
    }

    public function testUserCannotDeleteTaskIfUsed(): void
    {
        $taskStatus = TaskStatus::factory()->create();

        $response = $this
            ->actingAs($this->user)
            ->post('/tasks', [
                'name' => 'test',
                'description' => 'test',
                'status_id' => $taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->user->id,
                'labels' => [$this->label->id]
            ]);

        $this->assertDatabaseHas('tasks', [
            'name' => 'test'
        ]);
        $response = $this->delete("/labels/{$this->label->id}");
        $response->assertRedirect('/labels');
        $this->assertDatabaseHas('labels', [
            'id' => $this->label->id
        ]);
    }

    // guest
    public function testGuestCanSeeLabels(): void
    {
        $response = $this->get('/labels');
        $response->assertOk();
    }


    public function testGuestCannotSeeLabelCreateForm(): void
    {
        $response = $this->get('/labels/create');
        $response->assertStatus(403);
    }

    public function testGuestCannotSeeLabelEditForm(): void
    {
        $response = $this->get('/labels/1/edit');
        $response->assertStatus(403);
    }


    public function testGuestCannotStoreLabel(): void
    {
        $response = $this->post('/labels', ['name' => 'new status']);
        $response->assertStatus(403);
    }

    public function testGuestCannotUpdateLabel(): void
    {
        $response = $this->patch('/labels/1', ['name' => 'updated']);
        $response->assertStatus(403);
    }

    public function testGuestCannotDeleteLabel(): void
    {
        $response = $this->delete("/labels/{$this->label->id}");
        $response->assertStatus(403);
    }
}
