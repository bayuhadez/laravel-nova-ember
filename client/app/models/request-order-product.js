import DS from 'ember-data';
import { validator, buildValidations } from 'ember-cp-validations';

const Validations = buildValidations({
    quantity: {
        descriptionKey: 'request_order_product.attr.quantity',
        validators: [
            validator('presence', true),
            validator('number', {
                allowString: true,
            }),
        ],
    },
    information: validator('length', {
        max: 191,
        descriptionKey: 'request_order_product.attr.information',
    }),
    product: {
        descriptionKey: 'request_order_product.rel.product',
        validators: [
            validator('presence', true),
        ],
    },
});

export default DS.Model.extend(Validations, {
    // attributes
    sortingNumber: DS.attr(),
    quantity: DS.attr('number'),
    information: DS.attr('string'),

    // relationships
    requestOrder: DS.belongsTo('request-order'),
    product: DS.belongsTo('product'),
    productUnit: DS.belongsTo('product-unit'),
    unit: DS.belongsTo('unit'),

});
