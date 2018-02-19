<?php

namespace Vzool\Horizon\Tests\Feature\Fakes;

use Exception;
use Vzool\Horizon\Supervisor;

class SupervisorThatThrowsException extends Supervisor
{
    /**
     * Persist information about this supervisor instance.
     *
     * @return void
     * @throws \Exception
     */
    public function persist()
    {
        throw new Exception;
    }
}
