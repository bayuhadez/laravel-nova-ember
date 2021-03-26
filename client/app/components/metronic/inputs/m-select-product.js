import DtMSelect from 'rcf/components/metronic/inputs/m-select';
import { assign } from '@ember/polyfills';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';
import { task, timeout } from 'ember-concurrency';

export default DtMSelect.extend({
    intl: service(),
    store: service(),

    init()
    {
        this._super(...arguments);

        this.setProperties({
            searchField: this.searchField || 'code',
            label: this.label || this.intl.t('product.identifier'),
            selected: this.selected || null,
            model: this.model || null,
            relationName: this.relationName || 'product',
        });
    },

    didInsertElement()
    {
        // default queryParams
        let queryParams = {
            fields: {
                'products': 'code,name,units,product-units,base-price',
            },
            include: 'product-units,product-units.unit',
            scopes: ['codeLike'],
            sort: 'code',
        };

        // set query params and combines from inputs
        if (!isBlank(this.get('queryParams'))) {
            queryParams = assign(queryParams, this.get('queryParams'));
        }
    },

    searchRepo(term)
    {
        let params = {};

        // copy queryParams to var params
        assign(params, this.get('queryParams'));

        let filters = {};

        // compose filter
        if (!isBlank(params.scopes)) {
            params.scopes.forEach((scope) => {
                filters[scope] = term;
            });
        }

        // delete scopes index
        delete params.scopes;

        // set filter
        params.filter = filters;

        return new Promise((resolve, reject) => {
            this.store.query('product', params)
                .then((products) => {
                    resolve(products);
                }).catch((error) => {
                    reject(error);
                });
        });
    },

    searchTask: task(function* (term)
    {
        let self = this;

        yield timeout(1000);

        return self
            .searchRepo(term)
            .then((items) => {
                return items.map((item) => {
                    let obj = {};

                    obj.id = item.get('id');
                    obj[self.get('searchField')] = item.get(self.get('searchField'));

                    return obj;
                });
            });
    }),

    actions: {
        onChange(product)
        {
            let selectedProduct = this.store.peekRecord(this.get('relationName'), product.id)

            if (this.onChange !== undefined) {
                this.onChange(selectedProduct);
            } else {
                this.get('model').set(this.get('relationName'), selectedProduct);
            }
        },
    },

});
