<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transition extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $keyType = 'string';
    public $primaryKey = 'user_id';
}
