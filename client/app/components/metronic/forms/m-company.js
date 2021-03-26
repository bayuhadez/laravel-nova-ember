import Component from '@glimmer/component';
import { isBlank } from '@ember/utils';
import { action } from '@ember/object';
import { inject as service } from '@ember/service';

export default class MetronicFormsMCompanyComponent extends Component {

    divisionOptions = [
        { label: 'Wholesale', value: '1' },
        { label: 'Retail', value: '2' }
    ];

    valueAddedTaxTypeOptions = [
        { label: 'Manual', value: '1' },
        { label: 'Ya', value: '2' },
        { label: 'Tidak', value: '3' }
    ];

    @service store;
    @service intl;

    get formTitle()
    {
        if (!isBlank(this.args.formTitle)) {

            return this.args.formTitle;

        } else {

            if (!isBlank(this.args.company)) {
                if (this.args.company.isNew) {
                    return this.intl.t("company.heading.add");
                } else {
                    return this.intl.t("company.heading.edit");
                }
            }
        }
        return '';
    }

    get companyOptions()
    {
        if (isBlank(this.args.companies)) {
            return [];
        } else {
            return this.args.companies.filter((company) => !company.get('isNew'));
        }
    }

}
