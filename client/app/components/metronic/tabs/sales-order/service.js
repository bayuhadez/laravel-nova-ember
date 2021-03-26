import Component from '@glimmer/component';
import dtc from 'rcf/utils/dtcolumn';
import { inject as service } from '@ember/service';
import { action } from '@ember/object';
import { tracked } from '@glimmer/tracking';
import { computed } from '@ember/object';
import { A } from '@ember/array';

export default class MetronicTabsSalesOrderServiceComponent extends Component {
    @service intl;
    @service store;
    @service qswal;

    @tracked isEditingSso;
    @tracked sso;

    constructor()
    {
        super(...arguments);
        this.salesOrder = this.args.salesOrder;
    }

    @computed
    get columns() {
        return A([
            dtc.create({
                name: 'Kode-Nama Jasa',
                valuePath: 'service.displayName',
            }),
            dtc.create({
                name: 'Jumlah Pesanan',
                valuePath: 'orderQuantity',
            }),
            dtc.create({
                name: 'Harga',
                valuePath: 'sellPrice',
            }),
            dtc.create({
                name: 'Diskon',
                valuePath: 'discount',
            }),
            dtc.create({
                name: 'Subtotal',
                valuePath: 'total',
            }),
            dtc.create({
                buttons: [
                    {preset: 'edit'},
                    {preset: 'delete'},
                ]
            }),
        ]);
    }

    @action
    addSalesOrderService()
    {
        let sso = this.store.createRecord('sales-order-service');

        this.editSalesOrderService(sso);
    }

    @action
    removeSalesOrderService(sso)
    {
        this.qswal.confirmDelete(() => {
            KTApp.blockPage();
            this.salesOrder.salesOrderServices.popObject(sso);
            sso.destroyRecord().then(() => {
                KTApp.unblockPage();
                this.qswal.delete().s();
            }).catch(() => {
                KTApp.unblockPage();
                this.qswal.delete().e();
            });
        });
    }

    @action
    editSalesOrderService(sso)
    {
        // set current editing sso
        this.sso = sso;

        // set editing sso mode to true to show the input sso form
        this.isEditingSso = true;
    }

    @action
    saveSalesOrderService(sso)
    {
        if (!this.salesOrder.salesOrderServices.includes(sso)) {
            // add the sso to the sso list 
            this.salesOrder.salesOrderServices.pushObject(sso);
        }

        // remove current edit sso
        this.sso = null;

        // set edit mode to hide the sso form
        this.isEditingSso = false;
    }

    get salesOrderServicesFilterParameters()
    {
        return {
            inSalesOrders: A([this.salesOrder.id])
        };
    }
}
