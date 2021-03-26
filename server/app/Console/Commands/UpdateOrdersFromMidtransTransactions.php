<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Services\MidtransService;

class UpdateOrdersFromMidtransTransactions extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'order:update-from-midtrans-transaction
		{--order=* : The ID of order}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update Order from midtrans transaction';

	private $midtransService;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(MidtransService $midtransService)
	{
		parent::__construct();

		$this->midtransService = $midtransService;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$orderIds = $this->option('order');

		$orderBuilder = Order::whereRaw('1=1');

		if (!empty($orderIds)) {
			$orderBuilder->whereIn('id', $orderIds);
		}

		$orders = $orderBuilder->get();

		$bar = $this->output->createProgressBar($orders->count());

		$bar->start();

		foreach ($orders as $order) {

			try {

				$this
					->midtransService
					->updateOrderBasedOnPaymentGatewayOrder($order->paymentId);

			} catch (\Exception $e) {
				$this->error($e->getMessage());
			} catch (Exception $e) {
				$this->error($e->getMessage());
			}

			$bar->advance();
		}

		$bar->finish();
	}

}
