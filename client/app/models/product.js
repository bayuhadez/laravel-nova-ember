import DS from 'ember-data'
import { inject } from '@ember/service'
import { computed } from '@ember/object'
import { isBlank } from '@ember/utils'

export default DS.Model.extend({

    shoppingCart: inject(),
    numberFormatter: inject(),

    // attributes [
    code: DS.attr('string'),
    barcode: DS.attr('string'),
    image: DS.attr('string'),
    thumbnailImage: DS.attr('string'),
    name: DS.attr('string'),
    status: DS.attr('number'),
    description: DS.attr('string'),
    price: DS.attr('number'),
    basePrice: DS.attr('number'),
    sku: DS.attr('string'),
    createdAt: DS.attr('date'),
    updatedAt: DS.attr('date'),
    imageFile: DS.attr('string'),
    stock : DS.attr('number', {defaultValue: 0}),
    minimumStock: DS.attr('number'),
    // ]

    // relationship [
    productBanner: DS.belongsTo(),
    productCategories: DS.hasMany(),
    user: DS.belongsTo(),
    company: DS.belongsTo(),
    seminarProductMeta: DS.belongsTo(),
    chatRooms: DS.hasMany(),
    productMetaValues : DS.hasMany('product-meta-value'),
    productUnits : DS.hasMany('product-unit'),
    companyProducts : DS.hasMany('company-product'),
    expeditionProducts : DS.hasMany('expedition-product'),
    units: DS.hasMany('unit'),
    // ]

    displayName: computed('code', 'name', function () {
        return this.get('code') + ' - ' + this.get('name');
    }),

    shortDescription: computed('description', function () {
        if (!isBlank(this.get('description'))) {
            return this.get('description').substring(0, 300);
        }
    }),

    formattedBasePrice: computed('price', function () {
        return this.numberFormatter.formatCurrency(this.get('basePrice'));
    }),

    formattedPrice: computed('price', function () {
        return this.numberFormatter.formatCurrency(this.get('price'));
    }),

    existsInShoppingCart: computed('shoppingCart.items.[]', function () {
        return this.get('shoppingCart').isItemExists(this);
    }),

    imageUrl: computed('image', function () {
        return this.get('image');
    }),

    speakerName: computed('seminarProductMeta.speaker.fullname', 'user.fullname', function () {
        var name = this.get('seminarProductMeta.speaker.fullname');

        if (isBlank(name)) {
            name = this.get('user.fullname');
        }

        return name;

    }),

    statusLabel: computed('status', function () {
        let statusText = '';
        switch (this.get('status')) {
            case 0:
                statusText = 'Rejected';
                break;
            case 1:
                statusText = 'Proposed';
                break;
            case 2:
                statusText = 'Approved';
                break;
        }
        return statusText;
    }),

    displayImage: computed('imageUrl', function () {
        return '<img src='+this.get('imageUrl')+'>';
    }),

    imageAndName: computed('imageUrl', 'name', function () {
        return this.get('displayImage') + ' ' + this.get('name');
    }),

    productCategory: computed('productCategories', function () {
        return this.get('productCategories.firstObject');
    }),

    /**
     * if no company provided, returns product.price. otherwise returns
     * company_product.price for this product and provided company
     *
     * @param Company the company model to be used to search for related
     * company_product record
     * @return float
     */
    getSellPrice: function (company) {
        var sellPrice;

        if (!isBlank(company)) {
            let cp = this.companyProducts.findBy('company.id', company.id);

            if (!isBlank(cp)) {
                sellPrice = cp.price;
            } else {
                sellPrice = this.price;
            }

        } else {
            sellPrice = this.price;
        }

        return sellPrice;
    }
});
