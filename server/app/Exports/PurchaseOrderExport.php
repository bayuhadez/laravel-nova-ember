<?php

namespace App\Exports;

use App\Models\Product;
use App\Services\OrderService;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class PurchaseOrderExport implements FromCollection
{
	use Exportable;

	public $orderService;

	public function __construct(Product $product)
	{
		$this->orderService = new OrderService;
		$this->product = $product;
	}

	public function collection()
	{
		return $this
			->orderService
			->generatePurchaseOrderReportForProduct($this->product);
	}
}
