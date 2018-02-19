<?php

namespace Vzool\Horizon\Listeners;

use Vzool\Horizon\Events\JobReserved;
use Vzool\Horizon\Contracts\JobRepository;

class MarkJobAsReserved
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
     * @param  \Vzool\Horizon\Events\JobReserved  $event
     * @return void
     */
    public function handle(JobReserved $event)
    {
        $this->jobs->reserved($event->connectionName, $event->queue, $event->payload);
    }
}
