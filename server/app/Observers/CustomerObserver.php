<?php

namespace App\Observers;

use App\Lib\Functions;
use App\Models\Customer;

class CustomerObserver
{
    /**
     * Handle the customer "creating" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function creating(Customer $customer)
    {
        if (empty($customer->code)) {
            $customer->code = Functions::generateCode(
                10, true, $customer->getTable(), 'code'
            );
        }
    }

}
