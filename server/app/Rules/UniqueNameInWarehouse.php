<?php

namespace App\Rules;

use App\Models\Rack as Rack;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class UniqueNameInWarehouse implements Rule
{
    public $warehouseId;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($warehouseId)
    {
        $this->warehouseId = $warehouseId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $sameCompanyRacks = Rack::where('warehouse_id', $this->warehouseId)->get();
        $sameNameRacks = $sameCompanyRacks->where('name', $value);
        return !($sameNameRacks == [] || is_null($sameNameRacks));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Nama sudah digunakan';
    }
}