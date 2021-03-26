import Service from '@ember/service';

export default class RepositoriesSalesOrderService extends Service {
    save(salesOrder)
    {
        return salesOrder.save().then(() => {
            salesOrder.productSalesOrders.invoke('save');
            salesOrder.salesOrderServices.invoke('save');
        });
    }
}
