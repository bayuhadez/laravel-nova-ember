<?php

namespace App\Observers;

use App\Models\Rack;
use App\Lib\Functions;

class RackObserver
{
	/**
	 * Handle the rack "creating" event.
	 *
	 * @param  \App\Models\Rack  $rack
	 * @return void
	 */
	public function creating(Rack $rack)
    {
        if (empty($rack->code)) {
            $rack->code = substr($rack->name, 0, 3) . Functions::randomNumber(3);
        }
	}
}
