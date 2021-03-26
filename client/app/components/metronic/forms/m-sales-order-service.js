import Component from '@glimmer/component';
import { action } from '@ember/object';
import { isBlank } from '@ember/utils';
import { A } from '@ember/array';

export default class MetronicFormsMSalesOrderServiceComponent extends Component {
    get serviceOptions() {
        let options = A();
        let company = this.args.company;

        if (!isBlank(company)) {
            options = company.get('services');
            return options;
        }

        return options;
    }

    @action
    async setService(service)
    {
        // get the service's default price for the selected company
        let company = await this.args.company;
        this.args.sso.service = service;
        this.args.sso.sellPrice = service.getSellPrice(company);
    }
}
