import Route from '@ember/routing/route';
import { hash } from 'rsvp';
import { action } from '@ember/object';
import { inject as service } from '@ember/service';

export default class AxxSalesOrdersAddRoute extends Route {
    @service currentUser;
    @service('repositories/company-repository') companyRepository;
    @service('repositories/customer-repository') customerRepository;
    @service('repositories/staff-repository') staffRepository;

    model()
    {
        return hash({
            salesOrder: this.store.createRecord('sales-order'),
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
