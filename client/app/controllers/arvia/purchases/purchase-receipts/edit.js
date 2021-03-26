//import Controller from '@ember/controller';
import ENV from 'rcf/config/environment';
import PrAddController from 'rcf/controllers/axx/purchases/purchase-receipts/add';
import { A } from '@ember/array';

export default class AxxPurchasesPurchaseReceiptsEditController extends PrAddController {

    promiseAssignSelectedPreOrders(preOrders)
    {
        let self = this;
        return new Promise((resolve, reject) => {

            let url = (
                ENV.apiUrl + '/' + ENV.apiNamespace + '/'
                + 'purchase-receipts/'
                + self.formRecord.id + '/'
                + 'relationships/pre-orders'
            );

            let preOrderArray = A();

            preOrders
                .forEach(function(preOrder) {
                    preOrderArray.pushObject(
                        { "type": "pre-orders", "id": preOrder.id }
                    );
                });

            let data = JSON.stringify({"data": preOrderArray});

            self.ajaxHelperService
                .ajax({
                    'type': 'POST',
                    'url': url,
                    'data': data,
                    'contentType': false,
                    'processData': false,
                })
                .done((response) => {
                    preOrders.forEach((preOrder) => {
                        self.formRecord.preOrders.pushObject(preOrder);
                    });
                    resolve(response);
                })
                .fail((err) => {
                    reject(err);
                });
        });
    }

    promiseAddNewProductFromPreOrders(newProductTransactionReceipts)
    {
        return Promise.all(
            newProductTransactionReceipts.invoke('save')
        );
    }

    promiseTriggerPurchaseReceiptCalculation()
    {
        let self = this;
        return new Promise((resolve, reject) => {
            let url = (
                ENV.apiUrl + '/' + ENV.apiNamespace + '/'
                + 'purchase-receipts/'
                + self.formRecord.id + '/'
                + 'update-calculation'
            );

            self.ajaxHelperService
                .ajax({
                    'type': 'POST',
                    'url': url,
                    'data': {},
                    'contentType': false,
                    'processData': false,
                })
                .done((response) => {
                    self.store.pushPayload(response);
                    resolve(response);
                })
                .fail((err) => {
                    reject(err);
                });
        });
    }
}
