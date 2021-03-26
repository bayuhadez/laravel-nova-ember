import Model, { attr, hasMany } from '@ember-data/model';

export default class PaymentMethodModel extends Model {

    @attr('string') code;
    @attr('string') name;
    @attr('date') createdAt;
    @attr('date') updatedAt;

    @hasMany('wallet') wallets;
}
