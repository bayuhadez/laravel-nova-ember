import BaseValidator from 'ember-cp-validations/validators/base';

const VoucherAvailable = BaseValidator.extend({
  store: Ember.inject.service(),
  
  validate(value) {
    return this.get('store').query('voucher', {
      filter: { name: value }
    })
    .then((result) => {
      if (result.get('length') === 0) {
        return "Voucher tidak tersedia";
      } else {
        return true;
      }
    });
  }
});

VoucherAvailable.reopenClass({
  /**
   * Define attribute specific dependent keys for your validator
   *
   * [
   * 	`model.array.@each.${attribute}` --> Dependent is created on the model's context
   * 	`${attribute}.isValid` --> Dependent is created on the `model.validations.attrs` context
   * ]
   *
   * @param {String}  attribute   The attribute being evaluated
   * @param {Unknown} options     Options passed into your validator
   * @return {Array}
   */
  getDependentsFor(/* attribute, options */) {
    return [];
  }
});

export default VoucherAvailable;
