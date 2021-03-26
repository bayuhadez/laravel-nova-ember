import Component from '@glimmer/component';
import { isBlank } from '@ember/utils';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import { action, computed } from '@ember/object';

export default class MetronicFormsPreOrderProductComponent extends Component {

    @service store;
    @service currentUser;

    constructor()
    {
        super(...arguments);
    }
    
    @computed('args.preOrder.company')
    get productOptions()
    {
        this.store.query('product', {
            filter: { inCompany: this.args.preOrder.company.id },
            include: 'units,company-products,company-products.company'
        }).then((productOptions) => {
            this.productOptions = productOptions;
        });
    }

    get isUnitFieldDisabled()
    {
        return isBlank(this.args.preOrderProduct.product);
    }

    @action
    async setProduct(product)
    {
        this.args.preOrderProduct.product = product;
        await this.setPurchasePrice();
        this.setPurchasePriceForeign();
    }

    setPurchasePrice()
    {
        let companyProduct = this.args.preOrderProduct.product
            .get('companyProducts')
            .findBy('company.id', this.args.preOrder.company.id);
        this.args.preOrderProduct.purchasePrice = (
            (
                isBlank(companyProduct)
                || isBlank(companyProduct.receiptPrice)
            )
            ? this.args.preOrderProduct.product.price
            : companyProduct.receiptPrice
        );

    }

    @action
    recalculatePurchasePrice()
    {
        this.args.preOrderProduct.purchasePrice = (
            this.args.preOrderProduct.purchasePriceForeign
            * this.args.preOrder.currencyRate
        );
    }

    @action
    setPurchasePriceForeign()
    {
        this.args.preOrderProduct.purchasePriceForeign = (
            this.args.preOrderProduct.purchasePrice
            / this.args.preOrder.currencyRate
        );
    }

}
