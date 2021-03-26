import Component from '@ember/component';
import { inject as service } from '@ember/service';

export default Component.extend({
    store: service(),

    didReceiveAttrs()
    {
        // fetch product categories
        this.store.findAll('product-category').then((productCategories) => {
            this.set('childProductCategories', productCategories);
        });
    },

    actions: {
        addProductCategory(value)
        {
            return this.store.createRecord('product-category', {
                name: value,
            }).save();
        },
    }
});
