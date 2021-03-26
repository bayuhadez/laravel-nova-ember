import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import dtc from 'rcf/utils/dtcolumn';
import { action, computed } from '@ember/object';
import { isBlank, isEmpty } from '@ember/utils';

export default class AxxMasterProductsIndexController extends Controller {
    // services
    @service store;
    @service intl;

    @tracked refreshData = false;
    formRecordModel = 'product';
    filterLowStock = false;
    filterEmptyStock = false;

    @computed(
        'selectedFilterCompany',
        'filterLowStock',
        'filterEmptyStock',
        'columns.@each.filterValue')
    get filterParameters() {
        let filters = {};

            // filters coming from dt columns
            this.get('columns').forEach(function (column) {
                let filter = column.get('filter');

                if (!isEmpty(column.get('filterValue'))) {
                    filters[filter.scope] = column.get('filterValue');
                }
            });

            if (!isBlank(this.get('selectedFilterCompany.id'))) {
                filters.inCompany = this.get('selectedFilterCompany.id');
            }

            if (this.get('filterLowStock')) {
                filters.stockAtMinimumOrLess = true;
            }

            if (this.get('filterEmptyStock')) {
                filters.outOfStock = true;
            }

            return filters;
    }

    @computed()
    get columns() {
        return [
            dtc.create({
                name: this.intl.t('product.attr.image'),
                valuePath: 'image',
                component: 'product/zoomable-image',
            }),
            dtc.create({
                name: this.intl.t('product.attr.code'),
                valuePath: 'code',
                filter: {
                    // filter input type
                    type: 'text',
                    // scope will be used by LJA to do the filtering
                    scope: 'codeLike',
                    // can be used to set default filter value
                    value: null,
                },
            }),
            dtc.create({
                name: this.intl.t('product.attr.name'),
                valuePath: 'name',
                filter: {
                    type: 'text',
                    scope: 'nameLike',
                }
            }),
            dtc.create({
                name: this.intl.t('product.attr.sku'),
                valuePath: 'sku',
                filter: {
                    type: 'text',
                    scope: 'skuLike',
                }
            }),
            dtc.create({
                name: this.intl.t('product.attr.base_price'),
                valuePath: 'formattedBasePrice',
                filter: {
                    type: 'text',
                    scope: 'basePriceFilter',
                }
            }),
            dtc.create({
                name: this.intl.t('product.attr.price'),
                valuePath: 'formattedPrice',
                filter: {
                    type: 'text',
                    scope: 'priceFilter',
                }
            }),
            dtc.create({
                name: this.intl.t('product.attr.stock'),
                valuePath: 'stock',
                filter: {
                    type: 'text',
                    scope: 'stockFilter',
                }
            }),
            dtc.create({
                buttons: [
                    {preset: 'edit'},
                    {preset: 'delete'},
                ]
            }),
        ];
    }

    @action
    filterLowStock()
    {
        this.setProperties({
            filterLowStock: !this.get('filterLowStock'),
            filterEmptyStock: false,
        });
    }

    @action
    filterEmptyStock()
    {
        this.setProperties({
            filterLowStock: false,
            filterEmptyStock: !this.get('filterEmptyStock'),
        });
    }

    @action
    filterProduct(state)
    {
        if(state) {
            this.set('products', this.get('lowStockProducts'));
        } else {
            this.set('products', this.get('emptyStockProducts'));
        }
    }

    @action
    editProductRecord(record)
    {
        this.transitionToRoute('axx.master.products.edit', record.get('id'));
    }

    @action
    deleteProductRecord(record)
    {
        this.qswal.confirmDelete(() => {
            KTApp.blockPage();
            record.destroyRecord().then(() => {
                KTApp.unblockPage();
                this.qswal.delete().s();
                this.set('refreshData', true);
            }).catch(() => {
                KTApp.unblockPage();
                this.qswal.delete().e();
            });
        });
    }
}

