import Component from '@ember/component';
import { computed } from '@ember/object';

export default Component.extend({
	streamSrc: computed('streamKey', function () {
		return this.get('videoPath');
	}),

	didInsertElement()
	{
		let overrideNative = false;

		let player = videojs('playback', {
			controls: true,
			autoplay: false,
			preload: 'auto',
			responsive: true,
			fluid: true,
			html5: {
				hls: {
					overrideNative: overrideNative
				},
				nativeVideoTracks: !overrideNative,
				nativeAudioTracks: !overrideNative,
				nativeTextTracks: !overrideNative
			}
		});
		this.set('player', player);
		document.addEventListener('contextmenu', event => event.preventDefault());
	},

	didDestroyElement()
	{
		this.get('player').dispose();
	}
});
