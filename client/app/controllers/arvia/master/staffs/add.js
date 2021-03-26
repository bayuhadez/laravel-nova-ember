import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import dtc from 'rcf/utils/dtcolumn';
import { action, computed } from '@ember/object';
import { isEmpty } from '@ember/utils';

export default class AxxMasterStaffsAddController extends Controller {
    // services
    @service store;
    @service intl;

    breadcrumbs = {
        title: this.intl.t('staff.identifier'),
        route: "axx.master.staffs",
        subNav: [
            {
                name: this.intl.t('staff.heading.add'),
            }
        ],
    };

    @action
    saveStaffForm(staff, event) {
        event.preventDefault();
        KTApp.blockPage();

        let person = staff.get('person');

        let qswal = this.qswal.create();

        return person.then((person) => {
            return person.save().then(() => {
                return staff.save().then((staff) => {
                    KTApp.unblockPage();
                    qswal.s();
                    this.transitionToRoute('axx.master.staffs.edit', staff.get('id'));
                });
            });
        })
        .catch(() => {
            KTApp.unblockPage();
            qswal.e(this.get('staff.errors'));
        });

    }
}

