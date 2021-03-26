import Route from '@ember/routing/route';
import { hash } from 'rsvp';
import { action } from '@ember/object';
import { inject as service } from '@ember/service';
import lookupValidator from 'ember-changeset-validations';
import Changeset from 'ember-changeset';
import MCompanyValidations from 'rcf/validations/m-company';

export default class AxxMasterCompaniesAddRoute extends Route {

    @service currentUser;

    hasConfirmed = false;

    MCompanyValidations = MCompanyValidations;

    model()
    {
        return hash({
            company: this.store.createRecord('company'),
            companies: this.store.query('company', {
                filter: { weAndChildren: this.currentUser.getCompanyIds() }
            })
        });
    }

    setupController(controller, model)
    {
        controller.setProperties({
            company: model.company,
            companies: model.companies,
        });
        controller.set('companyChangeset', new Changeset(controller.company, lookupValidator(MCompanyValidations), MCompanyValidations));
    }

    @action
    willTransition(transition)
    {
        if(this.get('controller.companyChangeset.isDirty')) {
            if(!this.hasConfirmed) {
                transition.abort();
                this.qswal.confirmTransition(() => {
                    this.hasConfirmed = true;
                    transition.retry();
                    this.controller.company.rollbackAttributes();
                })
            } else {
                return true;
            }
        } else {
            this.controller.company.rollbackAttributes();
            return true;
        }
    }
}
