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

class ClientReportTest extends TestCase
{
    use RefreshDatabase;

    private int $seniorRoleId;

    private int $clientRoleId;

    private int $devRoleId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seniorRoleId = $this->insertRole('senior', 2);
        $this->devRoleId = $this->insertRole('professional', 1);
        $this->clientRoleId = $this->insertRole('stackeholder', 1);
    }

    public function test_senior_filter_lists_only_clients(): void
    {
        $senior = $this->makeSenior();
        $this->makeClient('Cliente Visible', 'cliente@example.com');
        $this->makeDeveloper('Dev UnicoNombre', 'dev@example.com');

        $this->actingAs($senior)
            ->get('/reports')
            ->assertOk()
            ->assertSee('Cliente Visible')
            ->assertSee('Seleccionar cliente')
            ->assertDontSee('Dev UnicoNombre');
    }

    public function test_senior_preview_shows_client_tasks_and_total(): void
    {
        $senior = $this->makeSenior();
        $client = $this->makeClient();
        $dev = $this->makeDeveloper();
        $project = $this->makeProject([$client->id, $dev->id, $senior->id], 'Proyecto Alpha');

        $task = $this->makeTask($project->id, [
            'name' => 'Ticket preview',
            'billed' => 4,
            'description' => "Primera linea\n  con espacios",
        ]);
        $this->makeEffort($task, $dev, 90);

        $this->actingAs($senior)
            ->get('/reports?user_id='.$client->id.'&start_date='.now()->startOfMonth()->toDateString().'&end_date='.now()->endOfMonth()->toDateString())
            ->assertOk()
            ->assertSee('Ticket preview')
            ->assertSee('Proyecto Alpha')
            ->assertSee('1.50 h')
            ->assertSee('value="4.00"', false)
            ->assertSee("Primera linea\n  con espacios", false)
            ->assertSee('Trabajo (1.50 h) — '.now()->format('d/m/Y'), false)
            ->assertSee('Total parcial')
            ->assertSee('Guardar reporte');
    }

    public function test_preview_lists_hours_from_oldest_to_newest(): void
    {
        $senior = $this->makeSenior();
        $client = $this->makeClient();
        $dev = $this->makeDeveloper();
        $project = $this->makeProject([$client->id, $dev->id, $senior->id], 'Proyecto Orden');

        $newerTask = $this->makeTask($project->id, [
            'name' => 'Ticket nuevo',
            'created_at' => '2026-08-20 10:00:00',
        ]);
        $olderTask = $this->makeTask($project->id, [
            'name' => 'Ticket viejo',
            'created_at' => '2026-08-10 10:00:00',
        ]);
        $this->makeEffort($olderTask, $dev, 60, '2026-08-11 09:00:00', 'Carga vieja');
        $this->makeEffort($olderTask, $dev, 30, '2026-08-18 09:00:00', 'Carga nueva');

        $newerExtra = Effort::create([
            'detail' => 'Extra nueva',
            'amount' => 60,
            'user_id' => $senior->id,
            'project_id' => $project->id,
            'task_id' => null,
        ]);
        $newerExtra->created_at = '2026-08-25 12:00:00';
        $newerExtra->save();

        $olderExtra = Effort::create([
            'detail' => 'Extra vieja',
            'amount' => 45,
            'user_id' => $senior->id,
            'project_id' => $project->id,
            'task_id' => null,
        ]);
        $olderExtra->created_at = '2026-08-12 12:00:00';
        $olderExtra->save();

        $html = $this->actingAs($senior)
            ->get('/reports?user_id='.$client->id.'&start_date=2026-08-01&end_date=2026-08-31')
            ->assertOk()
            ->getContent();

        $this->assertTrue(strpos($html, 'Ticket viejo') < strpos($html, 'Ticket nuevo'));
        $this->assertTrue(strpos($html, 'Carga vieja (1.00 h) — 11/08/2026') < strpos($html, 'Carga nueva (0.50 h) — 18/08/2026'));
        $this->assertTrue(strpos($html, 'Extra vieja (0.75 h) — 12/08/2026') < strpos($html, 'Extra nueva (1.00 h) — 25/08/2026'));
    }

    public function test_hours_to_bill_uses_loaded_hours_when_greater_than_billed(): void
    {
        $senior = $this->makeSenior();
        $client = $this->makeClient();
        $dev = $this->makeDeveloper();
        $project = $this->makeProject([$client->id, $dev->id, $senior->id], 'Proyecto Beta');

        $task = $this->makeTask($project->id, [
            'name' => 'Ticket mayor cargado',
            'billed' => 2,
        ]);
        $this->makeEffort($task, $dev, 210);

        $this->actingAs($senior)
            ->get('/reports?user_id='.$client->id.'&start_date='.now()->startOfMonth()->toDateString().'&end_date='.now()->endOfMonth()->toDateString())
            ->assertOk()
            ->assertSee('3.50 h')
            ->assertSee('value="3.50"', false);
    }

    public function test_preview_extra_hours_are_only_manual(): void
    {
        $senior = $this->makeSenior();
        $client = $this->makeClient();
        $dev = $this->makeDeveloper();
        $project = $this->makeProject([$client->id, $dev->id, $senior->id], 'Proyecto Gamma');

        $task = $this->makeTask($project->id, [
            'name' => 'Ticket con carga',
            'billed' => 4,
        ]);
        $this->makeEffort($task, $senior, 60);
        Effort::create([
            'detail' => 'Reunion extra',
            'amount' => 90,
            'user_id' => $senior->id,
            'project_id' => $project->id,
            'task_id' => null,
        ]);

        $html = $this->actingAs($senior)
            ->get('/reports?user_id='.$client->id.'&start_date='.now()->startOfMonth()->toDateString().'&end_date='.now()->endOfMonth()->toDateString())
            ->assertOk()
            ->assertSee('Ticket con carga')
            ->assertSee('Reunion extra')
            ->assertSee('2.00 h')
            ->assertSee('Horas extra')
            ->assertSee('Reunion extra (1.50 h) — '.now()->format('d/m/Y'), false)
            ->getContent();

        $this->assertEquals(1, substr_count($html, 'name="efforts[]"'));
        $this->assertStringContainsString('name="billed['.$task->id.']"', $html);
    }

    public function test_senior_can_add_manual_extra_hours(): void
    {
        $senior = $this->makeSenior();
        $client = $this->makeClient();
        $project = $this->makeProject([$client->id, $senior->id]);

        $this->actingAs($senior)
            ->post('/reports/hours', [
                'user_id' => $client->id,
                'start_date' => now()->startOfMonth()->toDateString(),
                'end_date' => now()->endOfMonth()->toDateString(),
                'kind' => 'manual',
                'project_id' => $project->id,
                'detail' => 'Reunion extra',
                'hours' => 1.5,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('efforts', [
            'detail' => 'Reunion extra',
            'project_id' => $project->id,
            'task_id' => null,
            'user_id' => $senior->id,
            'amount' => 90,
        ]);
    }

    public function test_senior_can_add_extra_hours_on_a_task(): void
    {
        $senior = $this->makeSenior();
        $client = $this->makeClient();
        $project = $this->makeProject([$client->id, $senior->id]);
        $task = $this->makeTask($project->id, ['name' => 'Ticket horas']);

        $this->actingAs($senior)
            ->post('/reports/hours', [
                'user_id' => $client->id,
                'start_date' => now()->startOfMonth()->toDateString(),
                'end_date' => now()->endOfMonth()->toDateString(),
                'kind' => 'task',
                'task_id' => $task->id,
                'detail' => 'QA extra',
                'hours' => 0.5,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('efforts', [
            'detail' => 'QA extra',
            'project_id' => $project->id,
            'task_id' => $task->id,
            'user_id' => $senior->id,
            'amount' => 30,
        ]);

        $html = $this->actingAs($senior)
            ->get('/reports?user_id='.$client->id.'&start_date='.now()->startOfMonth()->toDateString().'&end_date='.now()->endOfMonth()->toDateString())
            ->assertOk()
            ->assertSee('Ticket horas')
            ->assertSee('1.00 h')
            ->getContent();

        $this->assertEquals(0, substr_count($html, 'name="efforts[]"'));
        $this->assertStringContainsString('name="billed['.$task->id.']"', $html);
    }

    public function test_store_creates_client_report_updates_billed_and_marks_paid(): void
    {
        $senior = $this->makeSenior();
        $client = $this->makeClient();
        $dev = $this->makeDeveloper();
        $project = $this->makeProject([$client->id, $dev->id, $senior->id]);
        $task = $this->makeTask($project->id, [
            'name' => 'Ticket a facturar',
            'billed' => 4,
        ]);
        $this->makeEffort($task, $dev, 60);

        $manual = Effort::create([
            'detail' => 'Soporte',
            'amount' => 60,
            'user_id' => $senior->id,
            'project_id' => $project->id,
            'task_id' => null,
        ]);

        $from = now()->startOfMonth()->toDateString();
        $to = now()->endOfMonth()->toDateString();

        $response = $this->actingAs($senior)->post('/reports/store', [
            'user_id' => $client->id,
            'from' => $from,
            'to' => $to,
            'tasks' => [$task->id],
            'efforts' => [$manual->id],
            'billed' => [$task->id => 6],
            'effort_hours' => [$manual->id => 2],
            'rate' => 50,
            'detail' => 'Resumen editado',
        ]);

        $report = Report::query()->where('user_id', $client->id)->first();
        $this->assertNotNull($report);
        $response->assertRedirect('reports/'.$report->id);

        $this->assertEquals(8.0, (float) $report->billed_hours);
        $this->assertEquals(50.0, (float) $report->rate);
        $this->assertEquals('Resumen editado', $report->detail);
        $this->assertEquals((string) $task->id, $report->tasks);
        $this->assertEquals((string) $manual->id, $report->efforts);

        $task->refresh();
        $this->assertEquals(6.0, (float) $task->billed);
        $this->assertTrue((bool) $task->paid);
        $this->assertFalse((bool) $task->is_active);

        $manual->refresh();
        $this->assertEquals(120, $manual->amount);
    }

    public function test_senior_can_view_client_report_show(): void
    {
        $senior = $this->makeSenior();
        $client = $this->makeClient();
        $project = $this->makeProject([$client->id, $senior->id], 'Proyecto Show');
        $task = $this->makeTask($project->id, ['name' => 'Ticket show']);

        $report = Report::create([
            'from' => now()->startOfMonth()->toDateString(),
            'to' => now()->endOfMonth()->toDateString(),
            'user_id' => $client->id,
            'tasks' => (string) $task->id,
            'efforts' => '',
            'billed_hours' => 4,
            'productivity' => 100,
            'rate' => 50,
            'detail' => 'Resumen del cliente',
        ]);

        $this->actingAs($senior)
            ->get('/reports/'.$report->id)
            ->assertOk()
            ->assertSee('Detalle de reporte')
            ->assertSee('Descargar PDF')
            ->assertSee('Borrar')
            ->assertSee('Proyecto Show')
            ->assertSee('Ticket show')
            ->assertSee('Resumen del cliente')
            ->assertSee(now()->format('d/m/Y'))
            ->assertDontSee($client->name, false);
    }

    public function test_senior_can_download_report_pdf(): void
    {
        $senior = $this->makeSenior();
        $client = $this->makeClient();
        $project = $this->makeProject([$client->id, $senior->id]);
        $task = $this->makeTask($project->id, ['name' => 'Ticket pdf']);

        $report = Report::create([
            'from' => now()->startOfMonth()->toDateString(),
            'to' => now()->endOfMonth()->toDateString(),
            'user_id' => $client->id,
            'tasks' => (string) $task->id,
            'efforts' => '',
            'billed_hours' => 4,
            'productivity' => 100,
            'rate' => 50,
            'detail' => 'Para PDF',
        ]);

        $response = $this->actingAs($senior)->get('/reports/'.$report->id.'/pdf');

        $response->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $response->headers->get('content-type'));
        $this->assertStringStartsWith('%PDF', $response->getContent());
        $this->assertStringContainsString('/Image', $response->getContent());
        $this->assertStringContainsString(
            'attachment; filename="reporte-'.$report->id.'-'.$report->from.'.pdf"',
            (string) $response->headers->get('content-disposition')
        );
    }

    public function test_other_user_cannot_view_or_download_report(): void
    {
        $this->makeSenior();
        $client = $this->makeClient();
        $other = $this->makeDeveloper('Otro', 'otro@example.com');

        $report = Report::create([
            'from' => now()->startOfMonth()->toDateString(),
            'to' => now()->endOfMonth()->toDateString(),
            'user_id' => $client->id,
            'tasks' => '',
            'efforts' => '',
            'billed_hours' => 1,
            'productivity' => 0,
            'rate' => 10,
            'detail' => '',
        ]);

        $this->actingAs($other)->get('/reports/'.$report->id)->assertUnauthorized();
        $this->actingAs($other)->get('/reports/'.$report->id.'/pdf')->assertUnauthorized();
        $this->actingAs($other)->delete('/reports/'.$report->id)->assertUnauthorized();
        $this->assertDatabaseHas('reports', ['id' => $report->id]);
    }

    public function test_senior_can_delete_report_and_reopen_tasks(): void
    {
        $senior = $this->makeSenior();
        $client = $this->makeClient();
        $project = $this->makeProject([$client->id, $senior->id]);
        $task = $this->makeTask($project->id, [
            'name' => 'Ticket borrar',
            'paid' => true,
            'is_active' => false,
        ]);
        $manual = Effort::create([
            'detail' => 'Extra',
            'amount' => 60,
            'user_id' => $senior->id,
            'project_id' => $project->id,
            'task_id' => null,
        ]);
        $manual->paid = true;
        $manual->save();

        $report = Report::create([
            'from' => now()->startOfMonth()->toDateString(),
            'to' => now()->endOfMonth()->toDateString(),
            'user_id' => $client->id,
            'tasks' => (string) $task->id,
            'efforts' => (string) $manual->id,
            'billed_hours' => 5,
            'productivity' => 100,
            'rate' => 50,
            'detail' => '',
        ]);

        $this->actingAs($senior)
            ->delete('/reports/'.$report->id)
            ->assertRedirect('/reports');

        $this->assertDatabaseMissing('reports', ['id' => $report->id]);

        $task->refresh();
        $this->assertFalse((bool) $task->paid);
        $this->assertTrue((bool) $task->is_active);

        $manual->refresh();
        $this->assertFalse((bool) $manual->paid);
    }

    public function test_generate_developers_command_creates_report_and_does_not_duplicate(): void
    {
        $dev = $this->makeDeveloper();
        $client = $this->makeClient();
        $project = $this->makeProject([$client->id, $dev->id]);
        $task = $this->makeTask($project->id, [
            'name' => 'Ticket dev',
            'user_id' => $dev->id,
            'estimation' => 2,
            'created_at' => '2026-07-10 10:00:00',
        ]);
        $this->makeEffort($task, $dev, 120, '2026-07-15 12:00:00');

        $this->artisan('reports:generate-developers', [
            'from' => '2026-07-01',
            'to' => '2026-07-31',
        ])->assertSuccessful();

        $this->assertDatabaseCount('reports', 1);
        $report = Report::query()->where('user_id', $dev->id)->first();
        $this->assertNotNull($report);
        $this->assertEquals(2.0, (float) $report->billed_hours);
        $this->assertTrue((bool) Effort::query()->where('user_id', $dev->id)->first()->paid);

        $this->artisan('reports:generate-developers', [
            'from' => '2026-07-01',
            'to' => '2026-07-31',
        ])->assertSuccessful();

        $this->assertDatabaseCount('reports', 1);
    }

    private function makeSenior(): User
    {
        return $this->makeUser([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'role_id' => $this->seniorRoleId,
        ]);
    }

    private function makeClient(string $name = 'Cliente', string $email = 'cliente@example.com'): User
    {
        return $this->makeUser([
            'name' => $name,
            'email' => $email,
            'role_id' => $this->clientRoleId,
        ]);
    }

    private function makeDeveloper(string $name = 'Dev', string $email = 'dev@example.com'): User
    {
        return $this->makeUser([
            'name' => $name,
            'email' => $email,
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

    private function makeEffort(Task $task, User $user, int $minutes, $createdAt = null, string $detail = 'Trabajo'): Effort
    {
        $effort = Effort::create([
            'detail' => $detail,
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
