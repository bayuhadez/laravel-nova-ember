import Controller from '@ember/controller';
import ENV from 'rcf/config/environment';
import { inject as service } from '@ember/service';

export default Controller.extend({

	ajaxHelperService: service(),

	actions: {

		resetPassword(resetPasswordObject) {

			let self = this;
			let url = ENV.apiUrl + '/' + ENV.apiNamespace + '/profile/password/reset';
			let fd = new FormData();

			self.set('isProcessing', true);
			self.set('errorMessages', []);

			// set form data
			fd.append('password', resetPasswordObject.get('password'));
			fd.append('password_confirmation', resetPasswordObject.get('password_confirmation'));

			// create promise from ajax post
			let ajaxPromise = new Promise((resolve, reject) => {
				this.get('ajaxHelperService')
					.ajax({
						url: url,
						type: 'POST',
						data: fd,
						contentType: false,
						processData: false,
						success: function (response) {
							resolve(response);
						},
						error: function (jqXHR/*, textStatus, errorThrown*/) {
							reject(jqXHR);
						},
						beforeSend: function (/*xhr*/) {
							self.set('errorMessages', []);
						},
					});
			})
			.then((/*response*/) => {
				swal({
					title: "Reset Password Berhasil",
					icon: "success"
				}).then(() => {
					self.transitionToRoute(self.r('login'));
				});
			})
			.catch((/*reason*/) => {
				swal({
					title: "Gagal Reset Password",
					icon: "warning"
				});
			})
			.finally(() => {
				setTimeout(
					() => {
						self.set('isProcessing', false);
					},
					1500
				);
			});

		}
	}
});
