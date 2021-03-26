import ENV from 'rcf/config/environment';

export default function routeTenantResolver(route) {
	let tenantPrefix = ENV.APP.tenant;
	return tenantPrefix + '.' + route;
}
