import React, {useEffect, useState} from "react";
import {withStyles, makeStyles} from '@material-ui/core/styles';
import learnersCRUD from "../methods/learnersCRUD";
import {
    Table, TableBody, TableCell, TableHead,
    TableRow, Card, Typography, Grid,
    Button, FormControl, Input, InputLabel
} from "@material-ui/core";
import Pagination from '@material-ui/lab/Pagination';
import DeleteOutlineIcon from '@material-ui/icons/DeleteOutline';
import CreateIcon from '@material-ui/icons/Create';
import {Link} from 'react-router-dom';


const StyledTableCell = withStyles(theme => ({
    head: {
        backgroundColor: theme.palette.primary.main,
        color: theme.palette.common.white,
    },
    body: {
        fontSize: 14,
    },
}))(TableCell);

const StyledTableRow = withStyles(theme => ({
    root: {
        '&:nth-of-type(odd)': {
            backgroundColor: theme.palette.background.default,
        },
    },
}))(TableRow);


const useStyles = makeStyles({
    table: {
        minWidth: 700,
        fontFamily: 'Times New Roman',
    },
    title: {
        minWidth: 1080,
        minHeight: 100,
        marginTop: 75,
        display: 'block'
    },
    search: {
        minWidth: 1080,
        marginBottom: 25,

    },
    typo: {
        fontFamily: 'Times New Roman',
        marginTop: 15,
        marginBottom: 15,
    },
    create: {
        float:'right',
        margin: 10,
    },
    icon: {
        margin: 1,
    }
});

const LearnersPage = (props) => {

    const [learners, setLearners] = useState([]);
    const [currentPage, setCurrentPage] = useState(1);
    const [search, setSearch] = useState("");

    // méthod de recupérer les stagiaires
    const fetchLearners = async () => {
        try {
            const data = await learnersCRUD.findAll();
            setLearners(data)
        } catch (error) {
            console.log(error.response)
        }
    }
    // Trouver les stagaires dès le chargement de composant

    useEffect(() => {fetchLearners()}, []);

    // Méthode de recherche
    const handleSearch = ({currentTarget}) => {
        setSearch(currentTarget.value);
        setCurrentPage(1);
    };

    const itemsPerPage = 15;
    // Afin de faire de recherche, on filtre les Stagiaires
    const filteredLearners = learners.filter(l =>
        l.firstName.toLowerCase().includes(search.toLowerCase()) ||
        l.lastName.toLowerCase().includes(search.toLowerCase()) ||
        l.email.toLowerCase().includes(search.toLowerCase()) ||
        (l.job && l.job.toLowerCase().includes(search.toLowerCase())));

    const pagesCount = Math.ceil(filteredLearners.length / itemsPerPage);
    const start = currentPage * itemsPerPage - itemsPerPage;

    //Pagination de tableau des stagiaires
    const paginatedLearners = filteredLearners.slice(start, start + itemsPerPage);

    // Méthode de supprimer un stagiaire

    const handleDelete = async id => {

        const initialLearners = [...learners];

        setLearners(learners.filter(learner => learner.id !== id));

        try {
            await learnersCRUD.delete(id);
        } catch (error) {
            setLearners(initialLearners);
        }
    };

    // Init de variable classes avec useStyles (Material-UI)
    const classes = useStyles();

    return (
        <>

            <Grid container spacing={3}>
                <Grid item xs={12}>
                <Card className={classes.title}>
                    <Typography className={classes.typo}  variant="h3" align="center" color="inherit">Liste des
                        Stagiaires</Typography>
                    <Link to="/learners/new">
                        <Button className={classes.create} variant="contained" color="secondary">
                            Créer un Stagiaire
                            <CreateIcon />
                        </Button>
                    </Link>
                </Card>

            <Card className={classes.search}>
                <FormControl fullWidth={true} size="medium">
                    <InputLabel htmlFor="my-input" color="primary">Rechercher ...</InputLabel>
                    <Input id="my-input" aria-describedby="my-helper-text" onChange={handleSearch}
                           value={search}/>
                </FormControl>
            </Card>
                </Grid>
            </Grid>
                <Table className={classes.table} aria-label="customized table">
                    <TableHead>
                        <TableRow>
                            <StyledTableCell align="center">Code</StyledTableCell>
                            <StyledTableCell align="center">Stagiaire</StyledTableCell>
                            <StyledTableCell align="center">Email</StyledTableCell>
                            <StyledTableCell align="center">Téléphone</StyledTableCell>
                            <StyledTableCell align="center">Job</StyledTableCell>
                            <StyledTableCell align="center">Cours</StyledTableCell>
                            <StyledTableCell align="center">Reslutats</StyledTableCell>
                            <StyledTableCell align="center">Factures</StyledTableCell>
                            <StyledTableCell align="center">Montant Total</StyledTableCell>
                            <StyledTableCell align="center">Supprimer</StyledTableCell>

                        </TableRow>
                    </TableHead>
                    <TableBody>
                        {paginatedLearners.map(learner => (
                            <StyledTableRow key={learner.id}>
                                <StyledTableCell component="th" scope="row" align="center">
                                    {learner.id}
                                </StyledTableCell>
                                <StyledTableCell align="center">
                                    <Link to={"/learners/" + learner.id}>{learner.firstName} {learner.lastName}</Link>
                                </StyledTableCell>
                                <StyledTableCell align="center">{learner.email}</StyledTableCell>
                                <StyledTableCell align="center">{learner.telephone}</StyledTableCell>
                                <StyledTableCell align="center">{learner.job}</StyledTableCell>
                                <StyledTableCell align="center">{learner.courses.length}</StyledTableCell>
                                <StyledTableCell align="center">{learner.results.length}</StyledTableCell>
                                <StyledTableCell align="center">{learner.invoices.length}</StyledTableCell>
                                <StyledTableCell align="center">{learner.totalAmount.toLocaleString()} €</StyledTableCell>
                                <StyledTableCell align="center">
                                    <Button
                                        onClick={() => handleDelete(learner.id)}
                                        variant="contained"
                                        color="secondary"
                                        disabled={learner.invoices.length > 0}>
                                        <DeleteOutlineIcon />
                                    </Button>
                                </StyledTableCell>
                            </StyledTableRow>
                        ))}
                    </TableBody>
                </Table>


            {itemsPerPage < filteredLearners.length && <Pagination
                count={pagesCount}
                boundaryCount={3}
                defaultPage={currentPage}
                onChange={(event, value) => setCurrentPage(value)}
                variant="outlined"
                shape="rounded"
                color="primary"
                size="large"/>
            }
        </>
    );
}
export default LearnersPage;