import Service from '@ember/service';
import RSVP from 'rsvp';
import { inject as service } from '@ember/service';
import { computed, observer } from '@ember/object';
import { isBlank, isEmpty } from '@ember/utils';
import { include } from 'underscore';

export default class CurrentUserService extends Service {
    @service session;
    @service store;

    load() {
        return this.get('user');
    }

    getCompanyIds() {
        let companies = this.get('user.companies');
        return companies.mapBy('id');
    }

    loadCurrentUser() {
        return new Promise((resolve, reject) => {
            const userId = this.get('session.data.authenticated.user_id');
            if (!isEmpty(userId)) {
                this.get('store').findRecord('user', userId, {include: 'companies'}).then((user) => {
                    this.set('user', user);
                }, reject);
            } else {
                resolve();
            }
        });
    }

    @computed('session.isAuthenticated')
    get user() {
        if (this.get('session.isAuthenticated')) {
            return this.store.queryRecord('user', {
                current: true,
                include: 'roles,companies',
            });
        } else {
            return RSVP.resolve();
        }
    }

    @computed('user')
    get currentCompany() {
        return new Promise(resolve => {
            if (!isBlank(this.get('session.store.currentCompany'))) {
                resolve(this.get('session.store.currentCompany'));
            }
            this.get('user.companies').then((companies) => {
                const currentCompany = companies.get('firstObject');
                if (isBlank(this.get('session.store.currentCompany'))) {
                    this.set('currentCompany', currentCompany);
                }
                resolve(currentCompany);
            });
        });
    }
    set currentCompany(value)
    {
        this.set('session.store.currentCompany', value);
        return value;
    }
}
