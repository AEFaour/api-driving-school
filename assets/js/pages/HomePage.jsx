import React, {useContext, useState} from "react";
import {makeStyles} from '@material-ui/core/styles';
import {
    Card, Typography, FormControl, Box,
    Button, Container, TextField
} from "@material-ui/core";
import HomeLogin from "../methods/homeLogin";
import ContextAuth from "../contexts/ContextAuth";

const useStyles = makeStyles(theme => ({
    root: {
        flexGrow: 1,
        align: 'center',
        marginTop: 100,
    },
    title: {
        minHeight: 100,
        marginTop: 75,
    },
    btn: {
        margin: theme.spacing(1),
    },
    login: {
        marginBottom: 0,
    },
    typo: {
        fontFamily: 'Times New Roman',
        marginTop: 15,
        marginBottom: 15,
    },
    valid: {
        fontFamily: 'Times New Roman',
    }
}));

const HomePage = ({history}) => {
    console.log(history);

    const { setIsAuthenticated } = useContext(ContextAuth);

    const [credentials, setCredentials] = useState({
        username: "",
        password: ""
    });
    const [error, setError] = useState("");

    // Init et intégration des valeurs dans leschamps
    const handleChange = ({currentTarget}) => {
        const {value, name} = currentTarget;
        setCredentials({...credentials, [name]: value})
    };
     //Faire fonctionner submit
    const handelSubmit = async event => {
        event.preventDefault();
        try {
            await HomeLogin.authLogin(credentials);
            setIsAuthenticated(true);
            history.replace("/learners");
            setError("");
        } catch (error) {
            setError("Veuillez entrer l'adresse mail ou le mot de passe correctement");
        }
    }

    const classes = useStyles();
    return (
        <>
            <Card className={classes.title}>
                <Typography className={classes.typo} variant="h3" align="center" color="inherit">Connexion à
                    l'application</Typography>
            </Card>
            <Container className={classes.root} maxWidth="md">
                <Box borderTop={1} borderBottom={1} borderColor="primary.main">
                    <Card className={classes.login}>
                        <form onSubmit={handelSubmit}>
                            <Typography className={classes.valid} variant="h6" color="inherit">Email : </Typography>
                            <TextField
                                id="username"
                                placeholder="Email de la connexion"
                                name="username"
                                type="email"
                                value={credentials.username}
                                onChange={handleChange}
                                variant="filled"
                                color="primary"
                                margin="dense"
                                fullWidth={true}
                                error
                                helperText={error}
                            />
                            <Typography className={classes.valid} variant="h6" color="inherit">Mot de Passe
                                : </Typography>
                            <TextField
                                id="password"
                                placeholder="Mot de passe de la connexion"
                                name="password"
                                type="password"
                                value={credentials.password}
                                onChange={handleChange}
                                variant="filled"
                                color="primary"
                                margin="dense"
                                fullWidth={true}
                                error
                                helperText={error}
                            />
                            <FormControl fullWidth={true} size="medium">
                                <Button
                                    type="submit"
                                    className={classes.btn}
                                    variant="contained"
                                    size="large"
                                    color="primary">Connexion</Button>
                            </FormControl>
                        </form>
                    </Card>
                </Box>
            </Container>

        </>
    )
}

export default HomePage;