<?php

namespace Vzool\Horizon\Listeners;

use Cake\Chronos\Chronos;
use Vzool\Horizon\Events\MasterSupervisorLooped;
use Vzool\Horizon\Contracts\SupervisorRepository;
use Vzool\Horizon\Contracts\MasterSupervisorRepository;

class ExpireSupervisors
{
    /**
     * Handle the event.
     *
     * @param  \Vzool\Horizon\Events\MasterSupervisorLooped  $event
     * @return void
     */
    public function handle(MasterSupervisorLooped $event)
    {
        app(MasterSupervisorRepository::class)->flushExpired();

        app(SupervisorRepository::class)->flushExpired();
    }
}
