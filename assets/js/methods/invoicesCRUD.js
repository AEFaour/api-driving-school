import axios from "axios";

/**
 * @param id
 */
function add(invoice) {
     return  axios.post('http://127.0.0.1:8000/api/invoices', {
        ...invoice,
        learner: `/api/learners/${invoice.learner}`
    });

}

/**
 * @param id
 * @param invoice
 */
function edit(id, invoice) {
    return axios.put('http://127.0.0.1:8000/api/invoices/' + id, {
        ...invoice,
        learner: `/api/learners/${invoice.learner}`
    });
};

/**
 * @returns {Promise<AxiosResponse<any>>}
 */
function findAll() {
    return axios.get('http://127.0.0.1:8000/api/invoices')
        .then(response => response.data['hydra:member'])
};

/**
 * @param id
 * @returns {Promise<AxiosResponse<any>>}
 */
function findOne(id) {
    return axios
        .get("http://127.0.0.1:8000/api/invoices/" + id)
        .then(response => response.data);
};

/**
 * @param id
 * @returns {AxiosPromise}
 */
function deleteInvoice(id){
    return axios.delete('http://127.0.0.1:8000/api/invoices/' + id);
};

export default {
    add,
    edit,
    findAll,
    findOne,
    delete: deleteInvoice
};