import Component from '@ember/component';
import { isBlank } from '@ember/utils';

export default Component.extend({
	didRender() {
		let startTime = this.get('to');

		if (!isBlank(startTime)) {
			// Set the date we're counting down to
			var countDownDate = startTime.getTime();

			var self = this;

			// Get todays date and time
			let now = new Date().getTime();

			// Find the distance between now and the count down date
			let distance = countDownDate - now;

			self.timerStep(distance);

			// Update the count down every 1 second
			var x = setInterval(function() {
				// Get todays date and time
				let now = new Date().getTime();

				// Find the distance between now and the count down date
				let distance = countDownDate - now;

				self.timerStep(distance);

				// If the count down is finished, write some text
				if (distance < 0) {
					clearInterval(x);
					this.$(".timer").html("EXPIRED");
				}
			}, 1000);
		}
	},

	timerStep(distance) {

		var e = this.$('.timer');

		if (!isBlank(e)) {
			// Time calculations for days, hours, minutes and seconds
			var days = Math.floor(distance / (1000 * 60 * 60 * 24));
			var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
			var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
			var seconds = Math.floor((distance % (1000 * 60)) / 1000);

			// Display the result in the element with id="demo"
			this.$(".timer").html(days + "d " + hours + "h "
				+ minutes + "m " + seconds + "s ");
		}
	}
});
