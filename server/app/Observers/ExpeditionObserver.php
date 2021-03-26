<?php

namespace App\Observers;

use App\Models\Expedition;

class ExpeditionObserver
{
    /**
     * Handle the expedition "created" event.
     *
     * @param  \App\Models\expedition  $expedition
     * @return void
     */
    public function creating(Expedition $expedition)
    {
        if ($expedition->code ==  null) { 
            $expedition->code = rand(10, 9999);
        }
    }
}
