import DS from 'ember-data';

export default DS.Model.extend({
	address: DS.attr('string'),
	email: DS.attr('string'),
	firstName: DS.attr('string'),
	lastName: DS.attr('string'),
	password: DS.attr('string'),
	passwordConfirmation: DS.attr('string'),
	phone: DS.attr('string'),
	registrationCertificateNumber: DS.attr('string'), // Surat Tanda Registrasi (STR)
	token: DS.attr('string'),
});
