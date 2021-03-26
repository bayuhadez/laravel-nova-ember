import Controller from '@ember/controller';
import dtc from 'rcf/utils/dtcolumn';
import { A } from '@ember/array';
import { inject as service } from '@ember/service';
import { action, computed } from '@ember/object';
import { isBlank, isEmpty } from '@ember/utils';
import { tracked } from '@glimmer/tracking';
import Promise, { resolve, reject } from 'rsvp';

export default class AxxPrepareOrReturnProductIndexController extends Controller {

    @service intl;
    @service currentUser;

    @tracked selectedFilterDivision;
    @tracked selectedFilterRequiresDelivery;
    @tracked selectedFilterStatus;
    @tracked refreshData = false;

    divisionOptions = [
        { label: this.intl.t('company.division.wholesale'), value: '1' },
        { label: this.intl.t('company.division.retail'), value: '2' }
    ];

    deliveryOptions = [
        { label: this.intl.t('options.yes'), value: '0' },
        { label: this.intl.t('options.no'), value: '1' }
    ];

    statusOptions = [
        { 
            label: this.intl.t('product_sales_order.status.unauthorized'),
            value: '0'
        },
        { 
            label: this.intl.t('product_sales_order.status.partially_unauthorized'),
            value: '1'
        },
        { 
            label: this.intl.t('product_sales_order.status.authorized'),
            value: '2'
        },
        { 
            label: this.intl.t('product_sales_order.status.prepared'),
            value: '3'
        },
        { 
            label: this.intl.t('product_sales_order.status.on_delivery'),
            value: '4'
        },
        { 
            label: this.intl.t('product_sales_order.status.delivered'),
            value: '5'
        },
        {
            label: this.intl.t('product_sales_order.status.bill'),
            value: '6'
        },
    ];

    includeParameters = "sales-order"
        + ",sales-order.company"
        + ",sales-order.customer.company"
        + ",sales-order.delivery-recipient-customer"
        + ",sales-order.delivery-recipient-customer.company"
        + ",product"
        + ",stock-division"
        + ",sales-order.delivery-address";
    
    @computed('selectedFilterDivision', 'selectedFilterRequiresDelivery', 'selectedFilterStatus', 'columns.@each.filterValue')
    get filterParameters() {
        let filters = {};

        // filters coming from dt columns
        this.get('columns').forEach(function (column) {
            let filter = column.get('filter');

            if (!isEmpty(column.get('filterValue'))) {
                filters[filter.scope] = column.get('filterValue');
            }
        });

        if (!isBlank(this.selectedFilterDivision)) {
            filters.salesOrderCompanyDivisionFilter = this.selectedFilterDivision;
        }

        if (!isBlank(this.selectedFilterRequiresDelivery)) {
            filters.salesOrderRequiresDeliveryFilter = this.selectedFilterRequiresDelivery;
        }

        if (!isBlank(this.selectedFilterStatus)) {
            filters.statusFilter = this.selectedFilterStatus;
        }

        return filters;
    }

    columns = [
        dtc.create({
            name: this.intl.t('sales_order.rel.company'),
            valuePath: 'salesOrder.company.name',
            filter: {
                type: 'text',
                scope: 'salesOrderCompanyNameLike'
            }
        }),
        dtc.create({
            name: this.intl.t('company.attr.division'),
            valuePath: 'salesOrder.company.divisionName',
        }),
        dtc.create({
            name: this.intl.t('sales_order.attr.createdAt'),
            valuePath: 'salesOrder.formattedCreatedAt',
        }),
        dtc.create({
            name: this.intl.t('sales_order.attr.number'),
            valuePath: 'salesOrder.number',
            filter: {
                type: 'text',
                scope: 'salesOrderNumberLike'
            }
        }),
        dtc.create({
            name: this.intl.t('sales_order.attr.requiresDelivery'),
            valuePath: 'salesOrder.requiresDeliveryText',
            
        }),
        dtc.create({
            name: this.intl.t('sales_order.attr.customerName'),
            valuePath: 'salesOrder.customer.displayName',
            filter: {
                type: 'text',
                scope: 'salesOrderCustomerPersonNameLike'
            }
        }),
        dtc.create({
            name: this.intl.t('sales_order.rel.deliveryRecipientCustomer'),
            valuePath: 'salesOrder.deliveryRecipientCustomer.displayName',
            filter: {
                type: 'text',
                scope: 'salesOrderDeliveryRecipientNameLike'
            }
        }),
        dtc.create({
            name: this.intl.t('sales_order.rel.deliveryAddress'),
            valuePath: 'salesOrder.deliveryAddress.address',
            filter: {
                type: 'text',
                scope: 'salesOrderDeliveryAddressLike'
            }
        }),
        dtc.create({
            name: this.intl.t('address.rel.regency'),
            valuePath: 'salesOrder.deliveryAddress.regency.name',
            filter: {
                type: 'text',
                scope: 'salesOrderDeliveryAddressRegencyLike'
            }
        }),
        dtc.create({
            name: this.intl.t('product_sales_order.rel.product'),
            valuePath: 'product.displayName',
            filter: {
                type: 'text',
                scope: 'productNameLike'
            }
        }),
        dtc.create({
            name: this.intl.t('product_sales_order.rel.stockDivision'),
            valuePath: 'stockDivision.name',
            filter: {
                type: 'text',
                scope: 'stockDivisionNameLike'
            }
        }),
        dtc.create({
            name: this.intl.t('product_sales_order.attr.temporaryStock'),
            valuePath: 'product.stock',
        }),
        dtc.create({
            name: this.intl.t('product_sales_order.attr.amount_approved'),
            valuePath: 'amountApproved',
        }),
        dtc.create({
            name: this.intl.t('product_sales_order.attr.amount_prepared'),
            component: "input-text",
            type: "number",
            value: "amountPrepared",
            change: this.updateProductSalesOrder,
        }),
        dtc.create({
            name: this.intl.t('product_sales_order.heading.prepare'),
            buttons: [
                {
                    preset: "buttonAction",
                    icon: "flaticon-download-1",
                    onAction: this.openPrepareModal,
                },
            ]
        }),
        dtc.create({
            name: this.intl.t('product_sales_order.attr.amount_left'),
            valuePath: 'amountLeftToPrepare',
        }),
        dtc.create({
            name: this.intl.t('product_sales_order.heading.return'),
            valuePath: 'amountReturned',
            clickable: true,
            clickableAction: this.openReturnModal,
        }),
        // Waiting for prepareProduct feature to be finished
        // dtc.create({
        //     name: this.intl.t('product_sales_order.status.confirm_returned'),
        //     component: "metronic/datatable-inputs/m-checkbox-select-row",
        //     checkedRows: null,
        //     disabled: true,
        // }),
        dtc.create({
            name: this.intl.t('product_sales_order.attr.status'),
            valuePath: 'statusText',
        }),
        dtc.create({
            name: this.intl.t('product_sales_order.status.ready'),
            component: "input-checkbox",
            checked: 'status',
            change: this.setProductSalesOrderStatusToReady,
        }),
    ];

