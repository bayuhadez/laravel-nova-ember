<?php

namespace App\Observers;

use App\Models\SeminarProductMeta;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SeminarProductMetaObserver
{
	public function creating(SeminarProductMeta $seminarProductMeta)
	{
		$seminarProductMeta->stream_key = Str::random(32);
	}

	/**
	 * Handle the seminar product meta "created" event.
	 *
	 * @param  \App\SeminarProductMeta  $seminarProductMeta
	 * @return void
	 */
	public function created(SeminarProductMeta $seminarProductMeta)
	{
		$updatedData = [];

		// move BroadcastVideo file from tmp directory
		// to SeminarProductMeta/BroadcastVideo/{id} path
		// and update broadcast_video_path
		if (Str::contains(
			$seminarProductMeta->broadcast_video_path,
			$seminarProductMeta::$fileDirectoryPrefixTmp
		)) {

			$fromPath = $seminarProductMeta->broadcast_video_path;
			$toPath = (
				$seminarProductMeta::$fileDirectoryPrefixBroadcastVideo.'/'.
				$seminarProductMeta->id.
				Str::after(
					$seminarProductMeta->broadcast_video_path,
					$seminarProductMeta::$fileDirectoryPrefixTmp
				)
			);

			Storage::disk(config('filesystems.cloud'))
				->move($fromPath, $toPath);

			$updatedData['broadcast_video_path'] = $toPath;
		}

		// move PlaybackVideo file from tmp directory
		// to SeminarProductMeta/PlaybackVideo/{id} path
		// and update playback_video_file_path
		if (Str::contains(
			$seminarProductMeta->playback_video_file_path,
			$seminarProductMeta::$fileDirectoryPrefixTmp
		)) {

			$fromPath = $seminarProductMeta->playback_video_file_path;
			$toPath = (
				$seminarProductMeta::$fileDirectoryPrefixPlaybackVideo.'/'.
				$seminarProductMeta->id.
				Str::after(
					$seminarProductMeta->playback_video_file_path,
					$seminarProductMeta::$fileDirectoryPrefixTmp
				)
			);

			Storage::disk(config('filesystems.cloud'))
				->move($fromPath, $toPath);

			$updatedData['playback_video_file_path'] = $toPath;
		}

		// move powerpoint_file_path file from tmp directory
		// to SeminarProductMeta/Powerpoint/{id} path
		// and update powerpoint_file_path
		if (Str::contains(
			$seminarProductMeta->powerpoint_file_path,
			$seminarProductMeta::$fileDirectoryPrefixTmp
		)) {

			$fromPath = $seminarProductMeta->powerpoint_file_path;
			$toPath = (
				$seminarProductMeta::$fileDirectoryPrefixPowerpoint.'/'.
				$seminarProductMeta->id.
				Str::after(
					$seminarProductMeta->powerpoint_file_path,
					$seminarProductMeta::$fileDirectoryPrefixTmp
				)
			);

			Storage::disk(config('filesystems.cloud'))
				->move($fromPath, $toPath);

			$updatedData['powerpoint_file_path'] = $toPath;
		}

		$seminarProductMeta->update($updatedData);
	}

	/**
	 * Handle the seminar product meta "updated" event.
	 *
	 * @param  \App\SeminarProductMeta  $seminarProductMeta
	 * @return void
	 */
	public function updated(SeminarProductMeta $seminarProductMeta)
	{
		//
	}

	/**
	 * Handle the seminar product meta "deleted" event.
	 *
	 * @param  \App\SeminarProductMeta  $seminarProductMeta
	 * @return void
	 */
	public function deleted(SeminarProductMeta $seminarProductMeta)
	{
		//
	}

	/**
	 * Handle the seminar product meta "restored" event.
	 *
	 * @param  \App\SeminarProductMeta  $seminarProductMeta
	 * @return void
	 */
	public function restored(SeminarProductMeta $seminarProductMeta)
	{
		//
	}

	/**
	 * Handle the seminar product meta "force deleted" event.
	 *
	 * @param  \App\SeminarProductMeta  $seminarProductMeta
	 * @return void
	 */
	public function forceDeleted(SeminarProductMeta $seminarProductMeta)
	{
		//
	}
}
