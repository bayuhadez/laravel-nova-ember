import Component from '@ember/component';
import { schedule } from '@ember/runloop';
import { computed, observer } from '@ember/object';
import { A } from '@ember/array';
import { inject as service } from '@ember/service';
import { task, timeout } from 'ember-concurrency';
import { isBlank } from '@ember/utils';

export default Component.extend({
    intl: service(),
    firstPageNumber: 1,
    currentPageNumber: 0,
    pageSize: 10,
    store: service(),
    refreshData: false,
    suspendGetData: false,
    includeParameters: '',
    additionalRows: [],
    fieldParameters: '',

    routeLinkAction() {},

    init()
    {
        this._super(...arguments);
    },

    didInsertElement() {
        schedule('afterRender', () => {
            this.$('table').addClass('table table-bordered table-hover');

            // fetch first page data
            this.setProperties({
                currentPageNumber: 1,
            });
        });
    },

    visibleColumns: computed('columns.@each.visible', function () {
        let visibleColumns = [];

        this.get('columns').forEach((column) => {
            if (column.isVisible || isBlank(column.isVisible)) {
                visibleColumns.push(column);
            }
        });

        return A(visibleColumns);
    }),

    onDataParameterChanged: observer(
        'currentPageNumber',
        'pageSize',
        'filterParameters',
        'includeParameters',
        'refreshData',
        function () {
            if (this.get('suspendGetData')) {
                this.toggleProperty('suspendGetData');
            } else {
                this.getData.perform(
                    {
                        page: this.get('currentPageNumber'),
                        size: this.get('pageSize'),
                    },
                    this.get('filterParameters'),
                    this.get('includeParameters'),
                    this.get('fieldParameters')
                );
            }
        }
    ),

    getData: task(function*(options, filterParameters, includeParameters, fieldParameters) {
        yield timeout(300);

        KTApp.block(this.element, {
            overlayColor: '#000000',
            type: 'v2',
            state: 'success',
            message: this.intl.t('message.loading')
        });

        this.set('isLoading', true);

        var query = {};

        // prepare query for fetching data
        // start with pagination parameters
        if (!isBlank(includeParameters)) {
            query = {
                page: {
                    number: options.page,
                    size: options.size
                },
                filter: filterParameters,
                include: includeParameters
            };
        } else {
            query = {
                page: {
                    number: options.page,
                    size: options.size
                },
                filter: filterParameters
            };
        }

        if (!isBlank(fieldParameters)) {
            query['fields'] = fieldParameters;
        }

        let data = yield this.get('store').query(
            this.get('modelName'),
            query
        );

        if (this.get('refreshData')) {
            this.setProperties({
                suspendGetData: true,
                refreshData: false,
            });
        }

        // in case the data is empty and we are not in page 1, resend the request.
        // this could happen for example after going to page 4 and then filtering
        // from a column where the result doesnt reach page 4
        if (data.length < 1 && this.get('currentPageNumber') != 1) {
            this.set('currentPageNumber', 1);
        }

        let meta = data.meta.page;

        this.setProperties({
            'recordsFrom': meta.from,
            'recordsTo': meta.to,
            'recordsTotal': meta.total,
            'rows': data,
            'isLoading': false,
            'lastPageNumber': meta['last-page'],
        }); 

        KTApp.unblock(this.element);
    }).restartable(),

    visiblePageNumbers: computed('currentPageNumber', function () {
        let pageNumbers = A();

        let current = this.get('currentPageNumber');

        if ((current - 2) > 0) {
            pageNumbers.pushObject(current - 2);
        }

        if ((current - 1) > 0) {
            pageNumbers.pushObject(current - 1);
        }

        pageNumbers.pushObject(current);

        if ((current + 1) <= this.get('lastPageNumber')) {
            pageNumbers.pushObject(current + 1);
        }

        if ((current + 2) <= this.get('lastPageNumber')) {
            pageNumbers.pushObject(current + 2);
        }

        return pageNumbers;
    }),

    actions: {
        goToNextPage()
        {
            this.set('currentPageNumber', this.get('currentPageNumber') + 1);
        },

        goToPreviousPage()
        {
            this.set('currentPageNumber', this.get('currentPageNumber') - 1);
        },

        goToFirstPage()
        {
            this.set('currentPageNumber', this.get('firstPageNumber'));
        },

        goToPage(pageNumber)
        {
            this.set('currentPageNumber', pageNumber);
        },

        goToLastPage()
        {
            this.set('currentPageNumber', this.get('lastPageNumber'));
        }

    }
});
