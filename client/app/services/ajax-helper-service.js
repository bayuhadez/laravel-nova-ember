import Service from '@ember/service';
import { inject as service } from '@ember/service';
import $ from 'jquery';

export default Service.extend({
    session: service(),

    ajax(ajaxParam) {
        if (this.session.isAuthenticated) {
            const headers = {};
            headers['Authorization'] = `Bearer ${this.session.data.authenticated.access_token}`;
            // set default Request Header:
            headers['X-Requested-With'] = 'XMLHttpRequest';
            // Media Type:
            headers['Accept'] = 'application/vnd.api+json';
            headers['Content-Type'] = 'application/vnd.api+json';

            ajaxParam['headers'] = $.extend(
                headers, // auth headers
                ajaxParam['headers'] // provided
            );
        }
        return $.ajax(ajaxParam);
    },

    ajaxWithoutAuth(ajaxParam) {
        ajaxParam['headers'] = $.extend(
            {'X-Requested-With': 'XMLHttpRequest'}, // set default Request Header
            ajaxParam['headers'] // provided
        );

        return $.ajax(ajaxParam);
    },

});
