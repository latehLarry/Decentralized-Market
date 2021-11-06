<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use App\Traits\UUIDs;

class HelpRequest extends Model
{
    use HasFactory, UUIDs;

    /**
     * Get the owner of this help request
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Check if the help request is open or not
     * 
     * @return bool
     */
    public function status()
    {
    	if ($this->closed != false) {
    		return 'closed';
    	} else {
    		return 'open';
    	}
    }

    /**
     * Returns all messages pertaining to this help request
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
    	return $this->hasMany(HelpRequestReply::class, 'helprequest_id', 'id')->orderBy('created_at', 'DESC');
    }

    /**
     * Decrypt the help request title
     * 
     * @return Illuminate\Support\Facades\Crypt
     */
    public function decryptTitle()
    {
    	return Crypt::decryptString($this->title);
    }
}
