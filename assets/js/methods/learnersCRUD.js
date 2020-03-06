import axios from "axios";

function findAll() {
    return axios.get('http://127.0.0.1:8000/api/learners')
        .then(response => response.data['hydra:member'])
};

function deleteLearner(id){
    return axios.delete('http://127.0.0.1:8000/api/learners/' + id);
};

export default {
    findAll,
    delete: deleteLearner
};

