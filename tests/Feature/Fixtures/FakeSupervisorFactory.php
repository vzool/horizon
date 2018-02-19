<?php

namespace Vzool\Horizon\Tests\Feature\Fixtures;

use Vzool\Horizon\Tests\Feature\Fakes\SupervisorWithFakeMonitor;

class FakeSupervisorFactory
{
    public $supervisor;

    public function make($options)
    {
        return $this->supervisor = new SupervisorWithFakeMonitor($options);
    }
}
