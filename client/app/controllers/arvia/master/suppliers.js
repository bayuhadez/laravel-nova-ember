import Controller from '@ember/controller';
import dtc from 'rcf/utils/dtcolumn';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';
import { isEmpty } from '@ember/utils';

export default Controller.extend({
    store: service(),
    intl: service(),

    actions : {
        /*
        onSaveRecord()
        {
            let self = this;
            return this.get('formRecord').save().then(() => {
                self.set('refreshData', true);
            });
        },
        */
    }
});
