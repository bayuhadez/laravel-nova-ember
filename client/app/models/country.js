import Model, { attr, belongsTo, hasMany } from '@ember-data/model';
import { computed } from '@ember/object';

export default class CountryModel extends Model {

    // attributes:
    @attr('string') iso;
    @attr('string') name;
    @attr('number') phonecode;
    @attr('boolean') isActive;

    // functions
    @computed('iso')
    get isIndonesia() {
        return this.get('iso') === 'ID';
    }

}
