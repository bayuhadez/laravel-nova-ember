import Component from '@ember/component';
import { inject as service } from '@ember/service';
import { observer, computed } from '@ember/object';
import { isBlank } from '@ember/utils';
import { A } from '@ember/array';

export default Component.extend({
    store: service(),
    pageTitle: computed(function () {
        return (this.user.isNew ? "user.heading.add" : "user.heading.edit");
    }),

    didReceiveAttrs()
    {
        // fetch companies
        this.store.query('company', {
            filter: { notUsedByUser: this.user.id }
        }).then(async (companies) => {
            await this.set('companies', companies);
            this.set('companyOptions', A());
            this.companies.forEach((company) => {
                this.companyOptions.pushObject(company);
            });
        });

        // fetch people
        let people = this.store.findAll('person');

        // fetch user roles
        let roles = this.store.findAll('role');

        // fetch user stockDivisions
        let stockDivisions = this.store.findAll('stock-division');

        this.setProperties({
            people: people,
            roles: roles,
            stockDivisions: stockDivisions,
        });

        // in case the user has no companyUser record, create it
        this.addCompanyUserIfFull();
    },

    addCompanyUserIfFull()
    {
        let records = this.get('user.companyUsers');

        records.then((records) => {
            let hasEmpty = !isBlank(records.filter(item => {
                return isBlank(item.get('company.name'));
            }));

            if (!hasEmpty) {
                let record = this.store.createRecord('company-user');
                records.pushObject(record);
            }
        });
    },

    onCompanyUserCompanyChanged: observer('user.companyUsers.@each.company.id', function () {
        this.addCompanyUserIfFull();
    }),

    async deleteRecord(record)
    {
        let company = await record.get('company');
        this
            .qswal
            .confirmDelete(async () => {
                this.companyOptions.pushObject(company);
                KTApp.blockPage();
                record
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
    },

    removeCompanyOptionByIndex(company) {
        let index = 0;
        this.companyOptions.forEach(async (companyOption) => {
            if(await companyOption.get('id') == await company.get('id')) {
                this.companyOptions.removeAt(index);
            }
            index++;
        })
    },

    actions: {
        async onSelectCompanyUser(companyUser, company)
        {
            if(!isBlank(await companyUser.get('company.id'))) {
                await this.removeCompanyOptionByIndex(company);
                if(await companyUser.get('company.id') != await company.get('id')){
                    this.companyOptions.pushObject(await companyUser.get('company'));
                }
                companyUser.set('company', company);
            } else {
                await this.removeCompanyOptionByIndex(company);
                companyUser.set('company', company);
            }
        },

        deleteCompanyUser(companyUser)
        {
            this.deleteRecord(companyUser);
        },
    }
});
