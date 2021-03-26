import Model, { attr, belongsTo, hasMany } from '@ember-data/model';
import { computed } from '@ember/object';
import { inject as service } from '@ember/service';

export default class CompanyAddressModel extends Model {

    @service intl;
    @attr('boolean') isDefault;
    @attr('date') createdAt;
    @attr('date') updatedAt;

    @belongsTo('address') address;
    @belongsTo('company') company;

    @computed('address.address')
    get addressName()
    {
        return this.address.get('address');
    }

    @computed('isDefault')
    get isDefaultText()
    {
        if (this.isDefault) {
            return this.intl.t('yes');
        }
        return this.intl.t('no');
    }
}
