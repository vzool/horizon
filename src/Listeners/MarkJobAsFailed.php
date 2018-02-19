<?php

namespace Vzool\Horizon\Listeners;

use Vzool\Horizon\Events\JobFailed;
use Vzool\Horizon\Contracts\JobRepository;

class MarkJobAsFailed
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
     * @param  \Vzool\Horizon\Events\JobFailed  $event
     * @return void
     */
    public function handle(JobFailed $event)
    {
        $this->jobs->failed(
            $event->exception, $event->connectionName,
            $event->queue, $event->payload
        );
    }
}
