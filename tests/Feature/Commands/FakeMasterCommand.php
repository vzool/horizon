<?php

namespace Vzool\Horizon\Tests\Feature\Commands;

use Vzool\Horizon\MasterSupervisor;

class FakeMasterCommand
{
    public $processCount = 0;
    public $master;
    public $options;

    public function process(MasterSupervisor $master, array $options)
    {
        $this->processCount++;
        $this->master = $master;
        $this->options = $options;
    }
}
