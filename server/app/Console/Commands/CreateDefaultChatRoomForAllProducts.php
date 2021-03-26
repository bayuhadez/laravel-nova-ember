<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class CreateDefaultChatRoomForAllProducts extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'command:create-default-chat-room-for-all-products';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates a default chat room for all products that has no chat room yet';

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
		$products = Product::with('chatRooms')->get();

		foreach ($products as $product) {
			if ($product->chatRooms->isEmpty()) {
				$product->chatRooms()->create();
			}
		}
	}
}
