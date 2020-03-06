import axios from "axios";

function findAll() {
    return axios.get('http://127.0.0.1:8000/api/results')
        .then(response => response.data['hydra:member'])
};

function deleteResult(id){
    return axios.delete('http://127.0.0.1:8000/api/results/' + id);
};

export default {
    findAll,
    delete: deleteResult
};