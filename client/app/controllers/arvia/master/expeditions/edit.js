import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { action } from '@ember/object';

export default class AxxMasterExpeditionsEditController extends Controller {
    // services
    @service store;
    @service intl;

    breadcrumbs = {
        title: this.intl.t('expedition.identifier'),
        route: "axx.master.expeditions",
        subNav: [
            {
                name: this.intl.t('expedition.heading.edit'),
            }
        ],
    };

    @action
    saveExpeditionForm(expedition, event) {
        event.preventDefault();
        KTApp.blockPage();

        let qswal = this.qswal.edit();

        return expedition
            .save()
            .then(() => {
                KTApp.unblockPage();
                qswal.s();
            })
            .catch((response) => {
                KTApp.unblockPage();
                qswal.e(response.errors[0]);
            });
    }
}

