import axios from "axios";
import jwtDecode from "jwt-decode";

/**
 * token JWT sur axios
 * @param token
 */
function setAxiosToken(token) {
    axios.defaults.headers["Authorization"] = "Bearer " + token;
}

/**
 * Requête http post pour authentification et stockage du token (localStorage & axios)
 * @param credentials
 * @returns {Promise<boolean>}
 */
function authLogin(credentials) {
     return axios
         .post('http://127.0.0.1:8000/api/login_check', credentials)
        .then(response => response.data.token)
        .then(token => {
            // Pour stocker le token dans le localStorage
            window.localStorage.setItem("authToken", token);
            // Pour envoyer le token à axios afin de la stocker dans header par default de toutes les rêquetes
            setAxiosToken(token);
        });
}

/**
 * Déconnexion et suppression du token du localStorage et sur axios
 */
function logout() {
    window.localStorage.removeItem("authToken");
    delete axios.defaults.headers["Authorization"];
}

/**
 * Mise à jour de l'application
 */

function setup() {
    const token = window.localStorage.getItem("authToken");
    if (token){
        const {exp : expiration} = jwtDecode(token);
        if(expiration * 1000 > new Date().getTime()){
            setAxiosToken(token);
            console.log("Connect to axios!");
        }
    }
}

/**
 * Verification de l'authentification
 * @returns {boolean}
 */
function isAuthenticated() {
    const token = window.localStorage.getItem("authToken");
    if (token){
        const {exp : expiration} = jwtDecode(token);
        if(expiration * 1000 > new Date().getTime()){
            return true;
        }
        return false;
    }
    return false;
}

export default { authLogin, logout, setup, isAuthenticated };

