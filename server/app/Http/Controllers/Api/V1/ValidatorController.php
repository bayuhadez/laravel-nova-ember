<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Rack;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class ValidatorController extends Controller
{
    public function checkRackCodeUnique(Request $request)
    {
        $isValid = true;
        $code = $request->get('code');

        if (!empty($code)) {
            $rack = Rack::where('code', '=', $code)->first();
            if (!empty($rack)) {
                $isValid = false;
            }
        }

        return response()->json(['isValid' => $isValid]);
    }

    public function checkRackNameUniqueInWarehouse(Request $request)
    {
        $isValid = true;
        $warehouse = Warehouse::find($request->get('warehouse_id'));
        $name = $request->get('name');

        if (!empty($name) && !empty($warehouse)) {
            $rack = Rack::where('name', '=', $name)
                ->where('warehouse_id', '=', $warehouse->id)
                ->first();

            if (!empty($rack)) {
                $isValid = false;
            }
        }

        return response()->json(['isValid' => $isValid]);
    }
}
