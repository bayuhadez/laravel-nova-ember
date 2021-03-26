<?php

namespace App\Nova\Actions;

use App\Exports\PurchaseOrderExport;
use App\Models\Product;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Http\Requests\ActionRequest;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportPurchaseOrderProduct extends DownloadExcel
{
	/**
	 * Indicates if this action is only available on the resource detail view.
	 *
	 * @var bool
	 */
	public $onlyOnDetail = true;

	/**
	 * @param ActionRequest $request
	 * @param Action        $exportable
	 *
	 * @return array
	 */
	public function handle(ActionRequest $request, Action $exportable): array
	{
		$product = Product::find($request->resources);

		$this->withFilename("purchase_order_report-{$product->id}.xlsx");

		$response = Excel::download(
			//$exportable,
			new PurchaseOrderExport($product),
			$this->getFilename(),
			$this->getWriterType()
		);

		if (!$response instanceof BinaryFileResponse || $response->isInvalid()) {
			return \is_callable($this->onFailure)
				? ($this->onFailure)($request, $response)
				: Action::danger(__('Resource could not be exported.'));
		}

		return \is_callable($this->onSuccess)
			? ($this->onSuccess)($request, $response)
			: Action::download(
				$this->getDownloadUrl($response),
				$this->getFilename()
			);
	}

}
