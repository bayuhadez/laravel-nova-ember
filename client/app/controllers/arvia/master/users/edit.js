import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { action } from '@ember/object';
import { isBlank } from '@ember/utils';
import { hash } from 'rsvp';

export default class AxxMasterUsersEditController extends Controller {
    // services
    @service store;
    @service intl;

    breadcrumbs = {
        title: this.intl.t('user.identifier'),
        route: "axx.master.users",
        subNav: [
            {
                name: this.intl.t('user.heading.edit'),
            }
        ],
    };

    onSaveUserForm()
    {
        let user = this.get('user');

        return new Promise(function(resolve, reject) {
            let person = user.get('person');
            let companyUsers = user.get('companyUsers');

            user.save().then((user) => { 

                let promises = {};

                if (!isBlank(person.get('id'))) {
                    person.set('user', user);
                    person.then((person) => {
                        promises['person'] = person.save();
                    });
                }

                companyUsers.forEach((companyUser) => {

                    if (!isBlank(companyUser.get('company.id'))) {
                        promises['companyUser' + companyUser.get('id')] = companyUser.save();
                    }
                });

                hash(promises).then(() => {
                    resolve(user);
                }).catch((err) => {
                    reject(err);
                });

            }).catch((err) => {
                reject(err);
            });
        });
    }

    @action
    saveUserForm(event) {
        event.preventDefault();
        KTApp.blockPage();

        let qswal = this.qswal.edit();

        return this
            .onSaveUserForm()
            .then(() => {
                KTApp.unblockPage();
                qswal.s();
            })
            .catch((response) => {
                KTApp.unblockPage();
                qswal.e(response.errors[0]);
            });
    }
}

