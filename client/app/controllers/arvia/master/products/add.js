import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import { action, computed } from '@ember/object';
import { isBlank, isEmpty } from '@ember/utils';
import { A } from '@ember/array';

export default class AxxMasterStaffsAddController extends Controller {
    // services
    @service store;
    @service intl;

    breadcrumbs = {
        title: this.intl.t('product.identifier'),
        route: "axx.master.products",
        subNav: [
            {
                name: this.intl.t('product.heading.add'),
            }
        ],
    };

    onSaveProductForm()
    {
        let productMetaValues = A();

        // save grouped opmfs
        this.get('product.opmfGroups').forEach((opmfGroup) => {

            opmfGroup.get('opmfs').forEach((opmf) => {
                let productMetaValue = opmf.get('selectedProductMetaValue');

                if (!isBlank(productMetaValue)) {
                    productMetaValues.pushObject(productMetaValue);
                }
            });

        });

        // add ungrouped opmfs
        this.get('product.ungroupedOpmfs').forEach((opmf) => {

            let productMetaValue = opmf.get('selectedProductMetaValue');

            if (!isBlank(productMetaValue)) {
                productMetaValues.pushObject(productMetaValue);
            }

        });

        this.set('product.productMetaValues', productMetaValues);

        return this.get('product')
            .save()
            .then((product) => {
                this.get('product.expeditionProducts').forEach(expeditionProduct => {
                    if(!isBlank(expeditionProduct.get('expedition.id'))) {
                        expeditionProduct.set('product', product);
                        return expeditionProduct.save();
                    } else {
                        expeditionProduct.rollbackAttributes();
                    }
                })
                .then(() => {
                    this.get('product.companyProducts').forEach(companyProduct => {
                        if(!isBlank(companyProduct.get('company.id'))) {
                            companyProduct.set('product', product)
                            return companyProduct.save();
                        } else {
                            companyProduct.rollbackAttributes();
                        }
                })
                .then(() => {
                    this.get('product.productUnits')
                        .forEach(productUnit => {
                            if(!isBlank(productUnit.get('unit.id'))) {
                                productUnit.set('product', product);
                                return productUnit.save();
                            } else {
                                productUnit.rollbackAttributes();
                            }
                        })
                    })
                });
            })
    }

    @action
    saveProductForm(event) {
        event.preventDefault();
        KTApp.blockPage();

        let qswal = this.qswal.create();

        return this
            .onSaveProductForm()
            .then(() => {
                KTApp.unblockPage();
                qswal.s();
                this.transitionToRoute('axx.master.products.edit', this.get('product.id'));
            })
            .catch(() => {
                KTApp.unblockPage();
                qswal.e(this.get('product.errors'));
            });
    }
}

