import Component from '@ember/component';
import { inject as service } from '@ember/service';
import { isBlank } from '@ember/utils';
import { observer } from '@ember/object';
import { A } from '@ember/array';

export default Component.extend({
    store : service(),
    intl: service(),

    init()
    {
        this._super(...arguments);
        this.set('filterParameters', {
            inCompany: this.get('formRecord.id')
        });

        this.addStockDivisionIfFull();
    },

    addStockDivisionIfFull()
    {
        let stockDivisions = this.get('formRecord.stockDivisions');

        let hasEmpty = !isBlank(stockDivisions.filter(item => {
            return isBlank(item.get('name'));
        }));

        if (!hasEmpty) {
            let record = this.store.createRecord('stock-division', {
                company: this.get('formRecord'),
            });
            stockDivisions.pushObject(record);
        }
    },

    onStockDivisionsNameChanged: observer('formRecord.stockDivisions.@each.name', function () {
        this.addStockDivisionIfFull();
    }),

    actions: {
        deleteStockDivision(stockDivision)
        {
            this
                .qswal
                .confirmDelete(() => {
                    KTApp.blockPage();
                    stockDivision
                        .destroyRecord()
                        .then(() => {
                            KTApp.unblockPage();
                            this.qswal.delete().s();
                            this.addStockDivisionIfFull();
                        })
                        .catch(() => {
                            KTApp.unblockPage();
                            this.qswal.delete().e();
                        });
                });

            return false;
        },
    },

    didDestroyElement()
    {
        let toDestroy = A();

        this.formRecord.stockDivisions.forEach(function (stockDivision) {
            // remove all stockDivision having blank name or unsaved
            if (isBlank(stockDivision.get('name')) || stockDivision.get('isNew')) {
                toDestroy.pushObject(stockDivision);
            } else {
                // make sure to rollback all on modal close
                stockDivision.rollbackAttributes();
            }
        });

        toDestroy.invoke('destroyRecord');
    }
});
