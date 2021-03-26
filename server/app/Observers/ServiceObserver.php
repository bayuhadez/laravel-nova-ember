<?php

namespace App\Observers;
use App\Models\Service;

class ServiceObserver
{
    /**
     * Handle the service "created" event.
     *
     * @param  \App\Models\Service $service
     * @return void
     */
    public function creating(Service $service)
    {
        if ($service->code ==  null) { 
            $service->code = rand(10, 9999);
        }
    }
}
