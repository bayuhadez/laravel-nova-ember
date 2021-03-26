import Controller from '@ember/controller';
import { action } from '@ember/object';
import { hash } from 'rsvp';
import { inject as service } from '@ember/service';
import { isBlank, isEmpty } from '@ember/utils';

export default class AxxMasterSuppliersEditController extends Controller {

    @service store;

    @action
    async saveSupplierForm(e) {
        e.preventDefault();
        KTApp.blockPage();

        let company = await this.supplier.company;
        let pic = await this.supplier.pic;

        if (!isBlank(company)) {
            await company.save();
        }

        if (!isBlank(pic)) {
            await pic.save();
        }

        let staffPromise = new Promise((resolve) => {
            let staff = this.store.peekAll('staff')
                .find(function (staff) {
                    return (
                        staff.get('person.id') === pic.id
                        && staff.get('company.id') === company.id
                    );
                });

            if (isEmpty(staff) || isBlank(staff.id)) {
                // create staff
                let staff = this.store.createRecord('staff');
                staff.set('person', pic);
                staff.set('company', company);
                staff.save().then((staff) => {
                    resolve(staff);
                });
            } else {
                resolve(staff);
            }
        });

        return Promise.all([
            this.supplier.save(),
            staffPromise,
        ])
            .then((result) => {
                KTApp.unblockPage();
                this.qswal.create().s();
                this.transitionToRoute(this.r('master.suppliers.index'));
            })
            .catch((response) => {
                KTApp.unblockPage();
                this.qswal.create().e(response);
            });
    }
}
