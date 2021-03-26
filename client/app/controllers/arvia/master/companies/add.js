import Controller from '@ember/controller';
import { action } from '@ember/object';
import { tracked } from '@glimmer/tracking';
import { inject as service } from '@ember/service';

export default class AxxMasterCompaniesAddController extends Controller {
    
    @service currentUser;

    @tracked showErrors = false;

    @action
    async saveCompany(changeset, event)
    {
        let user = await this.currentUser.load();
        await changeset.set('updatedBy', user);
        
        event.preventDefault();

        await changeset.validate();

        if (changeset.isValid) {
            KTApp.blockPage();
            changeset.save().then(() => {
                let companyUser = this.store.createRecord('company-user');
                companyUser.set('company', this.company);
                companyUser.set('user', user);
                companyUser.save();
                KTApp.unblockPage();
                this.qswal.create().s();
                this.transitionToRoute('axx.master.companies.edit', this.company.id);
            }).catch((err) => {
                KTApp.unblockPage();
                this.showErrors = true;
                this.qswal.create().e();
            });
        } else {
            this.showErrors = true;
        }
    }
}
