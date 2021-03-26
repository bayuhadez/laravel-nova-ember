import Component from '@glimmer/component';
import { action } from '@ember/object';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';
import { tracked } from '@glimmer/tracking';

export default class MetronicFormsMProductTransactionReceiptComponent extends Component {
    @service store;
    @service moneyService;
    @service numberFormatter;

    // properties:
    productNameQueryParams = {
        scopes: ['nameLike'],
        sort: 'name'
    };

    constructor()
    {
        super(...arguments);
    }

    /** ----- computed ----- */
    @computed('args.purchaseReceipt.company')
    get productOptions()
    {
        KTApp.blockPage();
        this.store.query('product', {
            filter: { inCompany: this.args.purchaseReceipt.get('company.id') },
            include: 'units,company-products,company-products.company'
        }).then((productOptions) => {
            this.productOptions = productOptions;
            KTApp.unblockPage();
        });
    }

    @computed(
        'args.productTransactionReceipt.discounts',
        'args.productTransactionReceipt.price',
        'args.productTransactionReceipt.quantity'
    )
    get calculatedFormattedSubTotal()
    {
        this.args.productTransactionReceipt.calculateAndSetSubTotal();
        return this.args.productTransactionReceipt.formattedSubTotal;
    }

    @computed(
        'args.productTransactionReceipt.cost',
        'args.productTransactionReceipt.subTotal'
    )
    get calculatedFormattedTotal()
    {
        this.args.productTransactionReceipt.calculateAndSetTotal();
        return this.args.productTransactionReceipt.formattedTotal;
    }

    @computed(
        'args.productTransactionReceipt.product',
        'args.productTransactionReceipt.price',
        'args.productTransactionReceipt.productUnit'
    )
    get calculatedFormattedPricePerPcs()
    {
        this.args.productTransactionReceipt.calculateAndSetPricePerPcs(this.args.purchaseReceipt.isPpn);
        return this.args.productTransactionReceipt.formattedPricePerPcs;
    }

    @computed(
        'args.productTransactionReceipt.foreignPrice',
        'args.productTransactionReceipt.productUnit'
    )
    get calculatedFormattedForeignPricePerPcs()
    {
        this.args.productTransactionReceipt.calculateAndSetForeignPricePerPcs(this.args.purchaseReceipt.isPpn);
        return this.args.productTransactionReceipt.formattedForeignPricePerPcs;
    }

    @computed(
        'args.productTransactionReceipt.price',
        'args.purchaseReceipt.currencyRate'
    )
    get calculatedFormattedForeignPrice()
    {
        this.args.productTransactionReceipt.calculateAndSetForeignPrice(
            this.args.purchaseReceipt.currencyRate
        );
        return this.args.productTransactionReceipt.formattedForeignPrice;
    }

    /** ----- actions: -----  */
    @action async onProductChanged(product)
    {
        KTApp.blockPage();
        if (isBlank(product)) {
            this.args.productTransactionReceipt.resetProductUnitAndUnit();
        } else {
            this.args.productTransactionReceipt.assignProduct(product);

            // set primary product_unit
            let productUnits = await product.productUnits;
            let primaryProductUnit = productUnits.findBy('isPrimary', true);
            if (!isBlank(primaryProductUnit)) {
                this.args.productTransactionReceipt.productUnit = primaryProductUnit;
            } else {
                this.args.productTransactionReceipt.productUnit = null;
            }

            // set price
            await this.setPrice();
            this.setForeignPrice();
        }
        KTApp.unblockPage();
    }

    setPrice()
    {
        let companyProduct = this.args.productTransactionReceipt.product
            .get('companyProducts')
            .findBy('company.id', this.args.purchaseReceipt.get('company.id'));
        this.args.productTransactionReceipt.price = (
            (
                isBlank(companyProduct)
                || isBlank(companyProduct.receiptPrice)
            )
            ? this.args.productTransactionReceipt.get('product.price')
            : companyProduct.receiptPrice
        );
    }

    @action
    recalculatePrice()
    {
        this.args.productTransactionReceipt.price = (
            this.args.productTransactionReceipt.foreignPrice
            * this.args.purchaseReceipt.currencyRate
        );
    }

    @action
    setForeignPrice()
    {
        this.args.productTransactionReceipt.foreignPrice = (
            this.args.productTransactionReceipt.price
            / this.args.purchaseReceipt.currencyRate
        );
    }

}
