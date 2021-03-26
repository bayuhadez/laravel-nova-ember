import { Factory, faker } from 'ember-cli-mirage';

export default Factory.extend({

    'email'() {
        return faker.internet.email();
    },
    'password'() {
        return faker.name.firstName();
    },
    'name'() {
        return faker.name.firstName();
    },

});
