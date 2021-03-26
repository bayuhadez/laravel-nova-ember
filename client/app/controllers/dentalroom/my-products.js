import Controller from '@ember/controller';
import { computed } from '@ember/object';

export default Controller.extend({
	upcomingProducts: computed('products.[]', function () {
		return this.get('products').filter(function (product) {
			return !product.get('seminarProductMeta.isPast');
		});
	}),

	pastProducts: computed('products.[]', function () {
		return this.get('products').filter(function (product) {
			return product.get('seminarProductMeta.isPast');
		});
	}),
});
