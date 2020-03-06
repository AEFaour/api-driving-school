import axios from "axios";

function findAll() {
    return axios.get('http://127.0.0.1:8000/api/instructors')
        .then(response => response.data['hydra:member'])
};

function deleteInstructor(id){
    return axios.delete('http://127.0.0.1:8000/api/instructors/' + id);
};

export default {
    findAll,
    delete: deleteInstructor
};