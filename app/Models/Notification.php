<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUIDs;

class Notification extends Model
{
    use HasFactory, UUIDs;

    /**
     * Mark notification as read
     * 
     * @return App\Models\Notification
     */
    public static function markNotificationAsRead()
    {
        #Get auth user
        $user = auth()->user();

    	return self::where('user_id', $user->id)
                    ->where('read', false)
                    ->update(['read' => true]);
    }
}
