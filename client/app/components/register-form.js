import Component from '@ember/component';
import { validator, buildValidations } from 'ember-cp-validations';

const Validations = buildValidations({
	first_name: validator('presence', {
		presence: true,
		message: 'Nama depan tidak boleh kosong'
	}),
	last_name: validator('presence', {
		presence: true,
		message: 'Nama belakang tidak boleh kosong'
	}),
	email: [
		validator('presence', {
			presence: true,
			message: 'Email tidak boleh kosong'
		}),
		validator('format', {
			type: 'email',
			message: 'Format email salah'
		}),
		validator('email-available', { debounce: 300 })
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
			description: 'Konfirmasi password',
		}),
		validator('length', {
			min: 8,
			description: 'Konfirmasi password',
		}),
		validator('confirmation', {
			on: 'password',
			message: 'Konfirmasi password tidak sama'
		})
	],
	address: validator('presence', {
		presence: true,
		message: 'Alamat tidak boleh kosong'
	}),
	phone: {
		description: 'Nomor telephone',
		validators: [
			validator('presence', {
				presence: true
			}),
			validator('length', {
				min: 8,
				max: 18,
			}),
			validator('format', {
				regex: /^([(+]*[0-9]+[()+. -]*)$/
			})
		],
	},
	registration_certificate_number: {
		description: 'STR',
		validators: [
			validator('presence', {
				presence: true,
				ignoreBlank: false,
			}),
			validator('length', {
				min: 8,
				max: 20
			}),
			validator('number', {
				allowString: true,
				integer: true,
				message: '{description} harus berupa number'
			}),
			validator('format', {
				regex: /^[0-9]*$/,
				message: '{description} harus berupa number'
			})
		],
	},
});

export default Component.extend(Validations, {

	init() {

		this._super(...arguments);

		// input properties [
		this.setProperties({
			errorMessages: [],
			registration_certificate_number: null,
			address: null,
			captchaValidated: false,
			email: null,
			first_name: null,
			last_name: null,
			password: null,
			password_confirmation: null,
			phone: null,
		});
		// ]
	},

	actions: {
		register() {
			let self = this;

			if (self.get('captchaValidated') == false) {
				alert('Please validate reCaptcha')
				return;
			}

			// validate input value
			self.validate().then(({ validations }) => {

				if (validations.get('isValid')) {

					let registrar = self.get('registrar');

					// set registrar
					registrar.setProperties({
						address: self.get('address'),
						email: self.get('email'),
						firstName: self.get('first_name'),
						lastName: self.get('last_name'),
						password: self.get('password'),
						passwordConfirmation: self.get('password_confirmation'),
						phone: self.get('phone'),
						registrationCertificateNumber: self.get('registration_certificate_number'),
					});

					// passing action to controller
					self.onSubmit(registrar);

				}

			});
		},

		onCaptchaResolved(reCaptchaResponse) {
			this.set('captchaValidated', true);
		},

		onCaptchaExpired() {
			this.set('captchaValidated', false);
		}
	}
});
