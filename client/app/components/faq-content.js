import Component from '@ember/component';
import { inject as service } from '@ember/service';
import $ from 'jquery';

export default Component.extend({

	store: service(),
	isAnswerDisplayed: false,
	isProcessing: false,

	init() {
		this._super(...arguments);
		this.set('isAnswerDisplayed', false);
		this.set('isProcessing', false);
	},

	actions: {
		toggleAnswer() {

			let self = this;

			if (self.get('isAnswerDisplayed') === true) {

				// then hide the answer
				self.set('isAnswerDisplayed', false);
				self.$('#faq-body-' + self.get('faq.id')).collapse('hide');

			} else {

				// get the answer if it don't have the answer
				self.set('isProcessing', true);

				return this.store.findRecord('faq', self.get('faq.id'))
					.then(function (faq) {

						self.set('isAnswerDisplayed', true);

						// then show the answer
						self.$('#faq-body-' + faq.get('id')).collapse('show');

					})
					.catch(() => {
						alert('asdf');
					})
					.finally(function () {
						self.toggleProperty('isProcessing');
					});
			}
		}

	},
});
