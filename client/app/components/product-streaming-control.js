import Component from '@ember/component';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';

export default Component.extend({
	productService: service(),

	sessionStarted: computed(
		'product.seminarProductMeta.isSessionInProgress',
		function () {
			return this.get('product.seminarProductMeta.isSessionInProgress');
		}
	),

	streamKey: computed(
		'product.seminarProductMeta.streamKey',
		function () {
			return this.get('product.seminarProductMeta.streamKey');
		}
	),

	didRender() {
		var self = this;

		// current user is owner of the seminar
		this
			.get('productService')
			.productIsMine(this.get('product.id'))
			.then(function (response) {
				self.set('productIsMine', response);
			});
	},


});
