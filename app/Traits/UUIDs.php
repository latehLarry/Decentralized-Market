<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UUIDs
{
	/**
	 * Generate UUID
	 * @return atribute
	 */
    protected static function bootUUIDs()
    {
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Changes the attribute of the eloquent
     * @return atribute
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Changes the attribute of the eloquent
     * @return atribute
     */
    public function getKeyType()
    {
        return 'string';
    }
}
