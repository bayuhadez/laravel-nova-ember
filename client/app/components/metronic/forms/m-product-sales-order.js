import Component from '@glimmer/component';
import { action } from '@ember/object';
import { isBlank } from '@ember/utils';
import { A } from '@ember/array';
import { tracked } from '@glimmer/tracking';

export default class MetronicFormsMProductSalesOrderComponent extends Component {
    get productOptions() {
        let options = A();
        let company = this.args.company;

        if (!isBlank(company)) {
            options = company.get('products');
            return options;
        }

        return options;
    }

    get stockDivisionOptions() {
        let options = A();
        let company = this.args.company;

        if (!isBlank(company)) {
            options = company.get('stockDivisions');
            return options;
        }

        return options;
    }

    @action
    async setProduct(product)
    {
        // get the product's default price for the selected company
        let company = await this.args.company;
        this.args.pso.product = product;
        this.args.pso.sellPrice = product.getSellPrice(company);
    }
}
