import Service from '@ember/service';
import { inject as service } from '@ember/service';

export default class RepositoriesCustomerRepositoryService extends Service {
    @service store;

    getAll()
    {
        return this.store.findAll('customer');
    }
}
