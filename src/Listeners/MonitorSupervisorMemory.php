<?php

namespace Vzool\Horizon\Listeners;

use Vzool\Horizon\Events\SupervisorLooped;

class MonitorSupervisorMemory
{
    /**
     * Handle the event.
     *
     * @param  \Vzool\Horizon\Events\SupervisorLooped  $event
     * @return void
     */
    public function handle(SupervisorLooped $event)
    {
        $supervisor = $event->supervisor;

        if ($supervisor->memoryUsage() > $supervisor->options->memory) {
            $supervisor->terminate(12);
        }
    }
}
