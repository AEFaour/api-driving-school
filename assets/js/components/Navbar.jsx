import React, {useContext} from 'react';
import {makeStyles} from '@material-ui/core/styles';
import homeLogin from "../methods/homeLogin";
import {
    AppBar, Toolbar, Typography, Button
} from "@material-ui/core";
import {NavLink} from "react-router-dom";
import ContextAuth from "../contexts/ContextAuth";

const useStyles = makeStyles(theme => ({
    root: {
        flexGrow: 1,
        fontFamily: 'Times New Roman',
    },
    title: {
        flexGrow: 1,
        fontFamily: 'Times New Roman',
    },
    navlink: {
        color: theme.palette.common.white,
    },
}));

const Navbar = ({history}) => {
    const {isAuthenticated, setIsAuthenticated} = useContext(ContextAuth);
    const handleLogout = () => {
        homeLogin.logout();
        setIsAuthenticated(false);
        history.push("/home");
    }
    const classes = useStyles();
    return (
        <div className={classes.root}>
            <AppBar position="fixed">
                <Toolbar>
                    <Typography variant="h6" className={classes.title}>
                    <NavLink to="/home" className={classes.navlink}>
                            Auto Ecole
                    </NavLink>
                    </Typography>
                    <Button color="inherit">
                        <NavLink to="/learners" className={classes.navlink}>Stagiaires</NavLink>
                    </Button>
                    <Button color="inherit">
                        <NavLink to="/invoices" className={classes.navlink}>Factures</NavLink>
                    </Button>
                    <Button color="inherit">
                        <NavLink to="/results" className={classes.navlink}>Resultats</NavLink>
                    </Button>
                    <Button color="inherit">
                        <NavLink to="/courses" className={classes.navlink}>Cours</NavLink>
                    </Button>
                    <Button color="inherit">
                        <NavLink to="/instructors" className={classes.navlink}>Formateurs</NavLink>
                    </Button>
                    <Button color="inherit">
                        <NavLink to="/salaries" className={classes.navlink}>Salaires</NavLink>
                    </Button>
                    {(!isAuthenticated && (<>
                        <Button color="inherit" className={classes.navlink}>Inscription</Button>
                        <Button color="inherit">
                            <NavLink to="/home" className={classes.navlink}>Connexion</NavLink>
                        </Button>
                    </>
                    )) || (
                        <Button color="inherit" onClick={handleLogout}>
                            <NavLink to="/home" className={classes.navlink}>Déconnexion</NavLink>
                        </Button>
                    )}
                </Toolbar>
            </AppBar>
        </div>
    );
}

export default Navbar;