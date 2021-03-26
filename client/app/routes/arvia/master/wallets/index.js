import Route from '@ember/routing/route';

export default class AxxMasterWalletsIndexRoute extends Route {
    willTransition() {
        this.controller.rollbackAttributes();
    }
}