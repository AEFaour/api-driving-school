import React, {useContext, useState} from "react";
import ReactDOM from "react-dom";
import {HashRouter, Switch, Route, withRouter} from "react-router-dom";
import '../css/app.css';
import Navbar from "./components/Navbar";
import HomePage from "./pages/HomePage";
import LearnerPage from "./pages/LearnerPage";
import LearnersPage from "./pages/LearnersPage";
import InvoicePage from "./pages/InvoicePage";
import InvoicesPage from "./pages/InvoicesPage";
import ResultsPage from "./pages/ResultsPage";
import CoursesPage from "./pages/CoursesPage";
import InstructorsPage from "./pages/InstructorsPage";
import SalariesPage from "./pages/SalariesPage";
import homeLogin from "./methods/homeLogin";
import ContextAuth from "./contexts/ContextAuth";
import ProtectedRoute from "./components/ProtectedRoute";
import AddPage from "./pages/AddPage";


homeLogin.setup();


const App = () => {
    //Verification de la connexion
    const [isAuthenticated, setIsAuthenticated] = useState(homeLogin.isAuthenticated());
    const NavbarRoute = withRouter(Navbar);
    return (
        <ContextAuth.Provider value={{
            isAuthenticated,
            setIsAuthenticated
        }} >
            <HashRouter>
                <NavbarRoute />
                <main>
                    <Switch>
                        <Route path="/home" component={HomePage} />} />
                        <Route path="/add" component={AddPage} />} />
                        <ProtectedRoute path="/learners/:id" component={LearnerPage} />
                        <ProtectedRoute path="/learners" component={LearnersPage} />
                        <ProtectedRoute path="/invoices/:id" component={InvoicePage} />
                        <ProtectedRoute path="/invoices" component={InvoicesPage} />
                        <ProtectedRoute path="/results" component={ResultsPage} />
                        <ProtectedRoute path="/courses" component={CoursesPage} />
                        <ProtectedRoute path="/instructors" component={InstructorsPage} />
                        <ProtectedRoute path="/salaries" component={SalariesPage} />
                    </Switch>
                </main>
            </HashRouter>
        </ContextAuth.Provider>
        );
};

const rootElement = document.querySelector('#app');
ReactDOM.render(<App />, rootElement);
