<?php

namespace Vzool\Horizon\SupervisorCommands;

use Vzool\Horizon\Contracts\Terminable;

class Terminate
{
    /**
     * Process the command.
     *
     * @param  \Vzool\Horizon\Contracts\Terminable  $terminable
     * @param  array  $options
     * @return void
     */
    public function process(Terminable $terminable, array $options)
    {
        $terminable->terminate($options['status'] ?? 0);
    }
}
