import React from 'react';
import {makeStyles} from '@material-ui/core/styles';
import AppBar from '@material-ui/core/AppBar';
import Toolbar from '@material-ui/core/Toolbar';
import Typography from '@material-ui/core/Typography';
import Button from '@material-ui/core/Button';


const useStyles = makeStyles(theme => ({
    root: {
        flexGrow: 1,
        fontFamily : 'Times New Roman',
    },
    title: {
        flexGrow: 1,
        fontFamily : 'Times New Roman',
    },
}));

const Navbar = (props) => {

    const classes = useStyles();
    return (
        <div className={classes.root}>
            <AppBar position="static">
                <Toolbar>
                    <Typography variant="h6" className={classes.title}>
                        Auto Ecole
                    </Typography>
                    <Button color="inherit">Stagiaires</Button>
                    <Button color="inherit">Factures</Button>
                    <Button color="inherit">Resultats</Button>
                    <Button color="inherit">Cours</Button>
                    <Button color="inherit">Formateurs</Button>
                    <Button color="inherit">Salaires</Button>
                    <Button color="inherit">Inscription</Button>
                    <Button color="inherit">Connexion</Button>
                    <Button color="inherit">Déconnexion</Button>
                </Toolbar>
            </AppBar>
        </div>
    );
}

export default Navbar;