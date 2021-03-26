import Component from '@ember/component';
import { inject as service } from '@ember/service';
import { isBlank } from "@ember/utils"
import { validator, buildValidations } from 'ember-cp-validations';

const Validations = buildValidations({
	username: [
		validator('presence', {
			presence: true,
			message: 'Email tidak boleh kosong'
		}),
		validator('format', { 
			type: 'email',
			message: 'Format email salah'
		})
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
	]
});

export default Component.extend(Validations, {

	intl: service(),
	session: service(),

	init() {
		this._super(...arguments);
		this.setProperties({
			errorMessage: null,
			username: null,
			password: null,
		});
	},

	actions: {
		authenticate() {
			this.validate().then(({ validations }) => {
				if (validations.get('isValid')) {
					this.get('session').authenticate('authenticator:oauth2', this.get('username'), this.get('password'))
						.then((response) => {
							swal(
								this.intl.t('auth.success'),
								this.intl.t('auth.login success'),
								'success'
							);
						}, (err) => {
							if (!isBlank(err.message)) {
								swal(
									this.intl.t('auth.login failed'),
									this.intl.t('auth.'+err.message.toLowerCase()),
									'error'
								);
							}
						});
				}
			});
		}
	}
});
