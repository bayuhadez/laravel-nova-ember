import ENV from 'rcf/config/environment';
import r from 'rcf/utils/route-tenant-resolver';
import qswal from 'rcf/utils/qswal';

export function initialize(application) {
	var host = window.location.hostname;

	if (host == 'localhost') {
		ENV.APP.tenant = 'axx';
	} else {
		ENV.APP.tenant = 'axx';
	}

	application.register('libs:route-tenant-resolver', r, {instantiate:false});
	application.inject('component', 'r', 'libs:route-tenant-resolver');
	application.inject('controller', 'r', 'libs:route-tenant-resolver');
	application.inject('route', 'r', 'libs:route-tenant-resolver');

    application.register('libs:qswal', qswal, {instantiate:true});
	application.inject('component', 'qswal', 'libs:qswal');
	application.inject('controller', 'qswal', 'libs:qswal');
	application.inject('route', 'qswal', 'libs:qswal');

}

export default {
	initialize
};
