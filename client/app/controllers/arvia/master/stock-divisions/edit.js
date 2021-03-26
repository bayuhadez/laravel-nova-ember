import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { action } from '@ember/object';
import { isBlank } from '@ember/utils';
import { hash } from 'rsvp';

export default class AxxMasterStockDivisionsEditController extends Controller {
    // services
    @service intl;

    breadcrumbs = {
        title: this.intl.t('stock_division.identifier'),
        route: "axx.master.stock-divisions",
        subNav: [
            {
                name: this.intl.t('stock_division.heading.edit'),
            }
        ],
    };

    @action
    saveStockDivisionForm(event) {
        event.preventDefault();
        KTApp.blockPage();

        let record = this.get('company');
        let qswal = this.qswal.edit();

        let promises = [];

        // save the stock divisions
        record.get('stockDivisions').forEach(function (stockDivision) {
            if (!isBlank(stockDivision.get('name'))) {
                promises.addObject(stockDivision.save());
            }
        });

        return hash(promises)
            .then(() => {
                KTApp.unblockPage();
                qswal.s();
            })
            .catch((response) => {
                KTApp.unblockPage();
                qswal.e(response.errors[0]);
            });
    }
}