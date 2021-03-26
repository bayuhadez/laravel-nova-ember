import Route from '@ember/routing/route';
import ENV from 'rcf/config/environment';
import AuthenticatedRouteMixin from 'ember-simple-auth/mixins/authenticated-route-mixin';

export default Route.extend(AuthenticatedRouteMixin, {
    authenticationRoute: ENV.APP.tenant + '.login',
});
