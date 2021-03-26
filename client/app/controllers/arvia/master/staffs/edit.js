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
                name: this.intl.t('staff.heading.edit'),
            }
        ],
    };

    @action
    saveStaffForm(staff, event) {
        event.preventDefault();
        KTApp.blockPage();

        let qswal = this.qswal.edit();

        return staff.save().then(() => {
            KTApp.unblockPage();
            qswal.s();
        })
        .catch((response) => {
            KTApp.unblockPage();
            qswal.e(response.errors);
        });

    }
}

