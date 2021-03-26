import EmberObject from '@ember/object';
import { computed } from '@ember/object';
import { isEmpty } from '@ember/utils';

export default EmberObject.extend({
    init: function ()
    {
        this._super(...arguments);

        if (isEmpty(this.get('isVisible'))) {
            this.set('isVisible', true);
        }

        if (!isEmpty(this.get('filter'))) {
            if (isEmpty(this.get('filter.value'))) {
                this.set('filter.value', "");
            }
        }
    },
    filterValue: computed('filter.value', function () {
        return this.get('filter.value');
    }),
})
