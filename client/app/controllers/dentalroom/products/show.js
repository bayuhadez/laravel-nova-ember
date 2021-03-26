import Controller from '@ember/controller'
import { A } from '@ember/array'
import { isBlank } from '@ember/utils'
import { inject as service } from '@ember/service'
import { computed } from '@ember/object'
import $ from 'jquery';

export default Controller.extend({
    shoppingCart: service(),
    
    productSponsors: computed('product.seminarProductMeta.seminarProductSponsors.[]', function () {
        if (!isBlank(this.get('product.seminarProductMeta.id'))) {
            return this.get('product.seminarProductMeta.seminarProductSponsors').slice(0, 6);
        } else {
            return A();
        }
    }),

	actions: {

		showModal(selector) {
			$(selector).modal();
		},

		addProduct() {
			this.get('shoppingCart').add(this.get('product.id'));
			swal("Success", "Product Berhasil Di tambahkan" , "success");
		},

		buyProduct() {
			this.get('shoppingCart').add(this.get('product.id'));
			this.transitionToRoute(this.r('shopping-cart'));
		}
	}
});
