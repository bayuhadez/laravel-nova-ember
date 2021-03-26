import Component from '@ember/component';
import dtc from 'rcf/utils/dtcolumn';
import { computed, observer } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank, isEmpty } from '@ember/utils';
import { once } from '@ember/runloop';

export default Component.extend({
    store: service(),
    currentUser: service(),
    intl: service(),

    newRequestOrderProduct: null,
    newRequestOrderProducts: [],
    isEditingRequestOrderProduct: false,
    
    pageTitle: computed(function () {
        return (this.requestOrder.isNew ? "request_order.heading.add" : "request_order.heading.edit");
    }),
    disabledSubmitAsROButton: computed(
        'requestOrder.requestOrderProducts',
        'newRequestOrderProducts',
        function () {
            return ( isEmpty(this.get('newRequestOrderProducts')) && isEmpty(this.get('requestOrder.requestOrderProducts')) );
    }),

    init()
    {
        this._super(...arguments);
        this.setProperties({
            companies: this.currentUser.get('user.companies'),
            user: this.currentUser.get('user'),
        });
    },

    didInsertElement(){
        if (this.get('requestOrder.isNew')) {
            this.set('requestOrder.company', this.companies.get('firstObject'));
        }
    },

    updatedAt: computed('requestOrder.updatedAt', 'requestOrder.isNew', function () {
        if (this.get('requestOrder.isNew')) {
            return moment().format('DD/MM/YYYY');
        } else {
            return this.get('requestOrder.formattedUpdatedAt');
        }
    }),

    defaultRequestOrderProductUnit()
    {
        this.set('newRequestOrderProduct.unit', this.get('newRequestOrderProduct.product.units.firstObject') || null);
    },

    productChanged: observer('newRequestOrderProduct.product', function () {
        if(!this.isEditingRequestOrderProduct) {
            once(this, 'defaultRequestOrderProductUnit');
        }
    }),

    columns: computed(function () {
        return [
            dtc.create({
                name: this.intl.t('request_order_product.attr.product_code'),
                valuePath: 'product.code',
            }),
            dtc.create({
                name: this.intl.t('request_order_product.attr.product_name'),
                valuePath: 'product.name',
            }),
            dtc.create({
                name: this.intl.t('request_order_product.attr.quantity'),
                valuePath: 'quantity',
            }),
            dtc.create({
                name: this.intl.t('unit.attr.name'),
                valuePath: 'unit.name',
            }),
            dtc.create({
                name: this.intl.t('request_order_product.attr.information'),
                valuePath: 'information',
            }),
            dtc.create({
                name: '',
                valuePath: '_actionsColumn',
                buttons: [
                    {preset: 'edit'},
                    {preset: 'delete'},
                ]
            }),
        ];
    }),

    filterParameters: computed('requestOrder', function() {
        let filters = {};

        if (!this.get('requestOrder.isNew')) {
            filters.inRequestOrder = this.get('requestOrder.id');
        } else {
            filters.inRequestOrder = -1;
        }

        return filters;
    }),

    footerRows: computed(function() {
        let self = this;
        return [
            {
                product: {
                    code: {
                        component: {
                            name: "metronic/datatable-inputs/m-select-product",
                            params: {
                                relationName: 'product',
                                searchField: 'code',
                                searchRepo: function(store, term) {
                                    return store.query('product', {
                                        fields: {
                                            'products': 'code,name,units',
                                        },
                                        filter: {
                                            'codeLike': term,
                                            'inCompany': self.requestOrder.get('company.id'),
                                        },
                                        include: 'units',
                                        sort: 'code',
                                    }).then((products) => {
                                        return products;
                                    });
                                },
                            }
                        },
                    },
                    name: {
                        component: {
                            name: "metronic/datatable-inputs/m-select",
                            params: {
                                relationName: 'product',
                                searchField: 'name',
                                searchRepo: function(store, term) {
                                    return store.query('product', {
                                        fields: {
                                            'products': 'code,name,units',
                                        },
                                        filter: {
                                            'nameLike': term,
                                            'inCompany': self.requestOrder.get('company.id'),
                                        },
                                        include: 'units',
                                        sort: 'name',
                                    }).then((products) => {
                                        return products;
                                    });
                                },
                            }
                        },
                    },
                },
                'quantity': {
                    component: {
                        name: "metronic/datatable-inputs/m-text",
                        params: {
                            attributeName: 'quantity',
                        }
                    },
                },
                'unit': {
                    name: {
                        component: {
                            name: "metronic/datatable-inputs/m-select",
                            params: {
                                relationName: 'unit',
                                searchField: 'name',
                                optionsFromRelationName: 'product.units',
                            }
                        },
                    },
                },
                'information': {
                    component: {
                        name: "metronic/datatable-inputs/m-text",
                        params: {
                            attributeName: 'information',
                        }
                    },
                },
                '_actionsColumn': {
                    'buttons': [
                        {preset: 'add'},
                    ]
                },
            },
        ];
    }),

    
    actions: {
        
        addRequestOrderProduct()
        {
            let newRequestOrderProduct = this.get('newRequestOrderProduct');
            let requestOrder = this.get('requestOrder');

            if (!isBlank(newRequestOrderProduct)) {
                // make sure all validation is valid
                if (newRequestOrderProduct.get('validations.isValid')) {
                    if (requestOrder.get('isNew')) {
                        if(!this.isEditingRequestOrderProduct) {
                            this.get('newRequestOrderProducts').pushObject(newRequestOrderProduct);
                        }
                        this.set('disabledSubmitAsROButton', false);
                    } else {
                        newRequestOrderProduct.set('requestOrder', requestOrder);
                        // save
                        newRequestOrderProduct
                            .save()
                            .then((/*requestOrderProduct*/) => {
                                // refreshData
                                this.set('refreshData', true);
                                this.set('disabledSubmitAsROButton', false);
                            });
                    }
                    this.isEditingRequestOrderProduct = false;
                } else {
                    this.get('newRequestOrderProduct').set('showErrors', true);
                }
            }
        },

        deleteRequestOrderProductRecord(requestOrderProduct)
        {
            this.qswal.confirmDelete(() => {
                KTApp.blockPage();
                requestOrderProduct.destroyRecord().then(() => {
                    this.get('newRequestOrderProducts').removeObject(requestOrderProduct);
                    KTApp.unblockPage();
                    this.qswal.delete().s();
                }).catch(() => {
                    KTApp.unblockPage();
                    this.qswal.delete().e();
                });
            });
        },

        async editRequestOrderProductRecord(requestOrderProduct)
        {
            this.isEditingRequestOrderProduct = true;
            await this.set('newRequestOrderProduct', requestOrderProduct);
        },

        submitAsDraft()
        {
            this.set('requestOrder.status', 2);
            this.submitAction();
        },

        submitAsRO()
        {
            this.set('requestOrder.status', 0);
            this.submitAction();
        },

    },
});
