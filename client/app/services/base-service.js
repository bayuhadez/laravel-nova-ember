import Service from '@ember/service';
import ENV from 'rcf/config/environment';
import { inject as service } from '@ember/service';

export default Service.extend({
	store: service(),
	ajaxHelperService: service(),

	apiUrl(key, params)
	{
		//let url = ENV.apiUrl + '/' + ENV.api2Namespace + '/' + this.get('urls')[key];
		let url = ENV.apiUrl + '/' + ENV.apiNamespace + '/' + this.get('urls')[key];

		for (let paramKey in params) {
			url = url.replace('{'+paramKey+'}', params[paramKey]);
		}

		return url;
	},
});
