<?php

namespace Vzool\Horizon\Listeners;

use Vzool\Horizon\Lock;
use Vzool\Horizon\Horizon;
use Illuminate\Support\Facades\Notification;

class SendNotification
{
    /**
     * Handle the event.
     *
     * @param  mixed  $event
     * @return void
     */
    public function handle($event)
    {
        $notification = $event->toNotification();

        if (! resolve(Lock::class)->get('notification:'.$notification->signature(), 300)) {
            return;
        }

        Notification::route('slack', Horizon::$slackWebhookUrl)
                    ->route('nexmo', Horizon::$smsNumber)
                    ->route('mail', Horizon::$email)
                    ->notify($notification);
    }
}
