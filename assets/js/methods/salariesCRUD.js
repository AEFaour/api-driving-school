import axios from "axios";

function findAll() {
    return axios.get('http://127.0.0.1:8000/api/salaries')
        .then(response => response.data['hydra:member'])
};

function deleteSalary(id){
    return axios.delete('http://127.0.0.1:8000/api/salaries/' + id);
};

export default {
    findAll,
    delete: deleteSalary
};