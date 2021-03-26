import Controller from '@ember/controller'
import { computed } from '@ember/object'
import { inject as service } from '@ember/service'

export default Controller.extend({
	currentUser: service(),

	topFeaturedProducts: computed('products.[]', function () {
		return this.get('products').slice(0, 4);
	}),

	otherFeaturedProducts: computed('products.[]', function () {
		return this.get('products').slice(4);
	}),

    platinumSponsors: computed('sponsors.[]', function () {
        return this.get('sponsors').filter(function (sponsor) {
            return sponsor.get('platinum') == true;
        }).slice(0, 3);
    }),

    goldSponsors: computed('sponsors.[]', function () {
        return this.get('sponsors').filter(function (sponsor) {
            return sponsor.get('platinum') == false;
        }).slice(0, 12);
    }),
	
	hasMoreLink: computed('products.[]', function () {
		return (this.get('products.length') >= 10);
	}),

	actions: {
		goToProductDetail(productId)
		{
			this.transitionToRoute(this.r('products.show'), productId);
		}
	}
});
