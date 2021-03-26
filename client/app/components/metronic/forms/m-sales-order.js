import Component from '@glimmer/component';
import { A } from '@ember/array';
import { isBlank } from '@ember/utils';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import { computed } from '@ember/object';

export default class MetronicFormsMSalesOrderComponent extends Component {

    @service intl;
    @service store;

    @tracked isEditingPso;
    @tracked pso;
    @tracked selectedCustomer;
    
    @tracked activeTab;

    constructor()
    {
        super(...arguments);
        this.modalId = 'sales-order-modal-form';
        this.title = '';
        this.salesOrder = this.args.salesOrder;
        this.selectedCustomer = this.salesOrder.customer;
        this.companyOptions = this.args.companyOptions;
        this.customerOptions = this.args.customerOptions;
        this.salesOptions = this.args.salesOptions;
        this.warehouseStaffOptions = this.args.warehouseStaffOptions;
        this.stockDivisions = A();
        this.productSalesOrders = A();
        this.activeTab = "tab_product";
    }

    willTransition()
    {
        this.activeTab = "tab_product";
    }

    @computed('salesOrder.isNew')
    get title() {
        let salesOrder = this.salesOrder;

        if (!isBlank(salesOrder)) {
            if (salesOrder.isNew) {
                return this.intl.t('sales_order.heading.create');
            } else {
                return this.intl.t('sales_order.heading.edit');
            }
        }

        return this.intl.t('sales_order.heading.formcompany');
    }

    get deliveryRecipientCustomerOptions() {
        let options = A();
        let selectedCustomer = this.selectedCustomer;

        if (!isBlank(selectedCustomer)) {
            options.pushObject(selectedCustomer);

            if (!isBlank(selectedCustomer.get('childrenCustomer'))) {
                selectedCustomer.get('childrenCustomer').forEach((childCustomer) => {
                    options.pushObject(childCustomer);
                });
            }

        }

        return options;
    }
}
