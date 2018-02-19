<?php

namespace Vzool\Horizon\SupervisorCommands;

use Vzool\Horizon\Supervisor;

class Scale
{
    /**
     * Process the command.
     *
     * @param  \Vzool\Horizon\Supervisor  $supervisor
     * @param  array  $options
     * @return void
     */
    public function process(Supervisor $supervisor, array $options)
    {
        $supervisor->scale($options['scale']);
    }
}
