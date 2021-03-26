import Component from '@glimmer/component';
import { inject as service } from '@ember/service';
import { action, computed } from '@ember/object';
import { isBlank, isEmpty } from '@ember/utils';
import { tracked } from '@glimmer/tracking';
import { A } from '@ember/array';
import dtc from 'rcf/utils/dtcolumn';

export default class MetronicFormsMStockFifoComponent extends Component {

    @service intl;
    @service store;
    @tracked refreshData = false;

    formRecordModel = 'product-stock-movement';
    includeParameters = 'product-stock,stock-division,rack.warehouse';

    get filterParameters()
    {
        let filters = {};
        filters.inProduct = this.args.productId;
        filters.inStockDivision = this.args.stockDivisionId;
        return filters;
    }

    columns = A([
        dtc.create({
            valuePath: 'formattedDatetime',
            name: this.intl.t('product_stock_movement.attr.datetime')
        }),
        dtc.create({
            valuePath: 'inOrOutType',
            name: this.intl.t('product_stock_movement.attr.in_or_out')
        }),
        dtc.create({
            valuePath: 'receiptNumber',
            name: this.intl.t('product_stock_movement.attr.receipt_number')
        }),
        dtc.create({
            valuePath: 'quantity',
            name: this.intl.t('product_stock_movement.attr.quantity')
        }),
        dtc.create({
            valuePath: 'stock',
            name: this.intl.t('product_stock_movement.attr.stock')
        }),
        dtc.create({
            valuePath: 'price',
            name: this.intl.t('product_stock_movement.attr.price')
        }),
        dtc.create({
            valuePath: 'from',
            name: this.intl.t('product_stock_movement.attr.from')
        }),
        dtc.create({
            valuePath: 'to',
            name: this.intl.t('product_stock_movement.attr.to')
        }),
    ]);
}
