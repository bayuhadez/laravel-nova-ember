import Controller from '@ember/controller';
import { inject as service } from '@ember/service';

export default Controller.extend({
    intl: service(),
    session: service(),
    currentUser: service(),

    init() {
        this._super(...arguments);
        this.setProperties({
            username: null,
            password: null,
            errorMessage: null
        });
    },

    actions: {
        signIn()
        {
            let { username, password } = this.getProperties('username', 'password');
            this.get('session').authenticate('authenticator:oauth2', username, password).then(() => {
                this.get('rememberMe') && this.set('session.store.cookieExpirationTime', 60 * 60 * 24 * 14);
                this.qswal.manual(this.intl.t('auth.success'), this.intl.t('auth.login success'), 'success');
            }).catch((err) => {
                let response = err.responseJSON;
                this.qswal.manual(
                    this.intl.t('message.error.message'),
                    this.intl.t('auth.'+response.message.toLowerCase()),
                    'error'
                );
            });
        },
    }
});
