<?php

namespace App\Observers;
use App\Models\Warehouse;
use App\Lib\Functions;

class WarehouseObserver
{
    /**
     * Handle the warehouse "created" event.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return void
     */
    public function creating(Warehouse $warehouse)
    {
        if (empty($warehouse->code)) {
            $warehouse->code = substr($warehouse->name, 0, 3) . Functions::randomNumber(3);
        }
    }
}
