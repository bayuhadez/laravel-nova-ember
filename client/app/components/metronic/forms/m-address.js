import Component from '@glimmer/component';
import { action, computed } from '@ember/object';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';

export default class MetronicFormsMAddressComponent extends Component {

    @service store;

    constructor()
    {
        super(...arguments);
        this.addressOptions = this._loadAddressOptions();
    }

    async _loadAddressOptions()
    {
        return await this.store.findAll('address');
    }

    get provinceOptions()
    {
        KTApp.blockPage();
        return this.store.findAll('province').finally(() => {
            KTApp.unblockPage();
        });
    }

    @computed('args.address.province.id')
    get regencyOptions()
    {
        if (!isBlank(this.args.address.province.get('id'))) {
            var promise = this.store.query('regency', {
                filter: { inProvince: this.args.address.province.get('id') },
                include: 'province' 
            });
        } else {
            var promise = this.store.findAll('regency', { include: 'province' });
        }
        KTApp.blockPage();
        return promise.finally(() => {
            KTApp.unblockPage();
        });
    }

    @action
    setRegency(regency)
    {
        this.args.address.regency = regency;
    }

    @action
    setProvince(province)
    {
        if (!isBlank(this.args.address.regency.get('id'))) {
            if (this.args.address.regency.get('province.id') !== province.get('id')) {
                this.args.address.regency = null;
            }
        }

        this.args.address.province = province;
    }

}
