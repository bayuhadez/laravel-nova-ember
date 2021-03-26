import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import { action } from '@ember/object';
import dtc from 'rcf/utils/dtcolumn';

export default class AxxSalesOrdersIndexController extends Controller {

    @service intl;

    @tracked refreshDt = false;

    includeParameters = 'customer'
        +',product-sales-orders'
        +',created-by'
        +',created-by.person'
        +',warehouse-staff.person'
        +',sales.person';

    columns = [
        dtc.create({
            name: this.intl.t('sales_order.attr.createdAt'),
            valuePath: 'formattedCreatedAt',
        }),
        dtc.create({
            name: this.intl.t('sales_order.attr.number'),
            valuePath: 'number',
        }),
        dtc.create({
            name: this.intl.t('sales_order.attr.customerName'),
            valuePath: 'customer.displayName',
        }),
        dtc.create({
            name: this.intl.t('sales_order.rel.sales'),
            valuePath: 'sales.name',
        }),
        dtc.create({
            name: this.intl.t('sales_order.rel.warehouseStaff'),
            valuePath: 'warehouseStaff.name',
        }),
        dtc.create({
            name: this.intl.t('sales_order.attr.totalNominalNota'),
            valuePath: 'grandTotal',
        }),
        dtc.create({
            name: this.intl.t('sales_order.attr.requiresDelivery'),
            valuePath: 'requiresDeliveryText',
        }),
        dtc.create({
            name: this.intl.t('sales_order.attr.status'),
            valuePath: 'statusLabel',
        }),
        dtc.create({
            buttons: [
                {preset: 'approve'},
                {preset: 'reject'},
                {preset: 'edit'},
                {preset: 'delete'},
            ]
        }),
    ];

    @action
    approveSalesOrder(salesOrder)
    {
        if (salesOrder.get('canApprove')) {
            salesOrder.get('productSalesOrders').forEach((pso) => {
                pso.set('status', 1);
                pso.save();
            });
            salesOrder.set('status', 4);
            salesOrder.save();
            this.qswal.manual(
                'sales_order.alert.set_status_success_title',
                'sales_order.alert.set_approved_success_message',
                'success'
            );
        } else {
            this.qswal.manual(
                'sales_order.alert.set_status_fail_title',
                'sales_order.alert.set_approved_fail_message',
                'error'
            );
        }
    }

    @action
    rejectSalesOrder(salesOrder)
    {
        if (salesOrder.get('canApprove')) {
            salesOrder.set('status', 5);
            salesOrder.save();
            this.qswal.manual(
                'sales_order.alert.set_status_success_title',
                'sales_order.alert.set_onhold_success_message',
                'success'
            );
        } else {
            this.qswal.manual(
                'sales_order.alert.set_status_fail_title',
                'sales_order.alert.set_onhold_fail_message',
                'error'
            );
        }
    }

    @action
    editSalesOrder(record)
    {
        this.transitionToRoute('axx.sales-orders.edit', record.id);
    }

    @action
    deleteSalesOrder(record)
    {
        this.qswal.confirmDelete(() => {
            KTApp.blockPage();
            record.destroyRecord().then(() => {
                KTApp.unblockPage();
                this.qswal.delete().s();
            }).catch(() => {
                KTApp.unblockPage();
                this.qswal.delete().e();
            });
        });
    }
}
