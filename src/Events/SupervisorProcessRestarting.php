<?php

namespace Vzool\Horizon\Events;

use Vzool\Horizon\SupervisorProcess;

class SupervisorProcessRestarting
{
    /**
     * The supervisor process instance.
     *
     * @var \Vzool\Horizon\SupervisorProcess
     */
    public $process;

    /**
     * Create a new event instance.
     *
     * @param  \Vzool\Horizon\SupervisorProcess  $process
     * @return void
     */
    public function __construct(SupervisorProcess $process)
    {
        $this->process = $process;
    }
}
