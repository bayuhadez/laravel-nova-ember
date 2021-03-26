import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { action } from '@ember/object';
import { tracked } from '@glimmer/tracking';
import { isBlank } from '@ember/utils';

export default class AxxMasterRequestOrdersEditController extends Controller {
    // services
    @service store;
    @service intl;

    @tracked newRequestOrderProducts = [];
    @tracked newRequestOrderProduct = null;

    breadcrumbs = {
        title: this.intl.t('request_order.identifier'),
        route: "axx.purchases.request-orders",
        subNav: [
            {
                name: this.intl.t('request_order.heading.edit'),
            }
        ],
    };

    onSaveRequestOrderForm()
    {
        let self = this;

            return new Promise(function(resolve, reject) {

                // save requestOrder
                return self.get('requestOrder')
                    .save()
                    .then((requestOrder) => {

                        // set relation requestOrder to all newRequestOrderProducts
                        self.get('newRequestOrderProducts').forEach(function (requestOrderProduct) {
                            requestOrderProduct.set('requestOrder', requestOrder);
                        });

                        // save all newRequestOrderProducts
                        Promise.all(
                            self.get('newRequestOrderProducts').map(requestOrderProduct => requestOrderProduct.save())
                        ).then(() => {
                            // refresh requestOrderProducts
                            self.set('refreshData', true);

                            // set empty newRequestOrderProducts
                            self.set('newRequestOrderProducts', []);

                            resolve(requestOrder);
                        })
                        .catch((err) => {
                            reject(err);
                        });

                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
    }

    @action
    saveRequestOrderForm() {
        KTApp.blockPage();

        let qswal = this.qswal.edit();

        // Only save when the footer form is valid or |new and blank|
        if(this.newRequestOrderProduct.get('validations.isValid')
            || (this.newRequestOrderProduct.get('isNew')
            && isBlank(this.newRequestOrderProduct.get('product.id')))) {
            return this
                .onSaveRequestOrderForm()
                .then(() => {
                    KTApp.unblockPage();
                    qswal.s();
                })
                .catch((response) => {
                    KTApp.unblockPage();
                    qswal.e(response.errors[0].detail);
                });
        } else {
            KTApp.unblockPage();
            this.qswal.e(this.newRequestOrderProduct.get('errors'));
            this.newRequestOrderProduct.set('showErrors', true);
        }
    }
}

