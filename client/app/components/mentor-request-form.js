import Component from '@ember/component';
import { computed } from '@ember/object';
import { validator, buildValidations } from 'ember-cp-validations';

const Validations = buildValidations({
    fullName: validator('presence', true),
    expiryDate: validator('presence', true),
    number: validator('presence', true),
    photo: validator('presence', true)
});

export default Component.extend(Validations, {

	errorMessages: [],

	init() {
		this._super(...arguments);
		this.setProperties({
			expiryDate: null,
			fullName: null,
			number: null,
			photo: null,
			files: [],
		});
	},

	/** computed property: **/
	hasErrorMessage: computed('errorMessages.[]', function() {
		return this.get('errorMessages.length') > 0;
	}),

	actions: {
		submitRequest() {
			var self = this;

			this.validate().then(({ validations }) => {

				if (validations.get('isValid')) {
					var license = this.get('license');

					// set registrar
					license.setProperties({
						fullName: this.get('fullName'),
						expiryDate: this.get('expiryDate'),
						number: this.get('number'),
						photo: this.get('files').get('lastObject'),
					});

					// passing action to controller
					self.onSubmit(license);

				}
			});
		},
		// https://www.npmjs.com/package/ember-cli-form-data
		// https://www.artmann.co/articles/image-uploads-with-ember-js
		addFile(event) {
			var self = this;
			var files = event.target.files;
			self.get('files').pushObject(files[0]);
		}
	},

});
