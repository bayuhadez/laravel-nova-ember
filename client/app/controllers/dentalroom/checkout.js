import Controller from '@ember/controller'
import { inject as service } from '@ember/service'
import { isBlank } from '@ember/utils'

export default Controller.extend({
	currentUser: service(),
	orderService: service(),

	paymentResult: null,
	isProcessing: false,

	showPaymentResultModal(paymentResult) {

		let self = this;
		let error = false;

		if (!isBlank(paymentResult)) {

			let statusCode = paymentResult.status_code;
			let firstCharCode = null;

			if (typeof statusCode === 'string') {
				firstCharCode = statusCode.charAt(0);
			} else {
				firstCharCode = statusCode.toString().charAt(0);
			}

			if (
				!(firstCharCode == 4 || firstCharCode == 5) &&
				!isBlank(firstCharCode)
			) {
				swal({
					title: self.order.get('paymentId'),
					text: paymentResult.status_message,
					icon: "success"
				}).then(() => {
					self.transitionToRoute(self.r('dashboard'));
				});

				return true;
			}

			error = true;
		}

		if (error) {
			swal({
				title: self.order.get('paymentId'),
				text: paymentResult.status_message,
				icon: "warning"
			});
		}
	},

	actions: {
		pay() {
			let self = this;
			let isLoggedIn = !isBlank(this.get('currentUser.user'));

			// skip if still in process
			if (self.get('isProcessing')) {
				return false;
			}

			// set flag isProcessing
			self.set('isProcessing', true);

			// if guest user, redirect to login
			if (!isLoggedIn) {
				this.transitionToRoute(self.r('login'));
			} else {
				// process the payment
				this.get('orderService')
					.getTokenPayment(self.get('order.id'))
					.then(function (token) {
						// snap payment
						snap.pay(token, {
							onSuccess: function(result){
								self.set('paymentResult', result);
								self.showPaymentResultModal(result);
							},
							onPending: function(result){
								self.set('paymentResult', result);
								self.showPaymentResultModal(result);
							},
							onError: function(result){
								self.set('paymentResult', result);
								self.showPaymentResultModal(result);
							}
						});
					}).finally(function () {
						self.set('isProcessing', false);
					});
			}
		},
	}
});
