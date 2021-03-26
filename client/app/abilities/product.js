import { Ability } from 'ember-can';
import { inject } from '@ember/service';
import { reads } from '@ember/object/computed';
import { computed } from '@ember/object';

export default Ability.extend({
	productService: inject(),

	canStream: computed('model.id', 'model.seminarProductMeta.isSessionInProgress', function () {

		// current user has purchased the seminar
		let isPurchased = this
			.get('productService')
			.productIsPurchased(this.get('model.id'))
			.then(function (response) {
				return response;
			});

		// current user is owner of the seminar
		let isMine = this
			.get('productService')
			.productIsMine(this.get('model.id'))
			.then(function (response) {
				return response;
			});

		// semianr is started
		var isStarted = this
			.get('model.seminarProductMeta')
			.then(function (seminarProductMeta) {
				return seminarProductMeta.get('isSessionInProgress');
			});

		return Ember.RSVP.all([isPurchased, isMine, isStarted]).then(function (responses) {
			return (responses[0] || responses[1]) && responses[2];
		});
	}),

	canBroadcast: computed('model.id', function () {

		// current user is owner of the seminar
		let isMine = this
			.get('productService')
			.productIsMine(this.get('model.id'))
			.then(function (response) {
				return response;
			});

		return isMine;
	}),
});
