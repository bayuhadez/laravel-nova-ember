import { helper } from '@ember/component/helper';
import ur from 'rcf/utils/route-tenant-resolver';

export function r(params/*, hash*/) {
	let [route] = params;
	return ur(route);
}

export default helper(r);
