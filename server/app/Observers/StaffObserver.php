<?php

namespace App\Observers;
use App\Models\Staff;

class StaffObserver
{
    /**
     * Handle the staff "created" event.
     *
     * @param  \App\Models\Staff  $staff
     * @return void
     */
    public function creating(Staff $staff)
    {
        if ($staff->code ==  null) { 
            $staff->code = rand(10, 9999);
        }
    }
}
