<?php

namespace Vzool\Horizon\Listeners;

use Vzool\Horizon\Events\JobsMigrated;
use Vzool\Horizon\Contracts\JobRepository;

class MarkJobsAsMigrated
{
    /**
     * The job repository implementation.
     *
     * @var \Vzool\Horizon\Contracts\JobRepository
     */
    public $jobs;

    /**
     * Create a new listener instance.
     *
     * @param  \Vzool\Horizon\Contracts\JobRepository  $jobs
     * @return void
     */
    public function __construct(JobRepository $jobs)
    {
        $this->jobs = $jobs;
    }

    /**
     * Handle the event.
     *
     * @param  \Vzool\Horizon\Events\JobsMigrated  $event
     * @return void
     */
    public function handle(JobsMigrated $event)
    {
        $this->jobs->migrated($event->connectionName, $event->queue, $event->payloads);
    }
}
