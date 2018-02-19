<?php

namespace Vzool\Horizon\Tests\Feature\Fixtures;

use Vzool\Horizon\SupervisorProcess;

class SupervisorProcessWithFakeRestart extends SupervisorProcess
{
    public $wasRestarted = false;

    public function restart()
    {
        $this->wasRestarted = true;
    }
}
