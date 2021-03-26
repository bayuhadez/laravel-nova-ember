<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;
use URL;

class SeminarProductMeta extends Model
{
	public static $fileDirectoryPrefixBroadcastVideo = 'SeminarProductMeta/BroadcastVideo';
	public static $fileDirectoryPrefixPlaybackVideo = 'SeminarProductMeta/PlaybackVideo';
	public static $fileDirectoryPrefixPowerpoint = 'SeminarProductMeta/Powerpoint';
	public static $fileDirectoryPrefixTmp = 'SeminarProductMeta/tmp';

    protected $table = 'seminar_product_metas';

    protected $casts = [
        'is_past' => 'boolean',
        'is_session_in_progress' => 'boolean',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

	protected $fillable = [
		'broadcast_video_path',
		'playback_video_file_path',
		'powerpoint_file_path',
	];

	public function scopeUpcoming($q)
	{
		$q->where('is_past', false);
	}

	public function product()
	{
		return $this->belongsTo('App\Models\Product');
    }

	public function speaker()
	{
		return $this->belongsTo('App\Models\User', 'speaker_user_id');
	}

    public function seminarProductSponsors()
    {
        return $this->hasMany('App\Models\SeminarProductSponsor', 'seminar_product_meta_id');
    }

	/**
	 * start the seminar by marking it as session in progress and playing the
	 * broadcast_video_path file if available
	 */
	public function start()
	{
		$this->is_session_in_progress = true;
		$this->save();

		if (!empty($this->broadcast_video_path)) {
			$command ="ffmpeg -re -i ". Storage::url($this->broadcast_video_path) .
				" -codec copy -f flv rtmp://web/app/{$this->stream_key}";

			exec($command . ' > /dev/null 2>/dev/null &');
		}
	}

	public function getPlaybackVideoUrlAttribute()
	{
		if (!empty($this->playback_video_file_path)) {
			return Storage::temporaryUrl(
				$this->playback_video_file_path,
				now()->addHours(5)
			);
		}

		return null;
	}

}
