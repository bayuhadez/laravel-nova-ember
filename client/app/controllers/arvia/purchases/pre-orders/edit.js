import Controller from '@ember/controller';
import PreOrderValidator from 'rcf/validations/pre-order';
import { preOrder as PoConstants } from 'rcf/constants';
import { tracked } from '@glimmer/tracking';
import { action } from '@ember/object';
import { inject as service } from '@ember/service';

export default class AxxPurchasesPreOrdersEditController extends Controller {

    PreOrderValidator = PreOrderValidator;

    @tracked showPreOrderErrors = false;
    @service intl;

    breadcrumbs = {
        title: this.intl.t('pre_order.identifier'),
        route: "axx.purchases.pre-orders",
        subNav: [
            {
                name: this.intl.t('pre_order.heading.edit'),
            }
        ],
    };

    // PreOrder Form [
    @action async savePreOrderForm(preOrderChangeset, event)
    {
        event.preventDefault();
        KTApp.blockPage();
        this.showPreOrderErrors = false;
        await preOrderChangeset.validate();

        if (preOrderChangeset.isValid) {
            try {
                let preOrder = preOrderChangeset.data;
                preOrderChangeset.total = preOrder.calculatedTotal;
                preOrderChangeset.grandTotal = preOrder.calculatedGrandTotal;
                preOrderChangeset.status = PoConstants.STATUS.PENDING;
                await preOrderChangeset.save();
                KTApp.unblockPage();
                this.transitionToRoute('axx.purchases.pre-orders.index');
                this.qswal.create().s();
            } catch (e) {
                KTApp.unblockPage();
                this.showPreOrderErrors = true;
                this.qswal.create().e();
            }
        } else {
            KTApp.unblockPage();
            this.showPreOrderErrors = true;
        }
    }

    @action async savePreOrderAsDraft(preOrderChangeset, event)
    {
        event.preventDefault();
        KTApp.blockPage();
        this.showPreOrderErrors = false;
        await preOrderChangeset.validate();

        if (preOrderChangeset.isValid) {
            try {
                let preOrder = preOrderChangeset.data;
                preOrderChangeset.total = preOrder.calculatedTotal;
                preOrderChangeset.grandTotal = preOrder.calculatedGrandTotal;
                preOrderChangeset.status = PoConstants.STATUS.DRAFT;
                await preOrderChangeset.save();
                KTApp.unblockPage();
                this.transitionToRoute('axx.purchases.pre-orders.index');
                this.qswal.create().s();
            } catch (e) {
                KTApp.unblockPage();
                this.showPreOrderErrors = true;
                this.qswal.create().e();
            }
        } else {
            KTApp.unblockPage();
            this.showPreOrderErrors = true;
        }
    }
    // ]
}
