<?php

namespace Vzool\Horizon\Listeners;

use Vzool\Horizon\Events\JobDeleted;
use Vzool\Horizon\Contracts\JobRepository;
use Vzool\Horizon\Contracts\TagRepository;

class MarkJobAsComplete
{
    /**
     * The job repository implementation.
     *
     * @var \Vzool\Horizon\Contracts\JobRepository
     */
    public $jobs;

    /**
     * The tag repository implementation.
     *
     * @var \Vzool\Horizon\Contracts\TagRepository
     */
    public $tags;

    /**
     * Create a new listener instance.
     *
     * @param  \Vzool\Horizon\Contracts\JobRepository  $jobs
     * @param  \Vzool\Horizon\Contracts\TagRepository  $tags
     * @return void
     */
    public function __construct(JobRepository $jobs, TagRepository $tags)
    {
        $this->jobs = $jobs;
        $this->tags = $tags;
    }

    /**
     * Handle the event.
     *
     * @param  \Vzool\Horizon\Events\JobDeleted  $event
     * @return void
     */
    public function handle(JobDeleted $event)
    {
        $this->jobs->completed($event->payload, $event->job->hasFailed());

        if (! $event->job->hasFailed() && count($this->tags->monitored($event->payload->tags())) > 0) {
            $this->jobs->remember($event->connectionName, $event->queue, $event->payload);
        }
    }
}
