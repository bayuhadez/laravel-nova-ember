import Component from '@glimmer/component';
import ENV from 'rcf/config/environment';
import { inject as service } from '@ember/service';
import { action, computed } from '@ember/object';
import { tracked } from '@glimmer/tracking';
import { isBlank } from '@ember/utils';
import { A } from '@ember/array';

export default class MetronicFormsMStockRackComponent extends Component {

    @tracked racks;
    @service store;
    @service intl;
    @service ajaxHelperService;

    constructor() {
        super(...arguments);

        this.fetchRacksWithTotal(this.args.productId, this.args.stockDivisionId);
    }

    headers = A([
        this.intl.t('rack.rel.warehouse'),
        this.intl.t('rack.identifier'),
        this.intl.t('rack.attr.total_quantity_in_stock_division'),
    ]);

    fetchRacksWithTotal(productId, stockDivisionId) {
        KTApp.blockPage();

        let url = (
            ENV.apiUrl + '/'
            + ENV.apiNamespace + '/'
            + 'products/'
            + productId + '/'
            + 'get-product-stock-in-rack-by-stock-division/'
            + stockDivisionId
        );

        this
            .ajaxHelperService
            .ajax({
                'type': 'GET',
                'url': url,
                'contentType': false,
                'processData': false,
            })
            .then((response) => {
                // await this.composeRacksData(response);
                this.racks = response;
                // this.composeRacksData(response);
                KTApp.unblockPage();
            })
            .catch((e) => {
                KTApp.unblockPage();
                this.qswal.e(e);
            })
    }
}
