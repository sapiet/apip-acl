require('../css/app.css');

import Axios from 'axios';

(async () => {

    const entrypoint = document.body.dataset['entrypoint'];
    const jwt = document.body.dataset['jwt'];

    const ApiClient = Axios.create({
        headers: {
            Authorization: `Bearer ${jwt}`,
        }
    });

    const orders = await ApiClient.get(entrypoint)
        .then(response => response.data.order)
        .then(endpoint => ApiClient.get(endpoint))
        .then(response => response.data);

    console.log(orders);
})();
