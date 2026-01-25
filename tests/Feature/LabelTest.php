<?php

namespace Tests\Feature;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    // -------------------------
    // User tests
    // -------------------------

    public function testUserCanSeeLabels(): void
    {
        $response = $this->get(route('labels.index'));

        $response->assertOk();
    }

    public function testUserCanSeeLabelsCreateForm(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('labels.create'));

        $response->assertOk();
    }

    public function testUserCanSeeLabelsEditForm(): void
    {
        $this
            ->actingAs($this->user)
            ->post(route('labels.store'), ['name' => 'test']);

        $response = $this->get(route('labels.edit', $this->label->id));
        $response->assertOk();
    }

    public function testUserCanStoreLabel(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->post(route('labels.store'), [
                'name' => 'test',
            ]);

        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', [
            'name' => 'test',
        ]);
    }

    public function testUserCanUpdateLabel(): void
    {
        $oldName = $this->label->name;

        $response = $this
            ->actingAs($this->user)
            ->patch(route('labels.update', $this->label->id), [
                'name' => 'NEW NAME',
            ]);

        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', [
            'name' => 'NEW NAME',
        ]);
        $this->assertDatabaseMissing('labels', [
            'name' => $oldName,
        ]);
    }

    public function testUserCanDeleteLabel(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->delete(route('labels.destroy', $this->label->id));

        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseMissing('labels', [
            'id' => $this->label->id,
        ]);
    }

    public function testUserCannotDeleteLabelIfUsed(): void
    {
        $taskStatus = TaskStatus::factory()->create();

        $this
            ->actingAs($this->user)
            ->post(route('tasks.store'), [
                'name' => 'test',
                'description' => 'test',
                'status_id' => $taskStatus->id,
                'created_by_id' => $this->user->id,
                'assigned_to_id' => $this->user->id,
                'labels' => [$this->label->id],
            ]);

        $this->assertDatabaseHas('tasks', ['name' => 'test']);
        $response = $this
            ->actingAs($this->user)
            ->delete(route('labels.destroy', $this->label->id));
        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', ['id' => $this->label->id]);
    }

    // -------------------------
    // Guest tests
    // -------------------------

    public function testGuestCanSeeLabels(): void
    {
        $response = $this
            ->get(route('labels.index'));

        $response->assertOk();
    }

    public function testGuestCannotSeeLabelCreateForm(): void
    {
        $response = $this
            ->get(route('labels.create'));

        $response->assertStatus(403);
    }

    public function testGuestCannotSeeLabelEditForm(): void
    {
        $response = $this
            ->get(route('labels.edit', $this->label->id));

        $response->assertStatus(403);
    }

    public function testGuestCannotStoreLabel(): void
    {
        $response = $this
            ->post(
                route('labels.store'),
                ['name' => 'new status']
            );

        $response->assertStatus(403);
        $this->assertDatabaseMissing('labels', ['name' => 'new status']);
    }

    public function testGuestCannotUpdateLabel(): void
    {
        $response = $this
            ->patch(
                route('labels.update', $this->label->id),
                ['name' => 'updated']
            );

        $response->assertStatus(403);
        $this->assertDatabaseMissing('labels', ['name' => 'updated']);
    }

    public function testGuestCannotDeleteLabel(): void
    {
        $response = $this
            ->delete(route('labels.destroy', $this->label->id));

        $response->assertStatus(403);
        $this->assertDatabaseHas('labels', ['id' => $this->label->id]);
    }
}
