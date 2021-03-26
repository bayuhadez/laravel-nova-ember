import Component from '@ember/component';
import ENV from 'rcf/config/environment';
import { computed } from '@ember/object';

export default Component.extend({
	streamSrc: computed('streamKey', function () {
		return ENV.apiUrl+ "/storage/live/"+this.get('streamKey')+"/index.m3u8";
	}),

	didInsertElement()
	{
		let player = videojs('stream-video', {
			controls: true,
			autoplay: true,
			preload: 'auto',
			responsive: true,
			fluid: true,
		});

		this.set('player', player);

		document.addEventListener('contextmenu', event => event.preventDefault());
	},

	didDestroyElement()
	{
		this.get('player').dispose();
	}
});
