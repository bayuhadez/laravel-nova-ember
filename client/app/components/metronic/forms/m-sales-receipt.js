import Component from '@glimmer/component';
import { inject as service } from '@ember/service';
import { action, computed } from '@ember/object';
import { isBlank } from '@ember/utils';
import { tracked } from '@glimmer/tracking';
import { A } from '@ember/array';
import dtc from 'rcf/utils/dtcolumn';

export default class MetronicFormsMSalesReceiptComponent extends Component {

    @service intl;
    @service store;

    @tracked activeTab = 'tab_product';
    @tracked showSalesOrder = false;
    @tracked refreshData = false;

    constructor()
    {
        super(...arguments);

        if (!this.args.salesReceipt.isNew) {
            // populate pso & sos
            let productTransactionReceipts = this.args.transactionReceipt.get('productTransactionReceipts');
            let serviceTransactionReceipts = this.args.transactionReceipt.get('serviceTransactionReceipts');
        } else {
            this.setCompany(this.args.companyOptions.firstObject);
        }
    }

    get selectedProductSalesOrderIds()
    {
        return this.args.transactionReceipt.get('productTransactionReceipts').mapBy('productSalesOrder.id');
    }

    get selectedSalesOrderServiceIds()
    {
        return this.args.transactionReceipt.get('serviceTransactionReceipts').mapBy('salesOrderService.id');
    }

    get disabledUntilCompanySelected()
    {
        if (!isBlank(this.args.salesReceipt.get('company.id'))) {
            return false;
        }
        return true;
    }

    get disabledShowSalesOrderButton()
    {
        if (
            !isBlank(this.args.salesReceipt.get('company.id'))
            && !isBlank(this.args.transactionReceipt.get('customer.id'))
        ) {
            return false;
        }
        return true;
    }

    @computed('args.salesReceipt.company.id')
    get customerOptions()
    {
        if (!isBlank(this.args.salesReceipt.get('company.id'))) {
            return this.store.query('customer', {
                filter: { assignedInSalesOrderByCompany: this.args.salesReceipt.get('company.id') },
                include: 'sales-orders,company-customers.company'
            });
        }

        return A();
    }

    get psoFilterParameters()
    {
        let filters = {};
        let salesOrders = this.args.transactionReceipt.get('customer.salesOrders');
        let salesOrderIds = salesOrders.mapBy('id');
        filters.inSalesOrders = salesOrderIds;
        filters.inSalesOrderWithStatus = 2;
        filters.status = 3;
        filters.notInOtherTransactionReceipt = this.args.transactionReceipt.id;
        return filters;
    }
    
    psoIncludeParameters = 'product,sales-order,product-stock-movements.rack,product-stock-movements.stock-division,product-stock-movements.product-stock';

    psoColumns = [
        dtc.create({
            name: this.intl.t('sales_order.attr.createdAt'),
            valuePath: 'salesOrder.formattedCreatedAt',
        }),
        dtc.create({
            name: this.intl.t('sales_order.attr.number'),
            valuePath: 'salesOrder.number',
        }),
        dtc.create({
            name: this.intl.t('product_sales_order.rel.product'),
            valuePath: 'product.name',
        }),
        dtc.create({
            name: this.intl.t('product_sales_order.attr.amount'),
            valuePath: 'finalAmount',
        }),
        dtc.create({
            name: this.intl.t('product_sales_order.attr.sell_price'),
            valuePath: 'sellPrice',
        }),
        dtc.create({
            name: this.intl.t('product_sales_order.attr.sub_total'),
            valuePath: 'subTotal',
        }),
        dtc.create({
            component: "metronic/datatable-inputs/m-checkbox-select-row",
            checkedRows: this.selectedProductSalesOrderIds,
            changeAction: this.toggleAssignUnassignProductSalesOrder,
            targetAttr: 'id',
        }),
    ];

    @action
    toggleAssignUnassignProductSalesOrder(pso)
    {
        if (this.isProductSalesOrderInProductTransactionReceipts(pso)) {
            this.deleteProductTransactionReceiptFromProductSalesOrder(pso);
        } else {
            this.createProductTransactionReceiptFromProductSalesOrder(pso);
        }
    }

    get sosFilterParameters()
    {
        let filters = {};
        let salesOrders = this.args.transactionReceipt.get('customer.salesOrders');
        let salesOrderIds = salesOrders.mapBy('id');
        filters.inSalesOrders = salesOrderIds;
        filters.inSalesOrderWithStatus = 2;
        filters.notInOtherTransactionReceipt = this.args.transactionReceipt.id;
        return filters;
    }
    
    sosIncludeParameters = 'sales-order,service';

