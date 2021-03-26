import Controller from '@ember/controller';
import ENV from 'rcf/config/environment';
import $ from 'jquery';
import { inject as service } from '@ember/service';
import { validator, buildValidations } from 'ember-cp-validations';

const ForgotPasswordValidations = buildValidations({
	email: [
		validator('presence', {
			presence: true,
			message: 'Email tidak boleh kosong'
		}),
		validator('format', {
			type: 'email',
			message: 'Format email salah'
		})
	],
});

export default Controller.extend(ForgotPasswordValidations, {

	// attributes
	ajaxHelperService: service(),

	init() {
		this._super(...arguments);

		this.setProperties({
			errorMessages: [],
			captchaValidated: false,
			isProcessing: false,
		});
	},

	actions: {
		requestResetPassword: function () {

			// variables
			let self = this;

			// show loading spinner
			$('.spinner-loader-overlay').css('display', 'block');

			if (self.get('captchaValidated') == false) {
				alert('Please validate reCaptcha')
				return;
			}

			this.validate().then(({ validations }) => {
				if (validations.get('isValid') && !self.get('isProcessing')) {

					let url = ENV.apiUrl + '/' + ENV.apiNamespace + '/auth/password/request';
					let fd = new FormData();
					self.set('isProcessing', true);

					// set form data
					fd.append('email', self.get('email'));

					// create promise from ajax post
					let ajaxPromise = new Promise((resolve, reject) => {
						this.get('ajaxHelperService')
							.ajaxWithoutAuth({
								type: 'POST',
								url: url,
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
					});

					ajaxPromise.then((/*response*/) => {
						swal({
							title: "Reset Password Link",
							text: "Reset Password Link berhasil dikirim ke "+self.get('email'),
							icon: "success"
						}).then(() => {
							self.transitionToRoute(self.r('dashboard'));
						});
					})
					.catch((/*reason*/) => {
						swal({
							title: "Gagal Mengirim Reset Password Link",
							text: "Tidak dapat mengirim email atau email tidak terdaftar",
							icon: "warning"
						});
					})
					.finally(() => {
						setTimeout(
							() => {
								self.set('isProcessing', false);
								$('.spinner-loader-overlay').css('display', 'none');
							},
							1500
						);
					});
				}
			});

			return false;
		},

		onCaptchaResolved(reCaptchaResponse) {
			this.set('captchaValidated', true);
		},

		onCaptchaExpired() {
			this.set('captchaValidated', false);
		}
	},
});

