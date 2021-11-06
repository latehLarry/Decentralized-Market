<?php

namespace App\Tools\Facades;

use Illuminate\Support\Facades\Facade;

class MonerodFacade extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'monerod';
	}
}