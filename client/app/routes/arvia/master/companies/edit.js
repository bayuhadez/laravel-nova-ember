import Route from '@ember/routing/route';
import { action } from '@ember/object';
import { hash } from 'rsvp';
import { inject as service } from '@ember/service';
import lookupValidator from 'ember-changeset-validations';
import Changeset from 'ember-changeset';
import MCompanyValidations from 'rcf/validations/m-company';

export default class AxxMasterCompaniesEditRoute extends Route {
    
    @service currentUser;
    @service intl;

    hasConfirmed = false;
    
    MCompanyValidations = MCompanyValidations;

    model(params)
    {
        return hash({
            company: this.store.findRecord('company', params.company_id, {
                include: 'parent-company,children-company,warehouses,created-by,updated-by'
            }),
            companies: this.store.query('company', {
                filter: { weAndChildren: this.currentUser.getCompanyIds() }
            }),
        });
    }

    setupController(controller, model)
    {
        controller.setProperties({
            company: model.company,
            companies: model.companies
        });
        controller.set('companyChangeset', new Changeset(controller.company, lookupValidator(MCompanyValidations), MCompanyValidations));
    }

    resetCon()
    {
        this.controller.company.rollbackAttributes();
        this.controller.cancelAddress();
        this.controller.showTabContent3 = false;
        this.controller.showTabContent1 = true;
        if (this.controller.isEditingChildCompany) {
            this.controller.disabledParentCompanyInput = true;
        } else {
            this.controller.disabledParentCompanyInput = false;
        }
    }

    @action
    willTransition(transition)
    {
        if(this.get('controller.companyChangeset.isDirty')
        || this.get('controller.formCompanyAddressChangeset.isDirty')
        || this.get('controller.formChildCompanyChangeset.isDirty')) {
            if(!this.hasConfirmed) {
                transition.abort();
                this.qswal.confirmTransition(() => {
                    this.hasConfirmed = true;
                    transition.retry();
                    this.resetCon();
                })
            } else {
                return true;
            }
        } else {
            this.resetCon();
            return true;
        }
    }

    @action
    didTransition()
    {
        this.controller.refreshDtChildCompany = true;
        this.controller.isEditingChildCompany = false;
    }

}
