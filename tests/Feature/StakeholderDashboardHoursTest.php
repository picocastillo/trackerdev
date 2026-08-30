<?php

namespace Tests\Feature;

use App\Models\Effort;
use App\Models\Project;
use App\Models\Report;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StakeholderDashboardHoursTest extends TestCase
{
    use RefreshDatabase;

    private int $clientRoleId;

    private int $devRoleId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->devRoleId = $this->insertRole('professional', 1);
        $this->clientRoleId = $this->insertRole('stackeholder', 1);
    }

    public function test_stakeholder_dashboard_shows_loaded_hours_per_ticket_and_total(): void
    {
        $client = $this->makeClient();
        $dev = $this->makeDeveloper();
        $project = $this->makeProject([$client->id, $dev->id]);

        $first = $this->makeTask($project->id, [
            'name' => 'Ticket uno',
            'estimation' => 8,
            'billed' => 8,
        ]);
        $second = $this->makeTask($project->id, [
            'name' => 'Ticket dos',
            'estimation' => 4,
            'billed' => 4,
        ]);

        $this->makeEffort($first, $dev, 90);
        $this->makeEffort($second, $dev, 60);

        $this->actingAs($client)
            ->get('/home')
            ->assertOk()
            ->assertSee('Horas cargadas')
            ->assertSee('Ticket uno')
            ->assertSee('Ticket dos')
            ->assertSee('1.50 h')
            ->assertSee('1.00 h')
            ->assertSee('TOTAL 2.50 h');
    }

    public function test_stakeholder_dashboard_includes_hours_after_last_report(): void
    {
        $client = $this->makeClient();
        $dev = $this->makeDeveloper();
        $project = $this->makeProject([$client->id, $dev->id]);

        $openTask = $this->makeTask($project->id, [
            'name' => 'Ticket abierto',
            'billed' => 5,
        ]);
        $reportedTask = $this->makeTask($project->id, [
            'name' => 'Ticket reportado',
            'billed' => 10,
            'paid' => true,
            'is_active' => false,
        ]);

        $this->makeEffort($openTask, $dev, 30);
        $this->makeEffort($reportedTask, $dev, 120, now()->subMonth());

        $from = now()->subMonth()->startOfMonth();
        $to = now()->subMonth()->endOfMonth();

        $report = Report::create([
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'user_id' => $client->id,
            'tasks' => (string) $reportedTask->id,
            'efforts' => '',
            'billed_hours' => 10,
            'productivity' => 100,
            'rate' => 50,
            'detail' => '',
        ]);

        $this->makeEffort($reportedTask, $dev, 45, now());

        $this->actingAs($client)
            ->get('/home')
            ->assertOk()
            ->assertSee('Ticket abierto')
            ->assertSee('Ticket reportado')
            ->assertSee('0.50 h')
            ->assertSee('2.75 h')
            ->assertSee('TOTAL 3.25 h')
            ->assertSee('Incluye horas cargadas posteriores al último reporte')
            ->assertSee('Período')
            ->assertSee($from->format('d/m/Y').' — '.$to->format('d/m/Y'))
            ->assertSee('10.00 h')
            ->assertSee('Descargar')
            ->assertSee('/reports/'.$report->id.'/pdf', false);
    }

    public function test_stakeholder_dashboard_does_not_show_other_project_tickets(): void
    {
        $client = $this->makeClient();
        $otherClient = $this->makeClient('otro@example.com');
        $dev = $this->makeDeveloper();

        $ownProject = $this->makeProject([$client->id, $dev->id], 'Propio');
        $foreignProject = $this->makeProject([$otherClient->id, $dev->id], 'Ajeno');

        $this->makeTask($ownProject->id, ['name' => 'Mia']);
        $this->makeTask($foreignProject->id, ['name' => 'De otro']);

        $this->actingAs($client)
            ->get('/home')
            ->assertOk()
            ->assertSee('Mia')
            ->assertDontSee('De otro');
    }

    private function makeClient(string $email = 'cliente@example.com'): User
    {
        return $this->makeUser([
            'name' => 'Cliente',
            'email' => $email,
            'role_id' => $this->clientRoleId,
        ]);
    }

    private function makeDeveloper(): User
    {
        return $this->makeUser([
            'name' => 'Dev',
            'email' => 'dev@example.com',
            'role_id' => $this->devRoleId,
        ]);
    }

    /**
     * @param  array<int, int>  $userIds
     */
    private function makeProject(array $userIds, string $name = 'Proyecto'): Project
    {
        $project = Project::create(['name' => $name]);
        $project->users()->sync($userIds);

        return $project;
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function makeTask(int $projectId, array $overrides = []): Task
    {
        $id = DB::table('tasks')->insertGetId(array_merge([
            'name' => 'Ticket',
            'description' => 'Descripcion',
            'estimation' => 8,
            'billed' => 8,
            'paid' => false,
            'is_active' => true,
            'history_time' => '',
            'review' => 0,
            'project_id' => $projectId,
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));

        return Task::findOrFail($id);
    }

    private function makeEffort(Task $task, User $user, int $minutes, $createdAt = null): Effort
    {
        $effort = Effort::create([
            'detail' => 'Trabajo',
            'amount' => $minutes,
            'user_id' => $user->id,
            'project_id' => $task->project_id,
            'task_id' => $task->id,
        ]);

        if ($createdAt) {
            $effort->created_at = $createdAt;
            $effort->save();
        }

        return $effort;
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function makeUser(array $overrides = []): User
    {
        return User::create(array_merge([
            'name' => 'Usuario',
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password123',
            'role_id' => $this->devRoleId,
            'is_active' => true,
            'by_hours' => false,
        ], $overrides));
    }

    private function insertRole(string $seniority, float $weight): int
    {
        return (int) DB::table('roles')->insertGetId([
            'seniority' => $seniority,
            'weight' => $weight,
        ]);
    }
}
