<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;

class FixIsActiveFlagForCurrenciesThatAreUsedBySystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = (
        'fix:is-active-flag-for-currencies-that-are-used-by-system '.
        '{--d|debug : Will product debugging log}'.
        '{--p|pretend : True will avoid the process}'
    );

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set true for is_active flag for currencies that are used by this system';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $currencyIds = [
            122, // IDR, INDONESIA
            214, // SGD, SINGAPORE
            253, // USD, UNITED STATES OF AMERICA
        ];

        Currency::whereIn('id', $currencyIds)
            ->update(['is_active' => true]);

        Currency::whereNotIn('id', $currencyIds)
            ->update(['is_active' => false]);
    }
}
