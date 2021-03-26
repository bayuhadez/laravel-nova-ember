import Component from '@ember/component';
import { inject as service } from '@ember/service';
import { isBlank, isEmpty } from '@ember/utils';
import { A } from '@ember/array';
import EmberObject from '@ember/object';
import { observer, computed } from '@ember/object';
import { hash } from 'rsvp';
import dtc from 'rcf/utils/dtcolumn';

export default Component.extend({
    store: service(),
    intl: service(),
    companyRepository: service('repositories/company-repository'),
    currentUser: service(),
    pageTitle: computed(function () {
        return (this.product.isNew ? "product.heading.add" : "product.heading.edit");
    }),

    spawnOpmf(productMetaField)
    {
        let selectedProductMetaValue;

        this.get('product.productMetaValues').forEach((pmv) => {
            if (pmv.get('productMetaField.id') === productMetaField.get('id')) {
                selectedProductMetaValue = pmv;
            }
        });

        return EmberObject.create({
            label: productMetaField.get('label'),
            productMetaField: productMetaField,
            selectedProductMetaValue: selectedProductMetaValue,
        });
    },

    getProductCategoryAsOption(productCategory)
    {
        if (productCategory.get('children.length') > 0) {
            var options = A();

            productCategory.get('children').forEach((child) => {
                options.pushObject(this.getProductCategoryAsOption(child));
            });

            return EmberObject.create({
                groupName: productCategory.get('name'),
                options: options.toArray(),
            });
        } else {
            return productCategory;
        }
    },

    didReceiveAttrs()
    {
        this.activeTab = "tab_opmf";
        // fetch product categories
        this.store.query(
            'product-category',
            {include:'parent,children'}
        ).then((productCategories) => {
            let productCategoryOptions = A();

            // get all root categories
            let roots = productCategories.filter((productCategory) => {
                return isBlank(productCategory.get('parent.id'));
            });

            roots.forEach((root) => {
                productCategoryOptions.pushObject(this.getProductCategoryAsOption(root));
            });

            this.set('productCategories', productCategoryOptions);
        });

        this.store.findAll('expedition').then(expeditions => {
            this.set('expeditions', expeditions);
        })

        // fetch units and remove existing unit from options
        this.store.findAll('unit').then(units => {
            this.set('units', units);
        });

        this.store.query('unit', {
            filter: { notUsedByProduct: this.product.id }
        }).then(units => {
            this.set('unitOptions', A());
            units.forEach((unit) => {
                this.unitOptions.pushObject(unit);
            })
        });

        this.store.query('company', {
            filter: { weAndChildren: this.currentUser.getCompanyIds(), notUsedByProduct: this.product.id }
        }).then(async (companies) => {
            await this.set('companies', companies);
            this.set('companyOptions', A());
            this.companies.forEach((company) => {
                this.companyOptions.pushObject(company);
            });
        });

        // fetch product meta fields
        let productMetaFields = this.store.findAll('product-meta-field', {
            include: 'product-meta-field-group,product-meta-values'
        });

        let productWithPmvsLoaded = this.get('product.productMetaValues');
        let productWithCompanyProductsLoaded = this.get('product.companyProducts');
        let productWithExpeditionProductsLoaded = this.get('product.expeditionProducts');

        hash({
            productMetaFields: productMetaFields,
            productWithPmvsLoaded: productWithPmvsLoaded,
            productWithCompanyProductsLoaded: productWithCompanyProductsLoaded,
            productWithExpeditionProductsLoaded: productWithExpeditionProductsLoaded,
        })
        .then((hash) => {
            let productMetaFields = hash.productMetaFields;

            let ungroupedOpmfs = A();
            let opmfGroups = A();

            let productMetaFieldGroups = this.store.peekAll('product-meta-field-group');

            // start building custom opmfGroups tailored for use in the ui
            productMetaFieldGroups.forEach((productMetaFieldGroup) => {
                let opmfs = A();

                productMetaFieldGroup.get('productMetaFields')
                    .forEach((productMetaField) => {
                        opmfs.pushObject(this.spawnOpmf(productMetaField));
                    });

                opmfGroups.pushObject(EmberObject.create({
                    name: productMetaFieldGroup.get('name'),
                    id: productMetaFieldGroup.get('id'),
                    opmfs: opmfs,
                }));
            });

            productMetaFields.forEach((productMetaField) => {

                // create custom product meta
                // field object tailored for use in the ui
                let opmf = this.spawnOpmf(productMetaField);

                if (isBlank(productMetaField.get('productMetaFieldGroup.id'))) {
                    ungroupedOpmfs.pushObject(opmf);
                }
            });

            this.addRecordIfCollectionIsFull(
                'product-unit',
                this.get('product.productUnits'),
                'unit.id',
                true
            ).then((pu) => {
                pu.set('product', this.product);
                // mark extraProductUnit as primary if
                // added productUnit is the only record
                if (this.get('product.productUnits.length') === 1) {
                    this.get('product.productUnits.firstObject').set('isPrimary', true);
                } else {
                    pu.set('convertedUnit', this.get('product.productUnits.firstObject.unit'));
                }
            });


            this.addRecordIfCollectionIsFull(
                'company-product',
                this.get('product.companyProducts'),
                'company.id'
            );

            this.addRecordIfCollectionIsFull(
                'expedition-product',
                this.get('product.expeditionProducts'),
                'expedition.id'
            );

            this.get('product')
                .setProperties({
                    ungroupedOpmfs: ungroupedOpmfs,
                    opmfGroups: opmfGroups,
                    unitSelected : null,
                });
        });
    },

    async addRecordIfCollectionIsFull(modelName, collection, propertyCheck, shouldReturn = false)
    {
        let hasEmpty = !isBlank(collection.filter(item => {
            return isBlank(item.get(propertyCheck));
        }));

        if(!hasEmpty) {
            let record = this.store.createRecord(modelName);
            collection.pushObject(await record);
            if(shouldReturn) {
                return await record;
            }
        }
    },

    async deleteRecord(record, type = null)
    {
        this
            .qswal
            .confirmDelete(async () => {
                if(type === 'companyProduct'){
                    let company = await record.get('company');
                    this.companyOptions.pushObject(await company);
                } else if(type === 'productUnit') {
                    let unit = await record.get('unit');
                    this.unitOptions.pushObject(await unit);
                }
                KTApp.blockPage();
                record
                    .destroyRecord()
                    .then(() => {
                        KTApp.unblockPage();
                        this.qswal.delete().s();
                    })
                    .catch(() => {
                        KTApp.unblockPage();
                        this.qswal.delete().e();
                    });
            });

        return false;
    },

    removeCompanyOptionByIndex(company) {
        let index = 0;
        this.companyOptions.forEach(async (companyOption) => {
            if(await companyOption.get('id') == await company.get('id')) {
                this.companyOptions.removeAt(index);
            }
            index++;
        })
    },

    actions: {
        addProductMetaValue(productMetaField, value)
        {
            return this.store.createRecord('product-meta-value', {
                productMetaField: productMetaField,
                value: value,
            }).save();
        },

        addUnit(name) {
            return this.store.createRecord('unit', {
                name : name,
            }).save();
        },

        addExpedition(name)
        {
            return this.store.createRecord('expedition', {
                name : name,
            }).save();
        },

        addProductCategory(value)
        {
            return this.store.createRecord('product-category', {
                name: value,
            }).save();
        },

        onPhotoChanged(file)
        {
            this.set('product.imageFile', file);
        },

        deleteProductUnit(productUnit)
        {
            this.deleteRecord(productUnit, 'productUnit');
            this.addRecordIfCollectionIsFull(
                'product-unit',
                this.get('product.productUnits'),
                'unit.id'
            );
        },

        deleteExpeditionProduct(expeditionProduct)
        {
            this.deleteRecord(expeditionProduct);
            this.addRecordIfCollectionIsFull(
                'expedition-product',
                this.get('product.expeditionProducts'),
                'expedition.id'
            );
        },

        deleteCompanyProduct(companyProduct)
        {
            this.deleteRecord(companyProduct, 'companyProduct');
            this.addRecordIfCollectionIsFull(
                'company-product',
                this.get('product.companyProducts'),
                'company.id'
            );
        },

        onSelectExpeditionProduct(expeditionProduct, expedition)
        {
            expeditionProduct.set('expedition', expedition);

            this.addRecordIfCollectionIsFull(
                'expedition-product',
                this.get('product.expeditionProducts'),
                'expedition.id'
            );
        },

        async onSelectCompanyProduct(companyProduct, company)
        {
            if(!isBlank(await companyProduct.get('company.id'))) {
                await this.removeCompanyOptionByIndex(company);
                if(await companyProduct.get('company.id') != await company.get('id')){
                    this.companyOptions.pushObject(await companyProduct.get('company'));
                }
                companyProduct.set('company', company);
            } else {
                await this.removeCompanyOptionByIndex(company);
                companyProduct.set('company', company);
            }

            this.addRecordIfCollectionIsFull(
                'company-product',
                this.get('product.companyProducts'),
                'company.id'
            );

            if (!companyProduct.price) {
                companyProduct.set('price', this.product.price);
            }
        },

        async onSelectProductUnit(productUnit, unit)
        {
            if(!isBlank(await productUnit.get('unit.id'))) {
                this.get('unitOptions').removeObject(unit);
                if(await productUnit.get('unit.id') != await unit.get('id')){
                    this.unitOptions.pushObject(await productUnit.get('unit'));
                }
                productUnit.set('unit', unit);
            } else {
                this.get('unitOptions').removeObject(unit);
                productUnit.set('unit', unit);
            }

            if (!this.product.isNew) {
                productUnit.save();
            }

            let hasEmpty = !isBlank(this.get('product.productUnits').filter(item => {
                return isBlank(item.get('unit.id'));
            }));
    
            if(!hasEmpty) {
                let record = this.store.createRecord('product-unit');
                record.set('convertedUnit', this.get('product.productUnits.firstObject.unit'));
                record.set('product', this.get('product'));
                this.get('product.productUnits').pushObject(record);
            }
        },

        onSelectConvertedUnit(productUnit, unit)
        {
            if(!isBlank(productUnit.get('unit.id')) && unit.get('id') != productUnit.get('unit.id')) {
                productUnit.set('convertedUnit', unit);
                if (!this.product.isNew) {
                    productUnit.save();
                }
            } else {
                this.qswal.manual('Gagal', "Satuan yang dipilih harus berbeda", 'error');
            }
        },

        saveProductUnit(productUnit)
        {
            if(!isBlank(productUnit.get('unit.id'))) {
                if (!this.product.isNew) {
                    productUnit.save();
                }
            }
        },

        saveStockFIFOForm(event)
        {
            //
        }
    }
});
