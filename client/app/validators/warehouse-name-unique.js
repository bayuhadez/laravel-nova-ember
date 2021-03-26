import BaseValidator from 'ember-cp-validations/validators/base';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';

const WarehouseNameUnique = BaseValidator.extend({

    store: service(),

    validate(value, options, model) {

        if (isBlank(value)) {
            return true;
        } else {
            return this.get('store').query('warehouse', {
                filter: { nameExist: value }
            }).then((warehouses) => {

                if (
                    (warehouses.length > 0)
                    && (warehouses.get('firstObject.name') === value)
                ) {
                    if (!model.get('isNew')) {
                        // validate edit form
                        if (warehouses.get('firstObject.id') === model.get('id')) {
                            return true;
                        }
                    }
                    return "Nama sudah digunakan";
                } else {
                    return true;
                }
                
            });
        }
    }
});

WarehouseNameUnique.reopenClass({
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

export default WarehouseNameUnique;
