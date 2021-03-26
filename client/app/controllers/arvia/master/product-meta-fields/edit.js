import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { action } from '@ember/object';

export default class AxxMasterProductMetaFieldsEditController extends Controller {
    // services
    @service store;
    @service intl;

    breadcrumbs = {
        title: this.intl.t('product_meta_field.identifier'),
        route: "axx.master.product-meta-fields",
        subNav: [
            {
                name: this.intl.t('product_meta_field.heading.edit'),
            }
        ],
    };

    @action
    saveProductMetaFieldForm(productMetaField, event) {
        event.preventDefault();
        KTApp.blockPage();

        let qswal = this.qswal.edit();

        return productMetaField
            .save()
            .then(() => {
                KTApp.unblockPage();
                qswal.s();
            })
            .catch((response) => {
                KTApp.unblockPage();
                qswal.e(response.errors[0]);
            });
    }
}

