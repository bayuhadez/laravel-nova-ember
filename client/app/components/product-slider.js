import Component from '@ember/component';
import $ from 'jquery';
import { inject as service } from '@ember/service';
import { computed } from '@ember/object';

export default Component.extend({

	shoppingCart: service(),

	init() {
		this._super(...arguments);
		this.set('products', this.get('products'));
	},

	// rearrange products data per 4 chunks
	productChunks: computed('products.[]', function () {
		let productChunks = [];
		let chunk = 5;
		let products = this.get('products');

		for (let index = 0; index < products.length; index+=chunk) {
			productChunks.pushObject(products.slice(index, index + chunk));
		}

		productChunks.forEach(function (productChunk) {

			// if productChunks doesnt reach the chunk size, fill the rest of the
			// space with empty elements for UI purposes
			if (productChunk.get('length') < chunk) {

				for (let i = productChunk.get('length'); i < chunk; i++) {
					productChunk.pushObject({});
				}

			}

		});

		return productChunks;
	}),

	didInsertElement() {
		this._super(...arguments);
		this.$('.carousel-item').first().addClass('active');
	},

	actions: {
		addProduct(product) {
			this.get('shoppingCart').add(product.get('id'));
			swal("Success", "Product Berhasil Di Tambahkan Ke Keranjang" , "success");
		},
	}
});
