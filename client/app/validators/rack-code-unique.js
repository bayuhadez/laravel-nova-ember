import fetch from 'fetch';
import ENV from '../config/environment';

export default function validateRackCodeUnique() {

    return (key, newValue, oldValue, changes, content) => {

        if (newValue == oldValue) {
            return true;
        }

        return new Promise((resolve, reject) => {
            fetch(
                ENV.apiUrl + '/api/v1/check-rack-code-unique', 
                { 
                    method: 'POST', 
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        code: newValue,
                    })
                }
            ).then(function (response) {
                if (response.ok) {
                    response.json().then((json) => {
                        if (json.isValid) {
                            resolve(true);
                        } else {
                            resolve('Kode Rak sudah digunakan');
                        }
                    });
                } else {
                    resolve('Something went wrong, please try again');
                }
            }).catch(function (response) {
                reject('Something went wrong, please try again');
            });
        });
    };
}
