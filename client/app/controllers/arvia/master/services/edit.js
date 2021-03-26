import Controller from '@ember/controller';
import { action } from '@ember/object';
import { inject as service } from '@ember/service';

export default class AxxMasterServicesEditController extends Controller {
    @service store;

    @action
    async saveRecord(record, event)
    {
        event.preventDefault();

        try {
            KTApp.blockPage();
            await record.saveWithRelations();
            KTApp.unblockPage();
            this.qswal.edit().s();
            this.transitionToRoute('axx.master.services.index');
        } catch (err) {
            KTApp.unblockPage();
            this.qswal.edit().e(err);
        }
    }
}
