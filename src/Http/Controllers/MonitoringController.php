<?php

namespace Vzool\Horizon\Http\Controllers;

use Illuminate\Http\Request;
use Vzool\Horizon\Jobs\MonitorTag;
use Vzool\Horizon\Jobs\StopMonitoringTag;
use Vzool\Horizon\Contracts\JobRepository;
use Vzool\Horizon\Contracts\TagRepository;

class MonitoringController extends Controller
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
     * Create a new controller instance.
     *
     * @param  \Vzool\Horizon\Contracts\JobRepository  $jobs
     * @param  \Vzool\Horizon\Contracts\TagRepository  $tags
     * @return void
     */
    public function __construct(JobRepository $jobs, TagRepository $tags)
    {
        parent::__construct();

        $this->jobs = $jobs;
        $this->tags = $tags;
    }

    /**
     * Get all of the monitored tags and their job counts.
     *
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        return collect($this->tags->monitoring())->map(function ($tag) {
            return [
                'tag' => $tag,
                'count' => $this->tags->count($tag) + $this->tags->count('failed:'.$tag),
            ];
        })->sortBy('tag')->values();
    }

    /**
     * Paginate the jobs for a given tag.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $tag
     * @return array
     */
    public function paginate(Request $request, $tag)
    {
        $jobIds = $this->tags->paginate(
            $tag, $startingAt = $request->query('starting_at', 0),
            $request->query('limit', 25)
        );

        return [
            'jobs' => $this->getJobs($jobIds, $startingAt),
            'total' => $this->tags->count($tag),
        ];
    }

    /**
     * Get the jobs for the given IDs.
     *
     * @param  array  $jobIds
     * @param  int  $startingAt
     * @return \Illuminate\Support\Collection
     */
    protected function getJobs($jobIds, $startingAt = 0)
    {
        return $this->jobs->getJobs($jobIds, $startingAt)->map(function ($job) {
            $job->payload = json_decode($job->payload);

            return $job;
        })->values();
    }

    /**
     * Start monitoring the given tag.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function store(Request $request)
    {
        dispatch(new MonitorTag($request->tag));
    }

    /**
     * Stop monitoring the given tag.
     *
     * @param  string  $tag
     * @return void
     */
    public function destroy($tag)
    {
        dispatch(new StopMonitoringTag($tag));
    }
}
