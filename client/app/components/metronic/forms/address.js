import Component from '@glimmer/component';
import { action } from '@ember/object';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import { isBlank } from '@ember/utils';
import { hash } from 'rsvp';

export default class MetronicFormsAddressComponent extends Component {

    @service store;
    @tracked selectedAddress = null;

    constructor()
    {
        super(...arguments);
        this.addressOptions = this.store.findAll('address');
        this.provinceOptions = this.store.findAll('province');
        this.regencies = this.store.findAll('regency', { include: 'province' });
    }

    get regencyOptions()
    {
        if (!isBlank(this.args.address.province)) {
            return this.regencies.filter((regency) => {
                return regency.get('province.id') == this.args.address.province.get('id');
            });
        } else {
            return this.regencies;
        }
    }

    @action
    setProvince(province)
    {
        if (!isBlank(this.args.address.regency)) {
            let regencyProvince = this.args.address.regency.get('province');
            if (regencyProvince.get('id') !== province.get('id')) {
                this.args.address.regency = null;
            }
        }
        this.args.address.province = province;
    }

    @action
    updateSelectedAddress(address)
    {
        this.selectedAddress = address;
        this.args.updateAddressAction(address);
    }
}
