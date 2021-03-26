import Component from '@ember/component';
import { inject as service } from '@ember/service';
import { schedule } from '@ember/runloop';

export default Component.extend({
    intl: service(),

    didInsertElement() {
        schedule('afterRender', () => {
            this.$('table').addClass('table table-bordered table-hover');
        });
    }
});
