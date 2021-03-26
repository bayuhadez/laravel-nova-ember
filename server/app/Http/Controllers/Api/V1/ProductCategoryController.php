<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategory as ProductCategoryResource;
use App\Models\ProductCategory;

class ProductCategoryController extends Controller
{
	public function index()
	{
		return ProductCategoryResource::collection(
            ProductCategory::all()
        );
	}

	public function show($id)
	{
		return new ProductCategoryResource(ProductCategory::find($id));
	}

	public function showProducts($id)
	{
		//return ProductResource::collection(Company::find($id)->products);
    }
    
    public function showOrderProducts($id)
    {
        //$company = Company::with(['productCategories'])->find($id);
        //return new OrderProductResource($company);
    }
}
