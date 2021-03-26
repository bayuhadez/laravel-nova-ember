import Component from '@ember/component';
import { inject as service } from '@ember/service'
import $ from 'jquery';

export default Component.extend({
	shoppingCart: service(),

	actions: {
		addProduct() {
			this.send('addProduct');
		},

		buyProduct() {
			this.send('buyProduct');
		},

		showModal(selector) {
			$(selector).modal();
		},
	}
});
