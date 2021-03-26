import BaseValidator from 'ember-cp-validations/validators/base';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';

const CompanyCodeUnique = BaseValidator.extend({

    store: service(),

    validate(value, options, model, attribute) {

        if (isBlank(value)) {
            return true;
        } else {
            return this.get('store').query('company', {
                filter: { codeExist: value }
            }).then((companies) => {

                if (
                    (companies.length > 0)
                    && (companies.get('firstObject.code') === value)
                ) {
                    if (!model.get('isNew')) {
                        // validate edit form
                        if (companies.get('firstObject.id') === model.get('id')) {
                            return true;
                        }
                    }
                    return "Kode sudah digunakan";
                } else {
                    return true;
                }
                
            });
        }
    }
});

CompanyCodeUnique.reopenClass({
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

export default CompanyCodeUnique;
