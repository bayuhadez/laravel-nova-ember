import Component from '@glimmer/component';
import { inject as service } from '@ember/service';
import { action } from '@ember/object';
import { isBlank } from '@ember/utils';
import { A } from '@ember/array';
// import lookupValidator from 'ember-changeset-validations';
// import { Changeset } from 'ember-changeset';
// import validateServiceCodeUnique from 'rcf/validators/service-code-unique';

export default class MetronicFormsMServiceComponent extends Component {
    @service store;
    @service intl;
    @service qswal;

    constructor()
    {
        super(...arguments);
        this.service = this.args.service;
        this.companies = this.args.companies;
        this.companyOptions = A();
        this.companies.forEach((company) => {
            this.companyOptions.pushObject(company);
        });
        this.serviceCategories = this.args.serviceCategories;

        // let validationMap = {
        //     code: (new validateServiceCodeUnique()).store = this.store,
        // };

        // this.changeset = Changeset(this.service, lookupValidator(validationMap), validationMap);
    }

    removeCompanyOptionByIndex(company) {
        let index = 0;
        this.companyOptions.forEach(async (companyOption) => {
            if(await companyOption.get('id') == await company.get('id')) {
                this.companyOptions.removeAt(index);
            }
            index++;
        })
    }

    @action
    async onSelectCompanyService(companyService, company) {
        if(!isBlank(await companyService.get('company.id'))) {
            await this.removeCompanyOptionByIndex(company);
            if(await companyService.get('company.id') != await company.get('id')){
                this.companyOptions.pushObject(await companyService.get('company'));
            }
            companyService.set('company', company);
        } else {
            await this.removeCompanyOptionByIndex(company);
            companyService.set('company', company);
        }
        
        if (!companyService.price) {
            companyService.set('price', this.service.price);
        }
    }

    @action
    addCategory(name) {
        return this.store.createRecord('service-category', {
            name : name,
        }).save();
    }

    @action
    addCompanyService()
    {
        this.service.companyServices.pushObject(this.store.createRecord('company-service'));
    }

    @action
    async deleteCompanyService(companyService)
    {
        let company = await companyService.get('company');
        this
            .qswal
            .confirmDelete(() => {
                KTApp.blockPage();
                this.companyOptions.pushObject(company);
                companyService
                    .destroyRecord()
                    .then(() => {
                        KTApp.unblockPage();
                        this.qswal.delete().s();
                    })
                    .catch(() => {
                        KTApp.unblockPage();
                        this.qswal.delete().e();
                    });
            });

        return false;
    }
}
