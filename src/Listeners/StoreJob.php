<?php

namespace Vzool\Horizon\Listeners;

use Vzool\Horizon\Events\JobPushed;
use Vzool\Horizon\Contracts\JobRepository;

class StoreJob
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
     * @param  \Vzool\Horizon\Events\JobPushed  $event
     * @return void
     */
    public function handle(JobPushed $event)
    {
        $this->jobs->pushed(
            $event->connectionName, $event->queue, $event->payload
        );
    }
}
