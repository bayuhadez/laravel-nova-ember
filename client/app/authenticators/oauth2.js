import OAuth2PasswordGrant from 'ember-simple-auth/authenticators/oauth2-password-grant';
import ENV from '../config/environment';
import $ from 'jquery';

export default OAuth2PasswordGrant.extend({
    serverTokenEndpoint: ENV.apiUrl + '/api/v1/login',
    serverTokenRevocationEndpoint: ENV.apiUrl + '/revoke',
    rejectWithResponse: true,
});
