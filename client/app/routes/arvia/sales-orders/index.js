import Route from '@ember/routing/route';
import { hash } from 'rsvp';
import { inject as service } from '@ember/service';

export default class AxxWholesellersSalesOrdersIndexRoute extends Route {

    @service currentUser;

    model()
    {
        return hash ({
            companies: this.store.query('company', {
                filter: { meAndChildren: this.currentUser.currentCompany.id }
            }),
            customers: this.store.query('customer', {
                include: 'company.company-addresses.address,person.person-addresses.address,company.staffs.person'
            }),
            staffs: this.store.findAll('staff'),
        });
    }

    setupController(controller, model)
    {
        controller.setProperties({
            companies: model.companies,
            customers: model.customers,
            salesStaffs: model.staffs,
            warehouseStaffs: model.staffs,
        });
    }

}
