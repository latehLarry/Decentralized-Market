<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Traits\UUIDs;

class Notice extends Model
{
    use HasFactory, UUIDs;

    /**
     * Returns the user who posted the news
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the latest five news
     * 
     * @return App\Models\Notices
     */
    public static function latest()
    {
   	    return self::orderBy('created_at', 'DESC')->limit(5)->get();
   	}

    /**
     * Get the date of creation of the news
     * 
     * @return Carbon\Carbon
     */
   	public function createdAt()
   	{
   		   return Carbon::parse($this->created_at)->diffForHumans();
   	}

    /**
     * Get the news update date
     * 
     * @return Carbon\Carbon
     */
   	public function updatedAt()
   	{
   		   return Carbon::parse($this->updated_at)->diffForHumans();
   	}
}
