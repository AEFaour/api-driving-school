import React, {useEffect, useState} from "react";
import {makeStyles} from "@material-ui/core/styles";
import {
    Box, Button, Card, Container, FormControl,
    TextField, Typography
} from "@material-ui/core";
import {Link} from "react-router-dom";
import learnersCRUD from "../methods/learnersCRUD";
import Select from "../components/Select";
import axios from "axios";
import invoicesCRUD from "../methods/invoicesCRUD";

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

const InvoicePage = ({match, history}) => {
    const {id = "new"} = match.params;

    const [invoice, setInvoice] = useState({
        amount: "",
        learner: "",
        status: "SENT"
    });

    const [learners, setLearners] = useState([]);
    const [errors, setErrors] = useState({
        amount: "",
        learner: "",
        status: ""
    });
    const [editing, setEditing] = useState(false);

    //Récupération des stagiaires pour select

    const fetchLearners = async () => {
        try {
            const data = await learnersCRUD.findAll();

            setLearners(data);
            if (!invoice.learner) {
                setInvoice({...invoice, learner: data[0].id});
            }

        } catch (error) {
            history.replace('/invoices');

            console.log(error.response);
        }
    };

    const handleChange = ({currentTarget}) => {
        const {name, value} = currentTarget;
        setInvoice({...invoice, [name]: value})
    };

    //Récupération d'une facture par son identifiant
    const fetchInvoice = async id => {
        try {
            const { amount, status, learner} = await invoicesCRUD.findOne(id);

            setInvoice({ amount, status, learner: learner.id});

        } catch (error) {
            console.log(error.response);
            history.replace('/invoices');
        }
    }

    //Charger la liste des stagiaires
    useEffect(() => {
        fetchLearners();
    }, []);

    // Charger id ou une facture dès qu'on charge le composant
    useEffect(() => {
        if(id !== "new") {
            setEditing(true);
            fetchInvoice(id);
        }

    }, [id]);

    //Valider le formulaire
    const handelSubmit = async event => {
        event.preventDefault();
        try {
            if(editing){
               await invoicesCRUD.edit(id, invoice);

            } else {
                await invoicesCRUD.add(invoice);
                history.replace("/invoices");
            }
        } catch ({response}) {
            const {violations} = response.data;
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

    return (<>
        {editing && (
            <Card className={classes.title}>
                <Typography className={classes.typo} variant="h3" align="center" color="inherit">
                    Modification d'une facture
                </Typography>
            </Card>
        ) || (
            <Card className={classes.title}>
                <Typography className={classes.typo} variant="h3" align="center" color="inherit">
                    Création d'une facture
                </Typography>
            </Card>
        )}

        <Container className={classes.root} maxWidth="md">
            <Box borderTop={1} borderBottom={1} borderColor="primary.main">
                <Card className={classes.edit}>
                    <form onSubmit={handelSubmit}>
                        <Typography className={classes.valid} variant="h6" color="inherit">Montant : </Typography>
                        <TextField
                            id="amount"
                            name="amount"
                            type="number"
                            value={invoice.amount}
                            placeholder="Montant de la Facture"
                            variant="filled"
                            color="primary"
                            margin="dense"
                            fullWidth={true}
                            onChange={handleChange}
                            error
                            helperText={errors.amount}
                        />
                        <Typography className={classes.valid} variant="h6" color="inherit"> Stagiaire : </Typography>
                        <Select name="learner" value={invoice.learner} error={errors.learner}
                                onChange={handleChange}>

                            {learners.map(learner => <option key={learner.id}
                                                             value={learner.id}>{learner.firstName} {learner.lastName}</option>)}

                        </Select>

                        <Typography className={classes.valid} variant="h6" color="inherit">Status : </Typography>
                        <Select name="status" value={invoice.status} error={errors.status}
                                onChange={handleChange}>
                            <option value="SENT">Envoyée</option>
                            <option value="PAID">Payée</option>
                            <option value="CANCELLED">Annulée</option>
                        </Select>
                        <FormControl fullWidth={true} size="medium">
                            <Button
                                type="submit"
                                className={classes.btn}
                                variant="contained"
                                size="large"
                                color="primary">
                                <Link to="/invoices" className={classes.navlink}>Enregistrer</Link>
                            </Button>
                        </FormControl>
                        <FormControl fullWidth={true} size="medium">
                            <Button
                                className={classes.btn}
                                variant="contained"
                                size="large"
                                color="primary">
                                <Link to="/invoices" className={classes.navlink}>Retour à la Liste</Link>
                            </Button>
                        </FormControl>

                    </form>

                </Card>
            </Box>
        </Container>

    </>)
};

export default InvoicePage;