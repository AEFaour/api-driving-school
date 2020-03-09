import React, {useEffect, useState} from "react";
import {makeStyles} from "@material-ui/core/styles";
import {
    Card, Typography, FormControl, Box,
    Button, Container, TextField
} from "@material-ui/core";
import {Link} from "react-router-dom";
import learnersCRUD from "../methods/learnersCRUD";

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

const LearnerPage = ({match, history}) => {
    const {id = "new"} = match.params;

    const [learner, setLearner] = useState({
        lastName: "",
        firstName: "",
        email: "",
        telephone: "",
        job: ""
    });

    const [errors, setErrors] = useState({
        lastName: "",
        firstName: "",
        email: "",
        telephone: "",
        job: ""
    });

    const [editing, setEditing] = useState(false);

    //Récupération d'un stagiaire par son identifiant
    const fetchLearner = async id => {
        try {
            const {firstName, lastName, email, telephone, job} = await learnersCRUD.findOne(id);


            setLearner({firstName, lastName, email, telephone, job});

        } catch (error) {

            history.replace("/learners");
        }
    };

    // Charger id ou un stagiaire dès qu'on charge le composant
    useEffect(() => {
        if (id !== "new") {
            setEditing(true);
            fetchLearner(id);
        }
    }, [id]);

    //Changement de valeurs de TextField
    const handleChange = ({currentTarget}) => {
        const {name, value} = currentTarget;
        setLearner({...learner, [name]: value})
    };

    //Valider le formulaire
    const handelSubmit = async event => {
        event.preventDefault();
        try {
            if (editing) {
                await learnersCRUD.edit(id, learner);

            } else {
                await learnersCRUD.add(learner);
                history.replace("/learners");
            };

            setErrors({});
        } catch ({response}) {
            const { violations } = response.data;
            if (violations) {
                const apiErrors = {};
                violations.forEach(({propertyPath, message}) => {
                    apiErrors[propertyPath] = message;
                });
                setErrors(apiErrors);
            }
        }
    };
    const classes = useStyles();
    return (
        <>

            {(!editing &&
                <Card className={classes.title}>
                    <Typography className={classes.typo} variant="h3" align="center" color="inherit">
                    Création d'un stagiaire
                </Typography>
                </Card>) || (
                <Card className={classes.title}>
                    <Typography className={classes.typo} variant="h3" align="center" color="inherit">
                    Modification du stagiaire
                </Typography>
                </Card>)}

            <Container className={classes.root} maxWidth="md">
                <Box borderTop={1} borderBottom={1} borderColor="primary.main">
                    <Card className={classes.edit}>
                        <form onSubmit={handelSubmit}>
                            <Typography className={classes.valid} variant="h6" color="inherit">Nom : </Typography>
                            <TextField
                                id="lastName"
                                name="lastName"
                                value={learner.lastName}
                                placeholder="Nom du stagiaire"
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
                                value={learner.firstName}
                                placeholder="Prénom du stagiaire"
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
                                value={learner.email}
                                placeholder="Adresse mail du stagiaire"
                                variant="filled"
                                color="primary"
                                margin="dense"
                                fullWidth={true}
                                onChange={handleChange}
                                error
                                helperText={errors.email}/>
                            <Typography className={classes.valid} variant="h6" color="inherit">Téléphone
                                : </Typography>
                            <TextField
                                id="telephone"
                                name="telephone"
                                value={learner.telephone}
                                placeholder="Numéro de téléphone du stagiaire"
                                variant="filled"
                                color="primary"
                                margin="dense"
                                fullWidth={true}
                                onChange={handleChange}
                                error
                                helperText={errors.telephone}/>
                            <Typography className={classes.valid} variant="h6" color="inherit">Profession
                                : </Typography>
                            <TextField
                                id="job"
                                name="job"
                                value={learner.job}
                                placeholder="Profession du stagiaire"
                                variant="filled"
                                color="primary"
                                margin="dense"
                                fullWidth={true}
                                onChange={handleChange}
                                error
                                helperText={errors.job}/>
                            <FormControl fullWidth={true} size="medium">
                                <Button
                                    type="submit"
                                    className={classes.btn}
                                    variant="contained"
                                    size="large"
                                    color="primary">
                                    <Link to="/learners" className={classes.navlink}>Enregistrer</Link>
                                </Button>
                            </FormControl>
                            <FormControl fullWidth={true} size="medium">
                                <Button
                                    className={classes.btn}
                                    variant="contained"
                                    size="large"
                                    color="primary">
                                    <Link to="/learners" className={classes.navlink}>Retour à la Liste</Link>
                                </Button>
                            </FormControl>

                        </form>

                    </Card>
                </Box>
            </Container>

        </>);
}

export default LearnerPage;