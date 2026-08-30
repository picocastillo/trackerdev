<?php

namespace App\Console\Commands;

use App\Services\DeveloperReportGenerator;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateDeveloperReportsCommand extends Command
{
    protected $signature = 'reports:generate-developers {from?} {to?}';

    protected $description = 'Generate automatic developer reports for a period (defaults to last month)';

    public function handle(DeveloperReportGenerator $generator): int
    {
        $from = $this->argument('from')
            ? Carbon::parse($this->argument('from'))->startOfDay()
            : now()->subMonthNoOverflow()->startOfMonth();

        $to = $this->argument('to')
            ? Carbon::parse($this->argument('to'))->endOfDay()
            : now()->subMonthNoOverflow()->endOfMonth();

        $count = $generator->generate($from, $to);

        $this->info("Created {$count} developer report(s) from {$from->toDateString()} to {$to->toDateString()}.");

        return self::SUCCESS;
    }
}
