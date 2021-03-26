import Component from '@glimmer/component';
import dtc from 'rcf/utils/dtcolumn';
import { inject as service } from '@ember/service';
import { action } from '@ember/object';
import { tracked } from '@glimmer/tracking';
import { computed } from '@ember/object';
import { A } from '@ember/array';

export default class MetronicTabsSalesOrderProductComponent extends Component {
    @service intl;
    @service store;
    @service qswal;

    @tracked isEditingPso;
    @tracked pso;

    constructor()
    {
        super(...arguments);
        this.salesOrder = this.args.salesOrder;
    }

    @computed
    get columns() {
        return A([
            dtc.create({
                name: 'Kode-Nama Barang',
                valuePath: 'product.displayName',
            }),
            dtc.create({
                name: 'Divisi Stok',
                valuePath: 'stockDivision.name',
            }),
            dtc.create({
                name: 'Stok Sementara',
                valuePath: 'product.stock',
            }),
            dtc.create({
                name: 'Jumlah Pesanan',
                valuePath: 'orderQuantity',
            }),
            dtc.create({
                name: 'Jumlah Disetujui',
                valuePath: 'amountApproved',
            }),
            dtc.create({
                name: 'Jumlah Ditolak',
                valuePath: 'amountRejected',
            }),
            dtc.create({
                name: 'Sisa Pesanan',
                valuePath: 'amountLeftToApprove',
            }),
            dtc.create({
                name: 'Barang Siap',
                valuePath: 'amountPrepared',
            }),
            dtc.create({
                name: 'Konfirmasi Terima Barang',
                valuePath: '',
            }),
            dtc.create({
                name: 'Kembalikan Barang',
                valuePath: 'amountReturned',
            }),
            dtc.create({
                name: 'Jumlah Akhir',
                valuePath: 'finalAmount',
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
                valuePath: 'subTotal',
            }),
            dtc.create({
                name: 'Status',
                valuePath: 'statusText',
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
    addProductSalesOrder()
    {
        let pso = this.store.createRecord('product-sales-order', {
            status: 0,
            amountApproved: 0,
            amountRejected: 0,
        });

        this.editProductSalesOrder(pso);
    }

    @action
    removeProductSalesOrder(pso)
    {
        this.qswal.confirmDelete(() => {
            KTApp.blockPage();
            this.salesOrder.productSalesOrders.popObject(pso);
            pso.destroyRecord().then(() => {
                KTApp.unblockPage();
                this.qswal.delete().s();
            }).catch(() => {
                KTApp.unblockPage();
                this.qswal.delete().e();
            });
        });
    }

    @action
    editProductSalesOrder(pso)
    {
        // set current editing pso
        this.pso = pso;

        // set editing pso mode to true to show the input pso form
        this.isEditingPso = true;
    }

    @action
    saveProductSalesOrder(pso)
    {
        if (!this.salesOrder.productSalesOrders.includes(pso)) {
            // add the pso to the pso list 
            this.salesOrder.productSalesOrders.pushObject(pso);
        }

        // remove current edit pso
        this.pso = null;

        // set edit mode to hide the pso form
        this.isEditingPso = false;
    }

    get productSalesOrdersFilterParameters()
    {
        return {
            inSalesOrders: A([this.salesOrder.id])
        };
    }
}
