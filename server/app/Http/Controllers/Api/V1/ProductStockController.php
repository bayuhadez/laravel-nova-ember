<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\CompanyProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Response;

class ProductStockController extends Controller
{
    /**
     * get product stock(s) that belongs to a product, add up quantity of product stocks with the same stock division,
     * and compose for non-duplicate table content
     * 
     * @param $request
     * @return array
     */
    public function getInProductByStockDivision(Request $request)
    {
        $user = auth()->user();
        $results = [];
        $productId = $request['productId'];
        $companyProducts = CompanyProduct::with([
            'company' => function ($q) {
                $q->with([
                    'stockDivisions' => function ($q) {
                        $q->with([
                            'productStocks'
                        ]);
                    }
                ]);
            }
        ])->where('product_id', '=', $productId)->get();

        foreach ($companyProducts as $cp) {
            $company = $cp->company;
            $prevCompanyName = '';
            foreach ($company->stockDivisions as $stockDivision) {
                $companyName = $company->name . ' - ' . $company->divisionName;
                $targetProductStocks = $stockDivision->productStocks->filter(function($ps) use ($productId) {
                    return $ps->product_id == $productId;
                });
                $results[] = [
                    'companyName' => $prevCompanyName != $companyName ? $companyName : '',
                    'stockDivisionName' => $stockDivision->name,
                    'quantity' => $targetProductStocks->sum('quantity'),
                    'tempQuantity' => 0,
                    'stockDivisionId' => $stockDivision->id
                ];

                $prevCompanyName = $companyName;
            }
        }
        return $results;
    }
}
