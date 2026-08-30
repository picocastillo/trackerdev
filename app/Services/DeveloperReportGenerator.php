<?php

namespace App\Services;

use App\Models\Effort;
use App\Models\Report;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DeveloperReportGenerator
{
    /**
     * Create one report per developer that has unpaid efforts in the period
     * and does not already have a report covering those dates.
     */
    public function generate(Carbon $from, Carbon $to): int
    {
        $created = 0;

        User::query()
            ->developers()
            ->with('role')
            ->orderBy('id')
            ->each(function (User $user) use ($from, $to, &$created) {
                if ($this->generateFor($user, $from, $to)) {
                    $created++;
                }
            });

        return $created;
    }

    public function generateFor(User $user, Carbon $from, Carbon $to): ?Report
    {
        $periodStart = $from->copy()->startOfDay();
        $periodEnd = $to->copy()->endOfDay();

        $latest = Report::query()
            ->where('user_id', $user->id)
            ->orderByDesc('id')
            ->first();

        if ($latest && Carbon::parse($latest->to)->gte($periodStart->toDateString())) {
            return null;
        }

        $efforts = $user->efforts()
            ->where('paid', false)
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->get();

        if ($efforts->isEmpty()) {
            return null;
        }

        $effortTaskIds = $efforts->pluck('task_id')->filter()->unique()->values();

        $assignedTasks = Task::query()
            ->with(['efforts.user.role'])
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->get();

        $effortTasks = $effortTaskIds->isEmpty()
            ? collect()
            : Task::query()->with(['efforts.user.role'])->whereIn('id', $effortTaskIds)->get();

        $tasks = $assignedTasks->merge($effortTasks)->unique('id')->values();

        $productivity = 0.0;
        if ($tasks->isNotEmpty()) {
            $sum = 0.0;
            foreach ($tasks as $task) {
                $loaded = $task->getEfforts();
                if ($loaded != 0 && $task->estimation) {
                    $sum += ($task->estimation * 60 / $loaded) * 100;
                }
            }
            $productivity = $sum / $tasks->count();
        }

        $billedHours = $efforts->sum('amount') / 60;

        return DB::transaction(function () use ($user, $periodStart, $periodEnd, $tasks, $efforts, $productivity, $billedHours) {
            $report = Report::create([
                'from' => $periodStart->toDateString(),
                'to' => $periodEnd->toDateString(),
                'user_id' => $user->id,
                'tasks' => $tasks->pluck('id')->implode(','),
                'efforts' => $efforts->pluck('id')->implode(','),
                'productivity' => $productivity,
                'billed_hours' => $billedHours,
                'rate' => 0,
                'detail' => '',
            ]);

            Effort::query()->whereIn('id', $efforts->pluck('id'))->update(['paid' => true]);

            return $report;
        });
    }
}
