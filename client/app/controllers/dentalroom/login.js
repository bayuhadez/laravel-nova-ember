import Controller from '@ember/controller';

export default Controller.extend({

  actions: {
    loggedIn() {
      this.transitionToRoute(this.r('dashboard'));
    }
  }
});
