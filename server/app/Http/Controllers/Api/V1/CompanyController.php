<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Company as CompanyResource;
use App\Http\Resources\OrderProduct as OrderProductResource;
use App\Http\Resources\Product as ProductResource;
use App\Models\Company;

class CompanyController extends Controller
{
	public function index()
	{
		return CompanyResource::collection(Company::all());
	}

	public function show($id)
	{
		return new CompanyResource(Company::find($id));
	}

    /*
	public function showProducts($id)
	{
		return ProductResource::collection(Company::find($id)->products);
    }
    
    public function showOrderProducts($id)
    {
        $company = Company::with(['productCategories'])->find($id);
        return new OrderProductResource($company);
    }
    */
}
