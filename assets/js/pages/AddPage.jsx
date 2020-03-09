import React, {useState} from "react";
import {makeStyles} from "@material-ui/core/styles";
import {Box, Button, Card, Container, FormControl, TextField, Typography} from "@material-ui/core";
import {Link} from "react-router-dom";
import userAdd from "../methods/userAdd";

const useStyles = makeStyles(theme => ({
    root: {
        flexGrow: 1,
        align: 'center',
        marginTop: 100,
    },
    table: {
        minWidth: 700,
        fontFamily: 'Times New Roman',
    },
    title: {
        minHeight: 100,
        marginTop: 75,
    },
    btn: {
        margin: theme.spacing(1),
    },
    search: {
        marginBottom: 25,
    },
    edit: {
        marginBottom: 0,
    },
    typo: {
        fontFamily: 'Times New Roman',
        marginTop: 15,
        marginBottom: 15,
    },
    valid: {
        fontFamily: 'Times New Roman',
    },
    navlink: {
        color: theme.palette.common.white,
    },
}));

const AddPage = ({ history }) => {

    const [user, setUser] = useState({
        firstName: "",
        lastName: "",
        email: "",
        password: "",
        passwordConfirm: "",
    });

    const [errors, seErrors] = useState({
        firstName: "",
        lastName: "",
        email: "",
        password: "",
        passwordConfirm: "",
    });

    const handleChange = ({currentTarget}) => {
        const {name, value} = currentTarget;
        setUser({...user, [name]: value})
    };

    const handelSubmit = async event => {
        event.preventDefault();
        const apiErrors = {};

        if(user.password !== user.passwordConfirm){
            apiErrors.passwordConfirm = "Veuillez intégrer le même mot de passe";
            seErrors(apiErrors);
            return ;
        }
        try {
            const response = await userAdd.add(user);
            seErrors({});
            history.replace("/home");

            console.log(response);
            
        } catch (error) {
            const {violations} = error.response.data;
            if(violations){
                const apiErrors = {};
                violations.forEach(violation => {
                    apiErrors[violation.propertyPath] = violation.message;
                });
                seErrors(apiErrors);
            };
        }
    }
    const classes = useStyles();
    return (
        <>
            <Card className={classes.title}>
                <Typography className={classes.typo} variant="h3" align="center" color="inherit">
                    Création d'un utilisateur
                </Typography>
            </Card>
            <Container className={classes.root} maxWidth="md">
                <Box borderTop={1} borderBottom={1} borderColor="primary.main">
                    <Card className={classes.edit}>
                        <form onSubmit={handelSubmit}>
                            <Typography className={classes.valid} variant="h6" color="inherit">Nom : </Typography>
                            <TextField
                                id="lastName"
                                name="lastName"
                                value={user.lastName}
                                placeholder="Nom de l'utilisateur"
                                variant="filled"
                                color="primary"
                                margin="dense"
                                fullWidth={true}
                                onChange={handleChange}
                                error
                                helperText={errors.lastName}/>
                            <Typography className={classes.valid} variant="h6" color="inherit">Prénom : </Typography>
                            <TextField
                                id="firstName"
                                name="firstName"
                                value={user.firstName}
                                placeholder="Prénom de l'utilisateur"
                                variant="filled"
                                color="primary"
                                margin="dense"
                                fullWidth={true}
                                onChange={handleChange}
                                error
                                helperText={errors.firstName}/>
                            <Typography className={classes.valid} variant="h6" color="inherit">Email : </Typography>
                            <TextField
                                id="email"
                                name="email"
                                type="email"
                                value={user.email}
                                placeholder="Adresse mail de l'utilisateur"
                                variant="filled"
                                color="primary"
                                margin="dense"
                                fullWidth={true}
                                onChange={handleChange}
                                error
                                helperText={errors.email}/>
                            <Typography className={classes.valid} variant="h6" color="inherit">Mot de Passe
                                : </Typography>
                            <TextField
                                id="password"
                                name="password"
                                type="password"
                                value={user.password}
                                placeholder="Mot de passe de l'utilisateur"
                                variant="filled"
                                color="primary"
                                margin="dense"
                                fullWidth={true}
                                onChange={handleChange}
                                error
                                helperText={errors.password}/>
                            <Typography className={classes.valid} variant="h6" color="inherit">Confirmation de mot de passe
                                : </Typography>
                            <TextField
                                id="passwordConfirm"
                                name="passwordConfirm"
                                type="password"
                                value={user.passwordConfirm}
                                placeholder="Confirmation de mot de passe de l'utilisateur"
                                variant="filled"
                                color="primary"
                                margin="dense"
                                fullWidth={true}
                                onChange={handleChange}
                                error
                                helperText={errors.passwordConfirm}/>
                            <FormControl fullWidth={true} size="medium">
                                <Button
                                    type="submit"
                                    className={classes.btn}
                                    variant="contained"
                                    size="large"
                                    color="primary">
                                    Ajouter
                                </Button>
                            </FormControl>
                            <FormControl fullWidth={true} size="medium">
                                <Button
                                    className={classes.btn}
                                    variant="contained"
                                    size="large"
                                    color="primary">
                                    <Link to="/home" className={classes.navlink}>Aller à la Page de connexion</Link>
                                </Button>
                            </FormControl>

                        </form>

                    </Card>
                </Box>
            </Container>
        </>
    )

};

export default AddPage;