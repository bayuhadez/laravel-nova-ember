import Component from '@ember/component';
import dtc from 'rcf/utils/dtcolumn';
import RackValidation from 'rcf/validations/rack';
import { inject as service } from '@ember/service';
import { A } from '@ember/array';
import { isBlank } from '@ember/utils';
import { computed } from '@ember/object';
import { alias } from '@ember/object/computed';
import { once } from '@ember/runloop';

export default Component.extend({

    /**
     * This component provide template of warehouse form
     * 1. Add/Edit warehouse record & rack crud operation
     * 2. Can create warehouse-category record from this form
     *
     * How to use this component ?
     * 1. pass warehouse model
     * 2. pass array of company model into companyOptions attribute
     * 3. pass array of warehouse-category model into warehouseCategoryOptions attribute
     * 4. should be called within component form-page since this component dependent with that component saveRecord method
     */

    // services
    intl: service(),
    store: service(),
    RackValidation: RackValidation,

    warehouse: null,
    warehouseCategoryOptions: A(),
    warehouseRacks: alias('warehouse.racks'),
    companyOptions: A(),
    refreshDt: false,
    warehouseShowErrors: false,
    rackShowErrors: false,
    isEditingRack: false,

    // base rack record
    formRackRecord: null,

    rackColumns: computed(function () {
        return [
            dtc.create({
                name: this.intl.t('rack.attr.code'),
                valuePath: 'code',
            }),
            dtc.create({
                name: this.intl.t('rack.attr.name'),
                valuePath: 'name',
            }),
            dtc.create({
                name: this.intl.t('rack.attr.quantity'),
                valuePath: 'quantity',
            }),
            dtc.create({
                name: this.intl.t('rack.attr.description'),
                valuePath: 'description',
            }),
            dtc.create({
                buttons: [
                    {preset: 'edit'},
                    {preset: 'delete'},
                ]
            })
        ];
    }),

    rackIncludeParameters: 'warehouse',

    rackFilterParameters: computed('warehouse', function() {
        let filters = {};
        if (!this.get('warehouse.isNew')) {
            filters.inWarehouse = this.get('warehouse.id');
        } else {
            filters.inWarehouse = null;
        }
        return filters;
    }),

    actions: {
        addWarehouseCategory(name)
        {
            return this.store.createRecord('warehouse-category', {
                name: name
            }).save();
        },
        addRack()
        {
            this.set('formRackRecord', this.store.createRecord('rack', { warehouse: this.get('warehouse') }));
            this.set('isEditingRack', true);
        },
        async saveRack(rackChangeset, event)
        {
            event.preventDefault();
            await rackChangeset.validate();
            this.set('rackShowErrors', false);

            if (rackChangeset.isValid) {
                // save changeset record
                rackChangeset.save().then(() => {
                    this.set('refreshDt', true);
                    this.set('formRackRecord', null);
                    this.set('isEditingRack', false);
                });
            } else {
                this.set('rackShowErrors', true);
            }
        },
        editRack(rack)
        {
            this.set('formRackRecord', rack);
            this.set('isEditingRack', true);
        },
        deleteRack(rack)
        {
            this.qswal.confirmDelete(() => {
                KTApp.blockPage();
                rack.destroyRecord().then(() => {
                    KTApp.unblockPage();
                    this.qswal.delete().s();
                }).catch(() => {
                    KTApp.unblockPage();
                    this.qswal.delete().e();
                });
            });
        },
        cancelRack(rackChangeset, event)
        {
            event.preventDefault();
            this.set('rackShowErrors', false);
            this.set('isEditingRack', false);
            this.get('formRackRecord').rollbackAttributes();
            this.set('formRackRecord', null);
        }
    },

});
