import Component from '@glimmer/component';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import { A } from '@ember/array';
import ENV from 'rcf/config/environment';

export default class ProductStockTableComponent extends Component {

    @tracked productStocks;
    @service store;
    @service intl;
    @service ajaxHelperService;

    constructor()
    {
        super(...arguments);

        this.fetchProductStocksInProductByStockDivision(this.args.product.get('id'));
        this.productId = this.args.product.get('id');
    }

    headers = A([
        this.intl.t('product_stock.attr.formattedDisplayCompanyAndDivision'),
        this.intl.t('product_stock.rel.stock_division'),
        this.intl.t('product_stock.attr.quantity'),
        this.intl.t('product_stock.attr.temp_quantity'),
        this.intl.t('product_stock.attr.stockCard'),
    ]);

    fetchProductStocksInProductByStockDivision(productId) {
        KTApp.blockPage();

        let url = (
            ENV.apiUrl + '/'
            + ENV.apiNamespace + '/'
            + 'product-stock/'
            + productId + '/'
            + 'get-in-product-by-stock-division'
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
                this.productStocks = response;
                KTApp.unblockPage();
            })
            .catch(() => {
                KTApp.unblockPage();
            })
    }
}
