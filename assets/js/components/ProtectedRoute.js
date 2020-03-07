import React, {useContext} from "react";
import ContextAuth from "../contexts/ContextAuth";
import {Redirect, Route} from "react-router-dom";

const ProtectedRoute = ({path, component}) => {
    const { isAuthenticated } = useContext(ContextAuth);

    return isAuthenticated? (<Route path={path} component={component} />) : (<Redirect to="/home" />);
}

export default ProtectedRoute;