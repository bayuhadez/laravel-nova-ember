import DtMSelect from 'rcf/components/metronic/datatable-inputs/m-select';
import { inject as service } from '@ember/service';
import { task, timeout } from 'ember-concurrency';

export default DtMSelect.extend({
    store: service(),

    relationName: 'product', // default relation name for dropdown

    searchTask: task(function* (term)
    {
        let self = this;

        yield timeout(300);

        return self
            .get('params')
            .searchRepo(self.get('store'), term)
            .then((items) => {
                return items.map((item) => {
                    let obj = {};

                    obj.id = item.get('id');
                    obj[self.get('params.searchField')] = item.get(self.get('params.searchField'));

                    return obj;
                });
            });
    }),
});
