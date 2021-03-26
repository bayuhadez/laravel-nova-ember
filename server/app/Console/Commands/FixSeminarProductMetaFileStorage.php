<?php

namespace App\Console\Commands;

use App\Models\SeminarProductMeta;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class FixSeminarProductMetaFileStorage extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = (
		'fix:seminar-product-meta-file-storage '.
		'{--d|debug : Will product debugging log}'.
		'{--p|pretend : True will avoid the process}'
	);

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Fix storage of SeminarProductMeta files (Powerpoint and Videos)';

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
		$seminarProductMetas = SeminarProductMeta::
			where(function ($q) {
				$q->whereNotNull('powerpoint_file_path');
				$q->orWhereNotNull('playback_video_file_path');
				$q->orWhereNotNull('broadcast_video_path');
			})
			->get();

		$this->line('Seminar Product Meta');
		$this->info('count : '.$seminarProductMetas->count());

		foreach ($seminarProductMetas as $seminarProductMeta) {

			// 1. Powerpoint
			if (!empty($seminarProductMeta->powerpoint_file_path)) {

				$file = basename($seminarProductMeta->powerpoint_file_path);

				$existsInCloud = Storage::disk(config('filesystems.cloud'))
					->exists(
						$seminarProductMeta::$fileDirectoryPrefixPowerpoint.'/'.
						$seminarProductMeta->id.'/'.
						$file
					);

				if (!$existsInCloud) {

					$existsInPublic = Storage::disk('public')
						->exists($seminarProductMeta->powerpoint_file_path);

					if (!$existsInPublic && $existsInLocal && $this->option('debug')) {
						$this->info('Seminar Product Meta Powerpoint : '.$seminarProductMeta->id);
						$this->info('File exist in local but not exists in cloud');
						$this->info('local : '.$seminarProductMeta->powerpoint_file_path);
					}

					// move to cloud
					if (!$this->option('pretend') && $existsInPublic) {

						$sourceFullPath = Storage::disk('public')->path($seminarProductMeta->powerpoint_file_path);

						Storage::disk('s3')->putFileAs(
							$seminarProductMeta::$fileDirectoryPrefixPowerpoint.'/'.$seminarProductMeta->id,
							new File($sourceFullPath),
							basename($seminarProductMeta->powerpoint_file_path)
						);
					}
				}

			}


			// 2. Playback video
			$exists = Storage::disk(config('filesystems.cloud'))
				->exists($seminarProductMeta->playback_video_file_path);

			if (!$exists) {
				$existsInLocal = Storage::disk(config('filesystems.default'))
					->exists($seminarProductMeta->playback_video_file_path);

				if (!$exists && $existsInLocal && $this->option('debug')) {
					$this->info('Seminar Product Meta Playback Video : '.$seminarProductMeta->id);
					$this->info('File exist in local but not exists in cloud');
					$this->info('local : '.$seminarProductMeta->powerpoint_file_path);
				}

				// move to cloud
				if (!$this->option('pretend') && $existsInLocal) {
					/*
					Storage::move(
						$seminarProductMeta->playback_video_file_path,
						$seminarProductMeta::$fileDirectoryPrefixPlaybackVideo.'/'.$seminarProductMeta->id
					);
					 */
				}
			}

			// 3. Broadcast video
			$exists = Storage::disk(config('filesystems.cloud'))
				->exists($seminarProductMeta->broadcast_video_path);

			if (!$exists) {
				$existsInLocal = Storage::disk(config('filesystems.default'))
					->exists($seminarProductMeta->broadcast_video_path);

				if (!$exists && $existsInLocal && $this->option('debug')) {
					$this->info('Seminar Product Meta Broadcast Video : '.$seminarProductMeta->id);
					$this->info('File exist in local but not exists in cloud');
					$this->info('local : '.$seminarProductMeta->powerpoint_file_path);
				}

				// move to cloud
				if (!$this->option('pretend') && $existsInLocal) {
					/*
					Storage::move(
						$seminarProductMeta->broadcast_video_path,
						$seminarProductMeta::$fileDirectoryPrefixBroadcastVideo.'/'.$seminarProductMeta->id
					);
					 */
				}
			}
		}
	}
}
