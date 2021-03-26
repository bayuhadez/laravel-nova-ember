import Route from '@ember/routing/route';
import { hash } from 'rsvp';
import { action } from '@ember/object';
import { inject as service } from '@ember/service';

export default class AxxSalesOrdersEditRoute extends Route {
    @service currentUser;
    @service('repositories/company-repository') companyRepository;
    @service('repositories/customer-repository') customerRepository;
    @service('repositories/staff-repository') staffRepository;

    model(params)
    {
        return hash({
            salesOrder: this.store.findRecord('sales-order', params.sales_order_id,{
                include: 'customer.person,company,created-by.person,sales.person,warehouse-staff.person,product-sales-orders.product,product-sales-orders.stock-division'
            }),
            companyOptions: this.companyRepository
                .getCompanyWithChildren(this.currentUser.currentCompany),
            customerOptions: this.customerRepository
                .getAll(),
            staffOptions: this.staffRepository
                .getAll(),
        });
    }

    setupController(controller, model)
    {
        controller.setProperties({
            salesOrder: model.salesOrder,
            companyOptions: model.companyOptions,
            customerOptions: model.customerOptions,
            salesOptions: model.staffOptions,
            warehouseStaffOptions: model.staffOptions,
        });
    }

    @action
    willTransition()
    {
        this.controller.salesOrder.rollbackAttributes();
        return true;
    }
}
