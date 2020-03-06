import React from "react";
import ReactDOM from "react-dom";
import {HashRouter, Switch, Route} from "react-router-dom";
import '../css/app.css';
import Navbar from "./components/Navbar";
import LearnersPage from "./pages/LearnersPage";
import InvoicesPage from "./pages/InvoicesPage";
import ResultsPage from "./pages/ResultsPage";
import CoursesPage from "./pages/CoursesPage";
import InstructorsPage from "./pages/InstructorsPage";
import SalariesPage from "./pages/SalariesPage";


console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

const App = () => {
    return <HashRouter>
        <Navbar/>
        <main className="container pt-5">
            <Switch>
                <Route path="/learners" component={LearnersPage} />
                <Route path="/invoices" component={InvoicesPage} />
                <Route path="/results" component={ResultsPage} />
                <Route path="/courses" component={CoursesPage} />
                <Route path="/instructors" component={InstructorsPage} />
                <Route path="/salaries" component={SalariesPage} />
            </Switch>
        </main>
    </HashRouter>;
};

const rootElement = document.querySelector('#app');
ReactDOM.render(<App />, rootElement);
