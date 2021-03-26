import Component from '@ember/component';
import EmberObject from '@ember/object';
import { validator, buildValidations } from 'ember-cp-validations';

const ResetPasswordValidations = buildValidations({
	email: [
		validator('presence', {
			presence: true,
			message: 'Email tidak boleh kosong'
		}),
		validator('format', {
			type: 'email',
			message: 'Format email salah',
			debounce: 1000,
		}),
		//validator('email-available', { debounce: 300 })
	],
	password: [
		validator('presence', {
			presence: true,
			message: 'Password tidak boleh kosong'
		}),
		validator('length', {
			min: 8,
			message: 'Password minimal 8 karakter'
		})
	],
	password_confirmation: [
		validator('presence', {
			presence: true,
			message: 'Konfirmasi password tidak boleh kosong'
		}),
		validator('length', {
			min: 8,
			message: 'Konfirmasi password minimal 8 karakter'
		}),
		validator('confirmation', {
			on: 'password',
			message: 'Konfirmasi password tidak sama'
		})
	],
});

export default Component.extend(ResetPasswordValidations, {

	// attributes:
	email: null,
	isEmailInputHidden: false,

	init() {
		this._super(...arguments);

		// input properties [
		this.setProperties({
			password: null,
			password_confirmation: null,
			captchaValidated: false,
			errorMessages: [],
			shouldCheckEmail: false,
		});
		// ]
	},

	actions: {
		resetPassword() {
			let self = this;

			// validate input value
			this.validate().then(({ validations }) => {

				if (validations.get('isValid')) {

					let resetPasswordObj = EmberObject.create({
						email: self.get('email'),
						password: self.get('password'),
						password_confirmation: self.get('password_confirmation'),
					});

					// passing action to controller
					self.onSubmit(resetPasswordObj);
				}

			});
		},

	},

});
