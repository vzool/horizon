<?php

namespace Vzool\Horizon\Tests\Feature;

use Mockery;
use Cake\Chronos\Chronos;
use Vzool\Horizon\Supervisor;
use Illuminate\Support\Facades\Event;
use Vzool\Horizon\WaitTimeCalculator;
use Vzool\Horizon\Tests\IntegrationTest;
use Vzool\Horizon\Events\LongWaitDetected;
use Vzool\Horizon\Events\SupervisorLooped;
use Vzool\Horizon\Listeners\MonitorWaitTimes;
use Vzool\Horizon\Contracts\MetricsRepository;
use Illuminate\Contracts\Redis\Factory as RedisFactory;

class MonitorWaitTimesTest extends IntegrationTest
{
    public function test_queues_with_long_waits_are_found()
    {
        Event::fake();

        $redis = Mockery::mock(RedisFactory::class);
        $redis->shouldReceive('setnx')->with('monitor:time-to-clear', 1)->andReturn(1);
        $redis->shouldReceive('expire')->with('monitor:time-to-clear', 60);

        $calc = Mockery::mock(WaitTimeCalculator::class);
        $calc->shouldReceive('calculate')->andReturn([
            'redis:test-queue' => 10,
            'redis:test-queue-2' => 80,
        ]);
        $this->app->instance(WaitTimeCalculator::class, $calc);

        $listener = new MonitorWaitTimes(resolve(MetricsRepository::class), $redis);
        $listener->lastMonitoredAt = Chronos::now()->subDays(1);

        $listener->handle(new SupervisorLooped(Mockery::mock(Supervisor::class)));

        Event::assertDispatched(LongWaitDetected::class, function ($event) {
            return $event->connection == 'redis' && $event->queue == 'test-queue-2';
        });
    }
}
