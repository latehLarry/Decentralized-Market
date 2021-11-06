<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Traits\UUIDs;

class DisputeMessage extends Model
{
    use HasFactory, UUIDs;

    /**
     * Get the user who owns the message
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    /**
     * Decrypt message
     * 
     * @return Illuminate\Support\Facades\Crypt
     */
    public function decryptMessage()
    {
        return Crypt::decryptString($this->message);
    }

    /**
     * Shows the date the message is created
     * 
     * @return Carbon\Carbon
     */
    public function creationDate()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }
}
