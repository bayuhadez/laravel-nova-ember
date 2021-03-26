import Controller from '@ember/controller';
import { action } from '@ember/object';
import { inject as service } from '@ember/service';

export default class AxxMasterServicesAddController extends Controller {
    @service store;

    @action
    async saveRecord(record, event)
    {
        event.preventDefault();

        try {
            KTApp.blockPage();
            await record.saveWithRelations();
            KTApp.unblockPage();
            this.qswal.create().s();
            this.transitionToRoute('axx.master.services.index');
        } catch (err) {
            KTApp.unblockPage();
            this.qswal.create().e(err);
        }
    }
}
