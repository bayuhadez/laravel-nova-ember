<?php

namespace App\Observers;

use App\Models\Voucher;

class VoucherObserver
{
    public function saving(Voucher $voucher)
    {
        $currentProfile = auth()->user();

        if (
            !empty($currentProfile)
            && !$currentProfile->hasRole('superadministrator')
        ) {
            // get first companies
            $companyId = $currentProfile->companies->first()->id;
            $voucher->company_id = $companyId;
        }
    }

    /**
     * Handle the company "created" event.
     *
     * @param  \App\Models\Voucher  $voucher
     * @return void
     */
    public function created(Voucher $voucher)
    {
        //
    }

    /**
     * Handle the company "updated" event.
     *
     * @param  \App\Models\Voucher  $voucher
     * @return void
     */
    public function updated(Voucher $voucher)
    {
        //
    }

    /**
     * Handle the company "deleted" event.
     *
     * @param  \App\Models\Voucher  $voucher
     * @return void
     */
    public function deleted(Voucher $voucher)
    {
        //
    }

    /**
     * Handle the company "restored" event.
     *
     * @param  \App\Models\Voucher  $voucher
     * @return void
     */
    public function restored(Voucher $voucher)
    {
        //
    }

    /**
     * Handle the company "force deleted" event.
     *
     * @param  \App\Models\Voucher  $voucher
     * @return void
     */
    public function forceDeleted(Voucher $voucher)
    {
        //
    }
}
