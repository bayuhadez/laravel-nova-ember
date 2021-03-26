import Service from '@ember/service';
import { inject as service } from '@ember/service';

class Phone {
    faxNumber = null;
    mobileNumber = null;
    telephoneNumber = null;
}

export default class PhoneServiceService extends Service {

    @service store;

    copyPhoneAttributes(target, source)
    {
        target.faxNumber = source.faxNumber;
        target.mobileNumber = source.mobileNumber;
        target.telephoneNumber = source.telephoneNumber;
    }

    createPhoneClass()
    {
        return new Phone();
    }

}
