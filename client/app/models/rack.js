import DS from 'ember-data';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';
import { computed } from '@ember/object';

export default DS.Model.extend({

    // Service [
    intl: service(),
    // ]

    code: DS.attr('string'),
    name: DS.attr('string'),
    description: DS.attr('string'),
    quantity: DS.attr('number'),

    // Relationships [
    warehouse: DS.belongsTo('warehouse'),
    productStocks: DS.hasMany('product-stock'),
    // ]
});
