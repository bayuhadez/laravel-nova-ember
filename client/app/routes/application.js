import ApplicationRouteMixin from 'ember-simple-auth/mixins/application-route-mixin';
import RSVP from 'rsvp';
import Route from '@ember/routing/route'
import Echo from 'laravel-echo';
import ENV from 'rcf/config/environment';
import $ from 'jquery';
import { inject as service } from '@ember/service'
import { isBlank } from '@ember/utils'

const { set } = Ember;

export default Route.extend(ApplicationRouteMixin, {
    routeAfterAuthentication: ENV.APP.tenant+'.dashboard',
    currentUser: service('current-user'),
    intl: service(),
    headData: service(),
    session: service(),

    async beforeModel() {
        /* TODO: this code as backup, remove this if its no longer used
        this._loadCurrentUser()
        return this.intl.setLocale(['id-id'])
        */
        let user = await this.currentUser.load();
        if (!isBlank(user)) {
            await this.currentUser.get('currentCompany');
        }
        this.intl.setLocale(['id-id']);
    },


    afterModel(model) {
        var self = this;

		if (ENV.APP.tenant === 'axx') {
			set(this, 'headData.tenant', 'axx');
            $('body').addClass('kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed ember-application kt-aside--minimize');
            $('body').append('<script src="/assets/metronic/plugins/global/plugins.bundle.js"></script>');
		} else {
			set(this, 'headData.tenant', 'xxx');
			$('body').append('<script src="/assets/xxx/js/core/popper.min.js"></script>');
			$('body').append('<script src="/assets/xxx/js/core/bootstrap-material-design.min.js"></script>');
			$('body').append('<script src="/assets/xxx/js/plugins/moment.min.js"></script>');
			$('body').append('<script src="/assets/xxx/js/plugins/bootstrap-datetimepicker.js"></script>');
			$('body').append('<script src="/assets/xxx/js/plugins/nouislider.min.js"></script>');
			$('body').append('<script src="/assets/xxx/js/reactive-code.js"></script>');
			$('body').append('<script src="/assets/xxx/js/material-kit.js"></script>');
		}

        window.Echo = new Echo({
            broadcaster: 'socket.io',
            host: window.location.hostname + ':6001'
        });

        window.Echo.channel('auth')
            .listen('UserLoggedIn', (e) => {
                let user = e.user;
                self.get('currentUser.user').then((currentUser) => {
                    if (!isBlank(currentUser)) {
                        if (user.id === parseInt(currentUser.get('id'))) {
                            alert(self.get('intl').t("You've logged in from another device"));
                            self.get('session').invalidate();
                        }
                    }    
                });
            });
    },

    sessionAuthenticated() {
        this._super(...arguments);
    },

});
