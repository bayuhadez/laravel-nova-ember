import Component from '@ember/component';
import { inject as service } from '@ember/service';
import { task, timeout } from 'ember-concurrency';

export default Component.extend({
    store: service(),

    cellMeta: null,
    cellValue: {
        component: {
            params: {
                searchField: null,
                valuePath: null,
                label: null,
                searchRepo: null,
                optionsFromRelationName: null,
            }
        }
    },
    columnValue: null,
    componentName: "metronic/inputs/m-select",
    relationName: null, // relation name for dropdown
    model: null, // object that will be assigned attribute
    rowMeta: null,
    rowValue: null,

    didInsertElement()
    {
        this._super(...arguments);

        let component = this.get('cellValue.component');

        this.set('params', component.params);
    },

    searchTask: task(function* (term)
    {
        let self = this;

        yield timeout(300);

        return self
            .get('params')
            .searchRepo(self.get('store'), term)
            .then((items) => {
                return items.map((item) => {
                    return {
                        id: item.get('id'),
                        name: item.get(self.get('params.searchField'))
                    };
                });
            });
    }),

    actions: {
        onChange(item)
        {
            let self = this;
            self.get('model').set(
                self.get('params.relationName'),
                self.store.peekRecord(self.get('params.relationName'), item.id)
            );
        },
    },
});
