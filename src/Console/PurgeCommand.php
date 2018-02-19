<?php

namespace Vzool\Horizon\Console;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Vzool\Horizon\MasterSupervisor;
use Vzool\Horizon\ProcessInspector;
use Vzool\Horizon\Contracts\ProcessRepository;
use Vzool\Horizon\Contracts\SupervisorRepository;
use Vzool\Horizon\Contracts\MasterSupervisorRepository;

class PurgeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'horizon:purge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Terminate any rogue Horizon processes';

    /**
     * Execute the console command.
     *
     * @param  \Vzool\Horizon\Contracts\MasterSupervisorRepository  $masters
     * @param  \Vzool\Horizon\Contracts\SupervisorRepository  $supervisors
     * @param  \Vzool\Horizon\Contracts\ProcessRepository  $processes
     * @return void
     */
    public function handle(MasterSupervisorRepository $masters,
                           SupervisorRepository $supervisors,
                           ProcessRepository $processes)
    {
        foreach ($masters->names() as $master) {
            if (Str::startsWith($master, MasterSupervisor::basename())) {
                $this->purge($master, $supervisors, $processes);
            }
        }
    }

    /**
     * Purge any orphan processes.
     *
     * @param  string  $master
     * @param  \Vzool\Horizon\Contracts\SupervisorRepository  $supervisors
     * @param  \Vzool\Horizon\Contracts\ProcessRepository  $processes
     * @return void
     */
    public function purge($master,
                          SupervisorRepository $supervisors,
                          ProcessRepository $processes)
    {
        $this->recordOrphans($master, $processes);

        $expired = $processes->orphanedFor(
            $master, $supervisors->longestActiveTimeout()
        );

        collect($expired)->each(function ($processId) use ($master, $processes) {
            $this->comment("Killing Process: {$processId}");

            exec("kill {$processId}");

            $processes->forgetOrphans($master, [$processId]);
        });
    }

    /**
     * Record the orphaned Horizon processes.
     *
     * @param  string  $master
     * @param  \Vzool\Horizon\Contracts\ProcessRepository  $processes
     * @return void
     */
    protected function recordOrphans($master, ProcessRepository $processes)
    {
        $processes->orphaned(
            $master, $orphans = app(ProcessInspector::class)->orphaned()
        );

        foreach ($orphans as $processId) {
            $this->info("Observed Orphan: {$processId}");

            if (function_exists('posix_kill')) {
                posix_kill($processId, SIGTERM);
            }
        }
    }
}
