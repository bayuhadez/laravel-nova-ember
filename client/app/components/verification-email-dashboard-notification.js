import Component from '@ember/component';
import ENV from 'rcf/config/environment';
import { inject as service } from '@ember/service';

export default Component.extend({

	ajaxHelperService: service(),
	intl: service(),
	verificationEmailAlert: service(),

	user: null,

	actions: {
		resendVerificationEmail()
		{
			// variables
			let self = this;
			let url = ENV.apiUrl + '/' + ENV.apiNamespace + '/auth/email/resend';

			// create promise from ajax post
			let resendVerificationEmail = new Promise((resolve, reject) => {
				this.get('ajaxHelperService')
					.ajax({
						type: 'GET',
						url: url,
						contentType: false,
						processData: false,
						success: function (response) {
							resolve(response);
						},
						error: function (jqXHR/*, textStatus, errorThrown*/) {
							reject(jqXHR);
						},
					});
			})
			.then(function (response) {
				swal({
					type: "success",
					icon: "success",
					text: self.intl.t(response),
				});
			})
			.catch(function (reason) {
				swal({
					type: "error",
					icon: "error",
					text: self.intl.t(reason.responseJSON),
				});
			});

			resendVerificationEmail.finally(function (/*reason*/) {
			});

		},

		hideAlert()
		{
			this.get('verificationEmailAlert').hide();
		},
	}
});
