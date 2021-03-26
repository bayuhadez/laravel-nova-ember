import Route from '@ember/routing/route';
import { inject as service } from '@ember/service';

export default class AxxPurchasesPurchaseReceiptsIndexRoute extends Route {
    @service currentUser;

    beforeModel() {
        this.currentUser.loadCurrentUser();
    }
}
