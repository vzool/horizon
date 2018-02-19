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
        resolve(MasterSupervisorRepository::class)->flushExpired();

        resolve(SupervisorRepository::class)->flushExpired();
    }
}
