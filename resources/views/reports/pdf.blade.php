<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte {{ $report->id }}</title>
    <style>
        @page { margin: 28px 32px 40px; }
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1c1917;
            font-size: 11px;
            line-height: 1.45;
            background-image: url("{{ $watermark }}");
            background-repeat: no-repeat;
            background-position: center 180px;
            background-size: 360px 360px;
        }
        .header {
            border-bottom: 2px solid #5d161a;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }
        .brand img {
            width: 200px;
            height: auto;
            display: block;
            margin-bottom: 8px;
        }
        .meta {
            margin-top: 8px;
            color: #57534e;
        }
        .meta strong { color: #1c1917; }
        h1 {
            font-size: 16px;
            margin: 0 0 4px;
        }
        h2 {
            font-size: 12px;
            margin: 18px 0 8px;
            color: #5d161a;
            border-bottom: 1px solid #e7e5e4;
            padding-bottom: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            text-align: left;
            background: transparent;
            border-bottom: 1px solid #d6d3d1;
            padding: 6px 7px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        td {
            border-bottom: 1px solid #f5f5f4;
            padding: 6px 7px;
            vertical-align: top;
        }
        .right { text-align: right; }
        .muted { color: #78716c; }
        .box {
            margin-top: 10px;
            padding: 8px 10px;
            background: transparent;
            border: 1px solid #e7e5e4;
        }
        .totals {
            margin-top: 16px;
            text-align: right;
            font-size: 13px;
            font-weight: bold;
        }
        .footer {
            position: fixed;
            bottom: -18px;
            left: 0;
            right: 0;
            font-size: 9px;
            color: #78716c;
            border-top: 1px solid #e7e5e4;
            padding-top: 6px;
        }
        .detail {
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">
            <img src="{{ $logo }}" alt="TrackerDev">
        </div>
        <h1>Reporte #{{ $report->id }} — {{ $report->user->isClient() ? $projectName : $report->user->name }}</h1>
        <div class="meta">
            <strong>Período:</strong> {{ $periodFrom }} al {{ $periodTo }}
            &nbsp;·&nbsp;
            <strong>Fecha de emisión:</strong> {{ $issuedAt }}
        </div>
    </div>

    @if ($report->user->isClient())
        <h2>Tareas</h2>
        <table>
            <thead>
                <tr>
                    <th>Tarea</th>
                    <th>Proyecto</th>
                    <th class="right">Horas cargadas</th>
                    <th class="right">Horas facturadas</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tasks as $task)
                    <tr>
                        <td>{{ $task->getTitle() }}</td>
                        <td>{{ $task->project->name }}</td>
                        <td class="right">{{ number_format($task->getEfforts() / 60, 2) }} h</td>
                        <td class="right">{{ number_format((float) $task->billed, 2) }} h</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="muted">No hay tareas en este reporte</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($efforts->isNotEmpty())
            <h2>Horas extra</h2>
            <table>
                <thead>
                    <tr>
                        <th>Detalle</th>
                        <th>Proyecto / Tarea</th>
                        <th class="right">Horas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($efforts as $effort)
                        <tr>
                            <td>{{ $effort->detail }}</td>
                            <td>
                                {{ $effort->project->name ?? '' }}
                                @if ($effort->task)
                                    — {{ $effort->task->getTitle() }}
                                @else
                                    — Manual
                                @endif
                            </td>
                            <td class="right">{{ number_format($effort->amount / 60, 2) }} h</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if ($report->detail)
            <h2>Resumen</h2>
            <div class="box detail">{{ $report->detail }}</div>
        @endif

        <div class="totals">
            TOTAL ${{ number_format((float) $report->rate * (float) $report->billed_hours, 2) }}
        </div>
    @else
        <h2>Por proyecto</h2>
        @forelse ($by_project as $projectName => $items)
            <p><strong>{{ $projectName }}</strong></p>
            <table>
                <thead>
                    <tr>
                        <th>Detalle</th>
                        <th>Fecha</th>
                        <th class="right">Minutos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $effort)
                        <tr>
                            <td>
                                {{ $effort['detail'] }}
                                @if ($effort['task_id'])
                                    ({{ $effort['title_task'] }})
                                @else
                                    [Manual]
                                @endif
                            </td>
                            <td>{{ $effort['date'] }}</td>
                            <td class="right">{{ $effort['amount'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @empty
            <p class="muted">No hay esfuerzos en este reporte</p>
        @endforelse

        @if ($report->detail)
            <h2>Comentarios</h2>
            <div class="box detail">{{ $report->detail }}</div>
        @endif

        <div class="totals">
            TOTAL ${{ number_format((float) $report->rate * (float) $report->billed_hours, 2) }}
        </div>
    @endif

    <div class="footer">
        TrackerDev · Reporte #{{ $report->id }} · Emitido el {{ $issuedAt }} · Período {{ $periodFrom }} al {{ $periodTo }}
    </div>

</body>
</html>
