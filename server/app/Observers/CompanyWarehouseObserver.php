<?php

namespace App\Observers;

use App\Models\CompanyWarehouse;

class CompanyWarehouseObserver
{
    /**
     * Handle the company warehouse "created" event.
     *
     * @param  \App\CompanyWarehouse  $companyWarehouse
     * @return void
     */
    public function created(CompanyWarehouse $companyWarehouse)
    {
        //
    }

    /**
     * Handle the company warehouse "updated" event.
     *
     * @param  \App\CompanyWarehouse  $companyWarehouse
     * @return void
     */
    public function updated(CompanyWarehouse $companyWarehouse)
    {
        //
    }

    /**
     * Handle the company warehouse "deleted" event.
     *
     * @param  \App\CompanyWarehouse  $companyWarehouse
     * @return void
     */
    public function deleted(CompanyWarehouse $companyWarehouse)
    {
        $warehouse = $companyWarehouse->warehouse;

        // check if this was the last CompanyWarehouse record, delete Warehouse also
        if ($warehouse->companyWarehouses->count() == 0) {
            $warehouse->delete();
        }
    }

    /**
     * Handle the company warehouse "restored" event.
     *
     * @param  \App\CompanyWarehouse  $companyWarehouse
     * @return void
     */
    public function restored(CompanyWarehouse $companyWarehouse)
    {
        //
    }

    /**
     * Handle the company warehouse "force deleted" event.
     *
     * @param  \App\CompanyWarehouse  $companyWarehouse
     * @return void
     */
    public function forceDeleted(CompanyWarehouse $companyWarehouse)
    {
        //
    }
}
