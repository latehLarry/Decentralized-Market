<?php

namespace App\Traits;

use App\Models\Notification;

trait NotificationTrait
{
    /**
     * Create a notification
     * @param  $user
     * @param  $label
     * @param  $link 
     * 
     * @return App\Models\Notification
     */
    private function createNotification($user,$label,$link)
    {
        $notification = new Notification();
        $notification->user_id = $user;
        $notification->label = $label;
        $notification->link = $link;
        $notification->save();
    }
}
