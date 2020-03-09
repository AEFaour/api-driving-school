import axios from "axios";

/**
 * @returns {AxiosPromise<any>}
 */
function add(learner) {
    return axios.post("http://127.0.0.1:8000/api/learners", learner)
};

/**
 * @param id
 * @param learner
 * @returns {AxiosPromise<any>}
 */
function edit(id, learner) {
    return axios.put("http://127.0.0.1:8000/api/learners/" + id, learner);
};

/**
 * @returns {Promise<AxiosResponse<any>>}
 */
function findAll() {
    return axios.get('http://127.0.0.1:8000/api/learners')
        .then(response => response.data['hydra:member'])
};

/**
 * @returns {Promise<AxiosResponse<any>>}
 */
function findOne(id) {
    return axios
        .get("http://127.0.0.1:8000/api/learners/" + id)
        .then(response => response.data);
};

/**
 * @param id
 * @returns {AxiosPromise}
 */
function deleteLearner(id) {
    return axios.delete('http://127.0.0.1:8000/api/learners/' + id);
};

export default {
    add,
    edit,
    findAll,
    findOne,
    delete: deleteLearner
};