    sosColumns = [
        dtc.create({
            name: this.intl.t('sales_order.attr.createdAt'),
            valuePath: 'salesOrder.formattedCreatedAt',
        }),
        dtc.create({
            name: this.intl.t('sales_order.attr.number'),
            valuePath: 'salesOrder.number',
        }),
        dtc.create({
            name: this.intl.t('sales_order_service.rel.service'),
            valuePath: 'service.displayName',
        }),
        dtc.create({
            name: this.intl.t('sales_order_service.attr.order_quantity'),
            valuePath: 'orderQuantity',
        }),
        dtc.create({
            name: this.intl.t('sales_order_service.attr.sell_price'),
            valuePath: 'sellPrice',
        }),
        dtc.create({
            name: this.intl.t('sales_order_service.attr.total'),
            valuePath: 'total',
        }),
        dtc.create({
            component: "metronic/datatable-inputs/m-checkbox-select-row",
            checkedRows: this.selectedSalesOrderServiceIds,
            changeAction: this.toggleAssignUnassignSalesOrderService,
            targetAttr: 'id',
        }),
    ];

    get pageTitle()
    {
        return (this.args.salesReceipt.isNew ? "sales_receipt.heading.add" : "sales_receipt.heading.edit");
    }

    @action
    toggleAssignUnassignSalesOrderService(sos)
    {
        if (this.isSalesOrderServiceInServiceTransactionReceipts(sos)) {
            this.deleteServiceTransactionReceiptFromSalesOrderService(sos);
        } else {
            this.createServiceTransactionReceiptFromSalesOrderService(sos);
        }
    }

    @action
    setCompany(company)
    {
        if (this.args.salesReceipt.get('company.id') != company.id) {
            this.args.transactionReceipt.set('customer', null);
            this.args.salesReceipt.set('address', null);
        }
        this.args.salesReceipt.set('company', company);
    }

    @action
    setCustomer(customer)
    {
        if( this.args.transactionReceipt.get('customer.id') != customer.id ) {
            this.args.salesReceipt.set('address', null);
        }
        this.args.transactionReceipt.set('customer', customer);

        if (
            !isBlank(this.args.transactionReceipt.get('customer.id'))
            && !isBlank(this.args.salesReceipt.get('company.id'))
        ) {
            this.populateDueAt(
                this.args.transactionReceipt.get('customer'),
                this.args.salesReceipt.get('company')
            );
        }
    }

    populateDueAt(customer, company)
    {
        let companyCustomers = customer.get('companyCustomers');
        let companyCustomer = companyCustomers.find((cc) => cc.get('company.id') == company.get('id'));

        if (!isBlank(companyCustomer)) {
            let today = moment(new Date());
            let termOfPayment = companyCustomer.get('termOfPayment');
            this.args.salesReceipt.dueAt = today.add(termOfPayment, 'd').toDate();
        }
    }

    @action 
    choosePpnType(ppnType)
    {
        this.args.salesReceipt.isPpn = ppnType.id;
    }

    @action
    toggleShowSalesOrder()
    {
        this.showSalesOrder = !this.showSalesOrder;
    }

    isProductSalesOrderInProductTransactionReceipts(pso)
    {
        let productTransactionReceipts = this.args.transactionReceipt.get('productTransactionReceipts');
        let ptr = productTransactionReceipts.find((ptr) => ptr.get('productSalesOrder.id') === pso.get('id'));
        return !isBlank(ptr);
    }

    isSalesOrderServiceInServiceTransactionReceipts(sos)
    {
        let serviceTransactionReceipts = this.args.transactionReceipt.get('serviceTransactionReceipts');
        let str = serviceTransactionReceipts.find((str) => str.get('salesOrderService.id') === sos.get('id'));
        return !isBlank(str);
    }

    createProductTransactionReceiptFromProductSalesOrder(pso)
    {
        let ptr = this.store.createRecord('product-transaction-receipt', {
            transactionReceipt: this.args.transactionReceipt,
            product: pso.get('product'),
            productSalesOrder: pso,
            orderQuantity: pso.get('orderQuantity'),
            sellPrice: pso.get('sellPrice'),
            total: pso.get('subTotal')
        });
        pso.set('productTransactionReceipt', ptr);

        this.args.transactionReceipt.get('productTransactionReceipts').pushObject(ptr);
    }

    createServiceTransactionReceiptFromSalesOrderService(sos)
    {
        let str = this.store.createRecord('service-transaction-receipt', {
            transactionReceipt: this.args.transactionReceipt,
            service: sos.get('service'),
            salesOrderService: sos,
            orderQuantity: sos.get('orderQuantity'),
            sellPrice: sos.get('sellPrice'),
            total: sos.get('total')
        });
        sos.set('serviceTransactionReceipt', str);

        this.args.transactionReceipt.get('serviceTransactionReceipts').pushObject(str);
    }

    deleteProductTransactionReceiptFromProductSalesOrder(pso)
    {
        let productTransactionReceipts = this.args.transactionReceipt.get('productTransactionReceipts');
        let ptr = productTransactionReceipts.find((ptr) => ptr.get('productSalesOrder.id') === pso.get('id'));
        ptr.destroyRecord();
    }

    deleteServiceTransactionReceiptFromSalesOrderService(sos)
    {
        let serviceTransactionReceipts = this.args.transactionReceipt.get('serviceTransactionReceipts');
        let str = serviceTransactionReceipts.find((str) => str.get('salesOrderService.id') === sos.get('id'));
        str.destroyRecord();
    }

}
