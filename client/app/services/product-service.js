import BaseService from 'rcf/services/base-service';
import { inject as service } from '@ember/service';

export default BaseService.extend({
    store: service(),

    init()
    {
        this._super(...arguments);
        this.set('urls', {
            'productIsPurchased': 'products/{productId}/is-purchased',
            'productIsMine': 'products/{productId}/is-mine',
            'lowStockCount': 'products/low-stock-count',
            'outOfStockCount': 'products/out-of-stock-count',
        });
    },

    ajaxHelperService: service(),

    productIsPurchased(productId)
    {
        return new Promise((resolve, reject) => {
            this.get('ajaxHelperService').ajax({
                url: this.apiUrl('productIsPurchased', {productId: productId}),
                success(response)
                {
                    resolve(response);
                },

            });
        });
    },

    productIsMine(productId)
    {
        return new Promise((resolve, reject) => {
            this.get('ajaxHelperService').ajax({
                url: this.apiUrl('productIsMine', {productId: productId}),
                success(response)
                {
                    resolve(response);
                },

            });
        });
    },

    getProductByStreamKey(streamKey)
    {
        let store = this.get('store');

        return store.query('product', {
            filter: {
                streamKey: streamKey,
            },
        }).then(function (products) {
            return products.get('firstObject');
        });
    },

    lowStockCount()
    {
        return new Promise((resolve) => {
            this.get('ajaxHelperService').ajax({
                url: this.apiUrl('lowStockCount'),
                success(response)
                {
                    resolve(response);
                },

            });
        });
    },

    outOfStockCount()
    {
        return new Promise((resolve) => {
            this.get('ajaxHelperService').ajax({
                url: this.apiUrl('outOfStockCount'),
                success(response)
                {
                    resolve(response);
                },

            });
        });
    },

});
