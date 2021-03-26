import Controller from '@ember/controller';
import { inject as service } from '@ember/service';
import { tracked } from '@glimmer/tracking';
import dtc from 'rcf/utils/dtcolumn';
import { action, computed } from '@ember/object';
import { isEmpty } from '@ember/utils';

export default class AxxMasterProductMetaFieldsIndexController extends Controller {
    // services
    @service store;
    @service intl;

    @tracked refreshData = false;
    formRecordModel = 'product-meta-field';

    @computed('columns.@each.filterValue')
    get filterParameters() {
        let filters = {};

            // filters coming from dt columns
            this.get('columns').forEach(function (column) {
                let filter = column.get('filter');

                if (!isEmpty(column.get('filterValue'))) {
                    filters[filter.scope] = column.get('filterValue');
                }
            });

            return filters;
    }

    @computed()
    get columns() {
        return [
            dtc.create({
                name: this.intl.t('product_meta_field.attr.label'),
                valuePath: 'label',
                filter: {
                    type: 'text',
                    scope: 'labelLike',
                }
            }),
            dtc.create({
                buttons: [
                    {preset: 'edit'},
                    {preset: 'delete'},
                ]
            }),
        ];
    }

    @action
    editProductMetaFieldRecord(record)
    {
        this.transitionToRoute('axx.master.product-meta-fields.edit', record.get('id'));
    }

    @action
    deleteProductMetaFieldRecord(record)
    {
        this.qswal.confirmDelete(() => {
            KTApp.blockPage();
            record.destroyRecord().then(() => {
                KTApp.unblockPage();
                this.qswal.delete().s();
                this.set('refreshData', true);
            }).catch(() => {
                KTApp.unblockPage();
                this.qswal.delete().e();
            });
        });
    }
}

