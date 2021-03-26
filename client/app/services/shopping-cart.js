import Service from '@ember/service';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';
import { storageFor } from 'ember-local-storage';

export default Service.extend({

	store: service(),

	productIds: storageFor('shopping-cart'),

	init() {
		this._super(...arguments)
	},

	// Computed Properties [
	products: computed('productIds.[]', function() {
		if (this.get('productIds.length') == 0) {
			return [];
		} else {
			return this.get('store').query('product', {
				filter: {
					ids: this.get('productIds.content').join()
				}
			});
		}
	}),

	totalPrice: computed('products.[]', function () {
		let total_price = 0;
		this.products.forEach((item) => {
			total_price += item.price
		})
		return total_price
	}),

	totalProduct: computed('productIds.[]', function () {
		return this.get('productIds.length'); 
	}),
	// ]

	add(productId) {
		this.get('productIds').addObject(productId);
	},

	remove(productId) {
		this.get('productIds').removeObject(productId);
	},

	clear() {
		this.get('productIds').clear();
	},

	isItemExists(productId) {
		return (this.get('productIds').indexOf(productId) !== -1);
	},

});
