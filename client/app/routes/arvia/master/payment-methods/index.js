import Route from '@ember/routing/route';

export default class AxxMasterPaymentMethodsIndexRoute extends Route {
    willTransition() {
        this.controller.rollbackAttributes();
    }
}