    @action
    setProductSalesOrderStatusToReady(pso)
    {
        KTApp.blockPage();
        let status = ((pso.status === 3) ? 0 : 3);
        pso.status = status;
        pso.save().then((pso) => {
            KTApp.unblockPage();
        });
    }

    @action
    updateProductSalesOrder(pso)
    {
        pso.save();
    }

    // PSO record used in prepare or return
    @tracked psoSource = null;
    
    // Prepare Product [
    @tracked isPreparingProduct = false;

    @action
    openPrepareModal(pso)
    {
        this.isReturningProduct = false;
        this.isPreparingProduct = true;
        this.psoSource = pso;
    }

    @action
    closePrepareModal()
    {
        this.isPreparingProduct = false;
        this.psoSource = null;
    }

    @action
    savePrepareProductForm(productStocks, pso, transitRack, event)
    {
        event.preventDefault();

        let psmp = A();
        let totalOut = 0;

        // reduce real quantity value by _tempTakenAmount
        productStocks.forEach((ps) => {
            if (ps._tempTakenAmount > 0) {
                totalOut += ps._tempTakenAmount;
                let psmOut = this.store.createRecord(
                    'product-stock-movement', {
                        quantity: ps._tempTakenAmount,
                        inOrOut: 0,
                        product: ps.product,
                        rack: ps.rack,
                        stockDivision: ps.stockDivision,
                        productSalesOrder: pso,
                        productStock: ps,
                        user: this.currentUser.user,
                    }
                );
                let psmIn = this.store.createRecord(
                    'product-stock-movement', {
                        quantity: ps._tempTakenAmount,
                        inOrOut: 1,
                        product: ps.product,
                        rack: transitRack,
                        stockDivision: ps.stockDivision,
                        productSalesOrder: pso,
                        user: this.currentUser.user,
                    }
                );
                psmp.pushObject(psmOut.save());
                psmp.pushObject(psmIn.save());
            }
        });

        return Promise.all(psmp).then(() => {
            if (totalOut > 0) {
                pso.amountPrepared -= totalOut;
                pso.save().catch((response) => {
                    KTApp.unblockPage();
                    this.qswal.edit().e(response);
                });
            }
        }).finally(() => {
            KTApp.unblockPage();
            resolve();
            this.qswal.edit().s();
            this.refreshData = true;
            this.isPreparingProduct = false;
        }).catch((response) => {
            KTApp.unblockPage();
            this.qswal.edit().e(response);
            reject(response);
        });
    }
    // ]

    // Return Product [
    @tracked isReturningProduct = false;

    @action
    openReturnModal(pso)
    {
        this.isPreparingProduct = false;
        this.isReturningProduct = true;
        this.psoSource = pso;
    }

    @action
    closeReturnModal()
    {
        this.isReturningProduct = false;
        this.psoSource = null;
    }

    @action
    saveReturnProductForm(productStocks, pso, event)
    {
        event.preventDefault();

        let psmp = A();

        // add real quantity value by _tempReturnedAmount
        productStocks.forEach((ps) => {
            if (ps._tempReturnedAmount > 0) {
                let psm = this.store.createRecord(
                    'product-stock-movement', {
                        quantity: ps._tempReturnedAmount,
                        inOrOut: 1,
                        product: ps.product,
                        rack: ps.rack,
                        stockDivision: ps.stockDivision,
                        productSalesOrder: pso,
                        productStock: ps,
                        user: this.currentUser.user
                    }
                );
                psmp.pushObject(psm.save());
            }
        });

        return Promise.all(psmp).then(() => {
            KTApp.unblockPage();
            resolve();
            this.qswal.edit().s();
            this.isReturningProduct = false;
        }).catch((response) => {
            KTApp.unblockPage();
            this.qswal.edit().e(response);
            reject(response);
        });
    }
    // ]
}
