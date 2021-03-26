import Service from '@ember/service';
import { inject as service } from '@ember/service';

export default class RepositoriesCompanyRepositoryService extends Service {
    @service store;

    /**
     * get the specified company's data with its children companies included
     *
     * @param models/company company
     * @return RecordArray of model/company objects
     */
    getCompanyWithChildren(company)
    {
        return this.store.query('company', {
            filter: { meAndChildren: company.id }
        });
    }

    getAll()
    {
        return this.store.findAll('company');
    }

    getCompaniesWithChildren(companyId = null)
    {
        return this.store.query('company', {
            filter: { meAndChildren: companyId }
        });
    }

    getCompaniesUsedByCustomer()
    {
        return this.store.query('company', {
            filter: { usedByCustomer: '' }
        });
    }

    /**
     * Get all companies record not used by this customer for company-customer
     */
    getAvailableCompaniesNotUsedByCompanyCustomer(customerId = null, companyIds = [])
    {
        return this.store.query('company', {
            filter: {
                notUsedByCompanyCustomer: customerId ,
                weAndChildren: companyIds
            }
        });
    }
}
