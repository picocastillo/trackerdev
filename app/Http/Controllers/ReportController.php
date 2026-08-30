<?php

namespace App\Http\Controllers;

use App\Models\Effort;
use App\Models\Project;
use App\Models\Report;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));

        if (isSenior()) {
            if ($request->filled('user_id')) {
                $request->validate([
                    'user_id' => 'required|integer|exists:users,id',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
                ], [
                    'start_date.date' => 'La fecha inicial no es válida',
                    'end_date.after_or_equal' => 'La fecha inicial no debe ser mayor a la fecha final',
                    'end_date.date' => 'La fecha final no es válida',
                ]);
            }

            $clients = User::query()->clients()->with('role')->orderBy('name')->get();
            $reports = Report::with('user')->orderByDesc('id')->paginate(10)->withQueryString();
            $efforts = Effort::query()->where('paid', false)->where('user_id', Auth::id())->get();
            $preview = $this->previewForClient($request, $startDate, $endDate);

            return view('reports.index', array_merge([
                'clients' => $clients,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'reports' => $reports,
                'efforts' => $efforts,
                'selectedClientId' => $request->input('user_id'),
            ], $preview));
        }

        if (isClient()) {
            $user = Auth::user();
            $reports = Report::query()->where('user_id', $user->id)->orderByDesc('id')->paginate(10);
            $dashboard = Task::forStakeholderDashboard($user);

            return view('reports.index', [
                'reports' => $reports,
                'tasks' => $dashboard['tasks'],
                'lastReport' => $dashboard['lastReport'],
                'totalLoadedHours' => $dashboard['totalLoadedHours'],
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
        }

        $reports = Report::query()->where('user_id', Auth::id())->orderByDesc('id')->paginate(10);
        $efforts = Effort::query()->where('paid', false)->where('user_id', Auth::id())->get();

        return view('reports.index', [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'reports' => $reports,
            'efforts' => $efforts,
        ]);
    }

    public function show($id)
    {
        return view('reports.show', $this->reportViewData((int) $id));
    }

    public function pdf($id)
    {
        $data = $this->reportViewData((int) $id);
        $data['watermark'] = $this->watermarkDataUri();
        $data['logo'] = $this->logoDataUri();

        $options = new Options;
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $options->setChroot(public_path());

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('reports.pdf', $data)->render());
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'reporte-'.$data['report']->id.'-'.$data['report']->from.'.pdf';

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function destroy($id)
    {
        if (! isSenior()) {
            abort(401, 'No podes borrar este reporte');
        }

        $report = Report::query()->findOrFail($id);
        $taskIds = $this->idsFromList($report->tasks);
        $effortIds = $this->idsFromList($report->efforts);

        DB::transaction(function () use ($report, $taskIds, $effortIds) {
            if ($taskIds !== []) {
                Task::query()->whereIn('id', $taskIds)->update([
                    'paid' => false,
                    'is_active' => true,
                ]);
            }

            if ($effortIds !== []) {
                Effort::query()->whereIn('id', $effortIds)->update([
                    'paid' => false,
                ]);
            }

            $report->delete();
        });

        return redirect('/reports')->with('alert-success', 'Reporte borrado');
    }

    public function addHours(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'kind' => 'required|in:manual,task',
            'detail' => 'required|string|max:2000',
            'hours' => 'required|numeric|min:0.01',
            'project_id' => 'required_if:kind,manual|nullable|integer|exists:projects,id',
            'task_id' => 'required_if:kind,task|nullable|integer|exists:tasks,id',
        ], [
            'detail.required' => 'El detalle de las horas es obligatorio',
            'hours.min' => 'Las horas deben ser mayores a 0',
            'kind.in' => 'El tipo de hora extra no es válido',
        ]);

        $client = User::query()->with('role')->findOrFail($request->user_id);
        if (! $client->isClient()) {
            throw ValidationException::withMessages([
                'user_id' => 'Solo se pueden agregar horas a un reporte de cliente',
            ]);
        }

        $projectIds = $client->projects()->pluck('projects.id');
        $taskId = null;
        $projectId = (int) $request->project_id;

        if ($request->kind === 'task') {
            $task = Task::findOrFail($request->task_id);
            if (! $projectIds->contains($task->project_id)) {
                throw ValidationException::withMessages([
                    'task_id' => 'La tarea no pertenece a un proyecto del cliente',
                ]);
            }
            $taskId = $task->id;
            $projectId = $task->project_id;
        } elseif (! $projectIds->contains($projectId)) {
            throw ValidationException::withMessages([
                'project_id' => 'El proyecto no pertenece al cliente',
            ]);
        }

        Effort::create([
            'detail' => $request->detail,
            'user_id' => Auth::id(),
            'project_id' => $projectId,
            'task_id' => $taskId,
            'amount' => (int) round(((float) $request->hours) * 60),
        ]);

        return redirect()->route('reports.index', [
            'user_id' => $client->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ])->with('alert-success', 'Horas agregadas al resumen');
    }

    public function store(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
            'user_id' => 'required|integer|exists:users,id',
            'tasks' => 'nullable|array',
            'tasks.*' => 'integer|exists:tasks,id',
            'efforts' => 'nullable|array',
            'efforts.*' => 'integer|exists:efforts,id',
            'billed' => 'nullable|array',
            'billed.*' => 'numeric|min:0',
            'effort_hours' => 'nullable|array',
            'effort_hours.*' => 'numeric|min:0',
            'rate' => 'required|numeric|min:0',
            'detail' => 'nullable|string',
        ]);

        $client = User::query()->with('role')->findOrFail($request->user_id);
        if (! $client->isClient()) {
            return redirect()->back()->with('alert-danger', 'El reporte solo puede crearse para un cliente')->withInput();
        }

        $taskIds = collect($request->input('tasks', []))->map(fn ($id) => (int) $id)->unique()->values();
        $effortIds = collect($request->input('efforts', []))->map(fn ($id) => (int) $id)->unique()->values();

        if ($taskIds->isEmpty() && $effortIds->isEmpty()) {
            return redirect()->back()->with('alert-danger', 'El reporte no tiene tareas ni horas para guardar')->withInput();
        }

        $latest = Report::query()->where('user_id', $client->id)->orderByDesc('id')->first();
        if ($latest && Carbon::parse($latest->to)->gte(Carbon::parse($request->from))) {
            return redirect()->back()->with('alert-danger', 'Estas creando un reporte con una fecha ya incluida por otro para un mismo usuario')->withInput();
        }

        $projectIds = $client->projects()->pluck('projects.id');

        try {
            $report = DB::transaction(function () use ($request, $client, $taskIds, $effortIds, $projectIds) {
                $billedByTask = collect($request->input('billed', []));
                $hoursByEffort = collect($request->input('effort_hours', []));

                $tasks = $taskIds->isEmpty()
                    ? collect()
                    : Task::query()->with(['efforts.user.role'])->whereIn('id', $taskIds)->get();

                foreach ($tasks as $task) {
                    if (! $projectIds->contains($task->project_id)) {
                        throw ValidationException::withMessages([
                            'tasks' => 'Hay tareas que no pertenecen al cliente',
                        ]);
                    }

                    if ($billedByTask->has((string) $task->id) || $billedByTask->has($task->id)) {
                        $task->billed = (float) ($billedByTask[$task->id] ?? $billedByTask[(string) $task->id]);
                    }
                    $task->paid = true;
                    $task->is_active = false;
                    $task->save();
                }

                $efforts = $effortIds->isEmpty()
                    ? collect()
                    : Effort::query()->whereIn('id', $effortIds)->whereNull('task_id')->get();

                foreach ($efforts as $effort) {
                    if (! $projectIds->contains($effort->project_id)) {
                        throw ValidationException::withMessages([
                            'efforts' => 'Hay horas extra que no pertenecen al cliente',
                        ]);
                    }

                    if ($hoursByEffort->has((string) $effort->id) || $hoursByEffort->has($effort->id)) {
                        $hours = (float) ($hoursByEffort[$effort->id] ?? $hoursByEffort[(string) $effort->id]);
                        $effort->amount = (int) round($hours * 60);
                        $effort->save();
                    }
                }

                $taskBilled = $tasks->sum(fn (Task $task) => (float) $task->billed);
                $extraHours = $efforts->sum(fn (Effort $effort) => $effort->amount / 60);
                $billedHours = $taskBilled + $extraHours;

                $loadedHours = $tasks->sum(fn (Task $task) => $task->getEfforts()) / 60;
                $productivity = $loadedHours > 0 ? round(($billedHours / $loadedHours) * 100, 2) : 0;

                return Report::create([
                    'from' => $request->from,
                    'to' => $request->to,
                    'user_id' => $client->id,
                    'tasks' => $taskIds->implode(','),
                    'efforts' => $effortIds->implode(','),
                    'productivity' => $productivity,
                    'billed_hours' => $billedHours,
                    'rate' => $request->rate,
                    'detail' => $request->detail ?? '',
                ]);
            });
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()->back()->with('alert-danger', $e->getMessage())->withInput();
        }

        return redirect('reports/'.$report->id)->with('alert-success', 'Reporte creado con éxito');
    }

    /**
     * @return array{
     *     previewClient: User|null,
     *     previewTasks: Collection<int, Task>,
     *     previewEfforts: Collection<int, Effort>,
     *     previewProjects: Collection<int, Project>,
     *     clientTasks: Collection<int, Task>,
     *     defaultDetail: string
     * }
     */
    private function previewForClient(Request $request, string $startDate, string $endDate): array
    {
        $empty = [
            'previewClient' => null,
            'previewTasks' => collect(),
            'previewEfforts' => collect(),
            'previewProjects' => collect(),
            'clientTasks' => collect(),
            'defaultDetail' => '',
        ];

        if (! $request->filled('user_id')) {
            return $empty;
        }

        $client = User::query()->with(['role', 'projects'])->findOrFail($request->user_id);
        if (! $client->isClient()) {
            return $empty;
        }

        $from = Carbon::parse($startDate)->startOfDay();
        $to = Carbon::parse($endDate)->endOfDay();
        $projectIds = $client->projects()->pluck('projects.id');

        $previewTasks = Task::query()
            ->with([
                'project',
                'efforts' => fn ($query) => $query->orderBy('created_at')->orderBy('id'),
                'efforts.user.role',
            ])
            ->whereIn('project_id', $projectIds)
            ->where(function ($query) use ($from, $to) {
                $query->whereBetween('created_at', [$from, $to])
                    ->orWhereHas('efforts', function ($effortQuery) use ($from, $to) {
                        $effortQuery->whereBetween('created_at', [$from, $to]);
                    });
            })
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        $previewEfforts = Effort::query()
            ->with(['project'])
            ->whereIn('project_id', $projectIds)
            ->whereBetween('created_at', [$from, $to])
            ->whereNull('task_id')
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        $clientTasks = Task::query()
            ->with('project')
            ->whereIn('project_id', $projectIds)
            ->orderByDesc('id')
            ->get();

        return [
            'previewClient' => $client,
            'previewTasks' => $previewTasks,
            'previewEfforts' => $previewEfforts,
            'previewProjects' => $client->projects,
            'clientTasks' => $clientTasks,
            'defaultDetail' => $this->defaultDetailForPreview($previewTasks, $previewEfforts),
        ];
    }

    /**
     * @param  Collection<int, Task>  $previewTasks
     * @param  Collection<int, Effort>  $previewEfforts
     */
    private function defaultDetailForPreview(Collection $previewTasks, Collection $previewEfforts): string
    {
        $lines = [];

        foreach ($previewTasks as $task) {
            $lines[] = '- '.$task->getTitle().' ('.number_format($task->hoursToBill(), 2, '.', '').' h)';
            if ($task->description !== null && $task->description !== '') {
                $lines[] = $task->description;
            }
            foreach ($task->efforts as $effort) {
                $lines[] = $this->effortDetailLine($effort, '  - ');
            }
        }

        if ($previewEfforts->isNotEmpty()) {
            if ($lines !== []) {
                $lines[] = '';
            }
            $lines[] = 'Horas extra';
            foreach ($previewEfforts as $effort) {
                $lines[] = $this->effortDetailLine($effort);
            }
        }

        return implode("\n", $lines);
    }

    private function effortDetailLine(Effort $effort, string $prefix = '- '): string
    {
        $date = $effort->created_at?->format('d/m/Y') ?? '';
        $hours = number_format($effort->amount / 60, 2, '.', '');

        return $prefix.$effort->detail.' ('.$hours.' h) — '.$date;
    }

    /**
     * @return array{
     *     report: Report,
     *     tasks: Collection<int, Task>,
     *     efforts: Collection<int, Effort>,
     *     by_project: array<string, array<int, array<string, mixed>>>,
     *     projectName: string,
     *     periodFrom: string,
     *     periodTo: string,
     *     issuedAt: string
     * }
     */
    private function reportViewData(int $id): array
    {
        $report = Report::with('user.role')->findOrFail($id);
        if ((! isSenior()) && ($report->user_id != Auth::id())) {
            abort(401, 'No podes ver este reporte');
        }

        $taskIds = $this->idsFromList($report->tasks);
        $effortIds = $this->idsFromList($report->efforts);

        $tasks = $taskIds === []
            ? collect()
            : Task::query()->with(['project', 'items', 'efforts.user.role'])->whereIn('id', $taskIds)->get();

        $efforts = $effortIds === []
            ? collect()
            : Effort::query()->with(['project', 'task', 'user.role'])->whereIn('id', $effortIds)->get();

        $by_project = [];
        foreach ($efforts as $value) {
            $projectName = $value->project?->name ?? 'Sin proyecto';
            $by_project[$projectName][] = [
                'amount' => $value->amount,
                'detail' => $value->detail,
                'date' => $value->getDate(),
                'task_id' => $value->task_id,
                'title_task' => $value->task ? $value->task->getTitle() : '',
            ];
        }

        $projectName = $tasks->pluck('project.name')
            ->merge($efforts->pluck('project.name'))
            ->filter()
            ->unique()
            ->values()
            ->implode(' · ');

        if ($projectName === '') {
            $projectName = 'Sin proyecto';
        }

        return [
            'report' => $report,
            'tasks' => $tasks,
            'efforts' => $efforts,
            'by_project' => $by_project,
            'projectName' => $projectName,
            'periodFrom' => Carbon::parse($report->from)->format('d/m/Y'),
            'periodTo' => Carbon::parse($report->to)->format('d/m/Y'),
            'issuedAt' => now()->format('d/m/Y'),
        ];
    }

    private function logoDataUri(): string
    {
        $source = @imagecreatefrompng(public_path('trackerdev_negro.png'));
        if ($source === false) {
            return '';
        }

        $resized = imagescale($source, 500);
        imagedestroy($source);
        if ($resized === false) {
            return '';
        }

        ob_start();
        imagejpeg($resized, null, 90);
        $jpeg = (string) ob_get_clean();
        imagedestroy($resized);

        return 'data:image/jpeg;base64,'.base64_encode($jpeg);
    }

    private function watermarkDataUri(): string
    {
        $source = $this->composeWatermarkSource();
        $width = imagesx($source);
        $height = imagesy($source);

        $flat = imagecreatetruecolor($width, $height);
        imagefill($flat, 0, 0, imagecolorallocate($flat, 255, 255, 255));
        imagealphablending($flat, true);
        imagecopy($flat, $source, 0, 0, 0, 0, $width, $height);
        imagedestroy($source);

        $faded = imagecreatetruecolor($width, $height);
        imagefill($faded, 0, 0, imagecolorallocate($faded, 255, 255, 255));
        imagecopymerge($faded, $flat, 0, 0, 0, 0, $width, $height, 42);
        imagedestroy($flat);

        ob_start();
        imagejpeg($faded, null, 88);
        $jpeg = (string) ob_get_clean();
        imagedestroy($faded);

        return 'data:image/jpeg;base64,'.base64_encode($jpeg);
    }

    /**
     * @return \GdImage
     */
    private function composeWatermarkSource()
    {
        $composed = public_path('images/icon_td.png');
        if (is_file($composed)) {
            $image = @imagecreatefrompng($composed);
            if ($image !== false) {
                return $image;
            }
        }

        $size = 700;
        $canvas = imagecreatetruecolor($size, $size);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, 0, 0, 0, 127));
        imagealphablending($canvas, true);

        $center = (int) ($size / 2);
        imagefilledellipse($canvas, $center, $center, $size, $size, imagecolorallocate($canvas, 172, 34, 59));
        $inner = (int) round($size * 250 / 350);
        imagefilledellipse($canvas, $center, $center, $inner, $inner, imagecolorallocate($canvas, 219, 34, 34));

        $svg = (string) file_get_contents(public_path('images/icon_1.svg'));
        if (preg_match('/xlink:href="(data:image\/png;base64,[^"]+)"/', $svg, $matches)) {
            $logo = @imagecreatefromstring((string) file_get_contents($matches[1]));
            if ($logo !== false) {
                $logoWidth = (int) round($size * 212.402 / 350);
                $logoHeight = (int) round($size * 189.941 / 350);
                $scaled = imagecreatetruecolor($logoWidth, $logoHeight);
                imagealphablending($scaled, false);
                imagesavealpha($scaled, true);
                imagefill($scaled, 0, 0, imagecolorallocatealpha($scaled, 0, 0, 0, 127));
                imagecopyresampled($scaled, $logo, 0, 0, 0, 0, $logoWidth, $logoHeight, imagesx($logo), imagesy($logo));
                imagealphablending($canvas, true);
                imagecopy($canvas, $scaled, (int) round($size * 77 / 350), (int) round($size * 94 / 350), 0, 0, $logoWidth, $logoHeight);
                imagedestroy($logo);
                imagedestroy($scaled);
            }
        }

        return $canvas;
    }

    /**
     * @return array<int, int>
     */
    private function idsFromList(?string $list): array
    {
        if ($list === null || trim($list) === '') {
            return [];
        }

        return collect(explode(',', $list))
            ->map(fn ($id) => (int) trim($id))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
