import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { action } from '@ember/object';

export default class AxxMasterProductMetaFieldsAddController extends Controller {
    // services
    @service store;
    @service intl;

    breadcrumbs = {
        title: this.intl.t('product_meta_field.identifier'),
        route: "axx.master.product-meta-fields",
        subNav: [
            {
                name: this.intl.t('product_meta_field.heading.add'),
            }
        ],
    };

    @action
    saveProductMetaFieldForm(productMetaField, event) {
        event.preventDefault();
        KTApp.blockPage();

        let qswal = this.qswal.create();

        return productMetaField
            .save()
            .then(() => {
                KTApp.unblockPage();
                qswal.s();
                this.transitionToRoute('axx.master.product-meta-fields.edit', productMetaField.get('id'));
            })
            .catch((response) => {
                KTApp.unblockPage();
                qswal.e(response.errors[0]);
            });
    }
}

