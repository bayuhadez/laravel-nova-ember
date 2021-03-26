import Model, { attr, belongsTo, hasMany } from '@ember-data/model';
import { computed } from '@ember/object';

export default class ProductStockModel extends Model {

    @attr('date') datetime;
    @attr('number') quantity;
    @attr('date') createdAt;
    @attr('date') updatedAt;
    @attr('number', { defaultValue: 0 }) _tempTakenAmount;
    @attr('number', { defaultValue: 0 }) _tempReturnedAmount;
    @attr('number') tempQuantity;
    @attr('number') _tempInitialQuantity;

    @attr('boolean', { defaultValue: true }) _tempShowDisplayNameAndDivision;
    @attr('boolean', { defaultValue: true }) _tempShowStockDivisionName;

    @belongsTo('product') product;
    @belongsTo('rack') rack;
    @belongsTo('stock-division') stockDivision;
    @hasMany('product-stock-movement') productStockMovements;

    get rackAndWarehouseName()
    {
        return this.rack.get('name') + " (" + this.rack.get('warehouse.name') + ")";
    }

    get formattedDatetime()
    {
        let date = this.datetime;
        let format = 'DD/MM/YYYY HH:mm';
        return moment(date).format(format);
    }

    @computed('quantity', '_tempInitialQuantity')
    get availableSlot()
    {
        return this._tempInitialQuantity - this.quantity;
    }

}
