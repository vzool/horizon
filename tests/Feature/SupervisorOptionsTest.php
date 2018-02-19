<?php

namespace Vzool\Horizon\Tests\Feature;

use Vzool\Horizon\SupervisorOptions;
use Vzool\Horizon\Tests\IntegrationTest;

class SupervisorOptionsTest extends IntegrationTest
{
    public function test_default_queue_is_used_when_null_is_given()
    {
        $options = new SupervisorOptions('name', 'redis');
        $this->assertSame('default', $options->queue);
    }
}
