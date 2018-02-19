<?php

namespace Vzool\Horizon\Contracts;

interface WorkloadRepository
{
    /**
     * Get the current workload of each queue.
     *
     * @return array
     */
    public function get();
}
