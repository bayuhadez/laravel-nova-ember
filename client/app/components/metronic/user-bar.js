import Component from '@ember/component';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';

export default Component.extend({
    intl: service(),
    session: service(),
    currentUser: service(),
    classNames: ['kt-header__topbar-item', 'kt-header__topbar-item--user'],

    actions: {
        signOut() {
            this.get('session').invalidate();
        }
    }

});
