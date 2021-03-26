import Component from '@ember/component';
import EmberObject from '@ember/object';
import { validator, buildValidations } from 'ember-cp-validations';
import { computed } from '@ember/object';

const Validations = buildValidations({
	email: [
		validator('presence', {
			presence: true,
			message: 'Email tidak boleh kosong'
		}),
		validator('format', {
			type: 'email',
			message: 'Format email salah'
		}),
		validator('email-available', { 
			disabled: computed.not('model.shouldValidateEmail'),
			debounce: 300 
		})
	],
    first_name: validator('presence', { 
        presence: true,
        message: 'Nama depan tidak boleh kosong'
    }),
    last_name: validator('presence', {
        presence: true,
        message: 'Nama belakang tidak boleh kosong'
    }),
    address: validator('presence', {
        presence: true,
        message: 'Alamat tidak boleh kosong'
    }),
    phone: validator('presence', {
        presence: true,
        message: 'Nomor telephone tidak boleh kosong'
    })
});

export default Component.extend(Validations, {

    init() {
        this._super(...arguments);
		let user = this.get('user');
		let person = this.get('person');
        // input properties [
        this.setProperties({
			email: user.get('email'),
			first_name: person.get('first_name'),
			last_name: person.get('last_name'),
			address: person.get('address'),
			phone: person.get('phone'),
			errorMessages: []
        });
        // ]
    },

	shouldValidateEmail: computed('email', function() {
		if (this.get('email') === this.get('user.email')) {
			return false;
		} else {
			return true;
		}
	}),

    actions: {
        updateProfile() {
            var self = this;
            // validate input value
            this.validate().then(({ validations }) => {

                if (validations.get('isValid')) {

					let profile = EmberObject.create();

					profile.setProperties({
						user_id: self.get('user.id'),
						email: self.get('email'),
						first_name: self.get('first_name'),
						last_name: self.get('last_name'),
						address: self.get('address'),
						phone: self.get('phone')
					});

					this.onSubmit(profile);
                }

            });
        },
    }
});
