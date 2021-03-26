import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';

export default class ValidateCompanyCodeUnique {

    @service store;

    validate(key, newValue, oldValue, changes, content) {

        return this.store.query('company', { filter: { codeExist: newValue }}).then((companies) => {
            if (!isBlank(companies)) {
                let company = companies.firstObject;
                if (company.code === newValue) {
                    // validating edit self company record
                    if (!content.isNew && company.id === content.id) {
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
