import Route from '@ember/routing/route';

export default class AxxPrepareOrReturnProductIndexRoute extends Route {

    willTransition()
    {
        this.controller.set('isPreparingProduct', false);
        this.controller.set('isReturningProduct', false);
    }
}
