import Component from '@glimmer/component';
import { A } from '@ember/array';
import { action, computed } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';

export default class MetronicFormsMCompanyCustomerComponent extends Component {

    @service store;
    @service currentUser;
    @service ('repositories/company-repository') companyRepository;

    ppnOptions = [ { name: 'Tidak', value: 0 }, { name: 'Ya', value: 1 }];

    constructor()
    {
        super(...arguments);

        if (this.args.companyOptions) {
            this.companyOptions = this.args.companyOptions;
        } else {
            this.companyOptions = this.companyRepository.getAvailableCompaniesNotUsedByCompanyCustomer(
                this.args.customerSource.get('id'),
                this.currentUser.getCompanyIds()
            );
        }

        this.staffs = this.store.findAll('staff', { include: 'company,person' });
    }

    get staffOptions()
    {
        if (!isBlank(this.args.companyCustomer.company)) {
            return this.staffs.filter((staff) => {
                return staff.get('company.id') == this.args.companyCustomer.company.get('id');
            });
        } else {
            return this.staffs;
        }
    }

    get disabled()
    {
        return isBlank(this.args.companyCustomer.company);
    }

    get disableSaveButton()
    {
        if (this.companyOptions) {
            return this.companyOptions.length == 0;
        } 
        return true;
    }

    @action
    setCompany(company)
    {
        if (this.args.companyCustomer.company != company) {
            this.args.companyCustomer.staffs = A();
        }
        this.args.companyCustomer.company = company;
    }

}
