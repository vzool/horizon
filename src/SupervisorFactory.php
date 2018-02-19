<?php

namespace Vzool\Horizon;

class SupervisorFactory
{
    /**
     * Create a new supervisor instance.
     *
     * @param  \Vzool\Horizon\SupervisorOptions  $options
     * @return \Vzool\Horizon\Supervisor
     */
    public function make(SupervisorOptions $options)
    {
        return new Supervisor($options);
    }
}
