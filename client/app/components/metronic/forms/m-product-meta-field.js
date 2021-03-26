import Component from '@ember/component';
import { inject as service } from '@ember/service';
import { computed } from '@ember/object';

export default Component.extend({
    store: service(),
    pageTitle: computed(function () {
        return (this.productMetaField.isNew ? "product_meta_field.heading.add" : "product_meta_field.heading.edit");
    }),

    didReceiveAttrs()
    {
        let productMetaFieldGroups = this.store.findAll('product-meta-field-group');
        this.set('productMetaFieldGroups', productMetaFieldGroups);
    },

    actions: {
        addProductMetaFieldGroup(name)
        {
            return this.store.createRecord('product-meta-field-group', {
                name: name
            }).save();
        },
    }
});
