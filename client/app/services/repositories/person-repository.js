import Service from '@ember/service';
import { isBlank } from '@ember/utils';
import { inject as service } from '@ember/service';

export default class RepositoriesPersonRepositoryService extends Service {
    @service store;

    getAll()
    {
        return this.store.findAll('person');
    }

    getPeopleInCompany(companyId = null)
    {
        return this.store.query('person', {
            filter: { peopleInCompany: companyId }
        });
    }

}
