import Component from '@glimmer/component';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import { action, computed } from '@ember/object';
import { isBlank } from '@ember/utils';
import { A } from '@ember/array';
import dtc from 'rcf/utils/dtcolumn';

export default class MetronicFormsAssignCompanyWalletComponent extends Component {

    @service store;
    @service intl;
    @service currentUser;

    @tracked selectedCompanyIds;

    constructor()
    {
        super(...arguments);
        this.selectedCompanyIds = this.args.wallet.get('companies').mapBy('id');
    }

    get companyFilterParameters()
    {
        let filters = {};
        filters.weAndChildren = this.currentUser.getCompanyIds();
        return filters;
    }

    get companyColumns()
    {
        return [
            dtc.create({
                name: this.intl.t('company.identifier'),
                valuePath: 'name',
            }),
            dtc.create({
                component: "metronic/datatable-inputs/m-checkbox-select-row",
                checkedRows: this.args.wallet.companies,
                changeAction: this.toggleAssignUnassignCompany,
                targetAttr: 'id',
            }),
        ];
    }

    @action
    toggleAssignUnassignCompany(company)
    {
        if (this.isCompanyInWallet(company)) {
            this.args.wallet.get('companies').removeObject(company);
        } else {
            this.args.wallet.get('companies').pushObject(company);
        }
    }

    isCompanyInWallet(company)
    {
        let companies = this.args.wallet.get('companies');
        let sameCompany = companies.find((c) => c.get('id') === company.get('id'));
        return !isBlank(sameCompany);
    }

}