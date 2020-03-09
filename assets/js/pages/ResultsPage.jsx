import React, {useEffect, useState} from "react";
import {withStyles, makeStyles} from '@material-ui/core/styles';
import resultsCRUD from "../methods/resultsCRUD";
import {
    Table, TableBody, TableCell, TableHead,
    TableRow, Card, Link, Typography,
    Button, FormControl, Input, InputLabel
} from "@material-ui/core";
import Pagination from "@material-ui/lab/Pagination";
import DeleteOutlineIcon from '@material-ui/icons/DeleteOutline';
import moment from "moment";

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
        fontFamily : 'Times New Roman',
    },
    title: {
        minHeight: 100,
        marginTop: 75,
    },
    search: {
        marginBottom: 25,
    },
    typo: {
        fontFamily : 'Times New Roman',
        marginTop: 15,
        marginBottom: 15,
    }
});

const ResultsPage = (props) => {

    const [results, setResults] = useState([]);
    const  [currentPage, setCurrentPage] = useState(1);
    const [search, setSearch] = useState("");

    const fetchResults = async () => {
        try {
            const data = await resultsCRUD.findAll();
            setResults(data)
        } catch (error) {
            console.log(error.response)
        }
    }

    useEffect(() => {fetchResults()}, []);

    const handleSearch = ({currentTarget}) => {
        setSearch(currentTarget.value);
        setCurrentPage(1);
    };

    const itemsPerPage = 15;
    const toFrench = {
        ADMITTED : "Admis-e-",
        ADJOURNED : "Ajourné-e-"
    }
    const filteredResults = results.filter(r =>
        r.learner.firstName.toLowerCase().includes(search.toLowerCase()) ||
        r.learner.lastName.toLowerCase().includes(search.toLowerCase()) ||
        toFrench[r.status].toLowerCase().includes(search.toLowerCase()));
    const pagesCount = Math.ceil(filteredResults.length / itemsPerPage);
    const start = currentPage * itemsPerPage - itemsPerPage;
    const paginatedResults = filteredResults.slice(start, start + itemsPerPage);

    const handleDelete = async id => {

        const initialResults = [...results];

        setResults(results.filter(result => result.id !== id));

        try {
            await resultsCRUD.delete(id);
        } catch (error) {
            setResults(initialResults);
        }
    };

    const formatDate = (str) => moment(str).format('DD/MM/YYYY');



    const classes = useStyles();

    return (
        <>
            <Card className={classes.title}>
                <Typography className={classes.typo} variant="h3" align="center" color="inherit">Liste des Resultats</Typography>
            </Card>
            <Card className={classes.search}>
                <FormControl fullWidth={true} size="medium">
                    <InputLabel htmlFor="my-input" color="primary">Rechercher ...</InputLabel>
                    <Input id="my-input" aria-describedby="my-helper-text" onChange={handleSearch}
                           value={search}/>
                </FormControl>
            </Card>

            <Table className={classes.table} aria-label="customized table">
                <TableHead>
                    <TableRow>
                        <StyledTableCell align="center">Code</StyledTableCell>
                        <StyledTableCell align="center">Stagiaire</StyledTableCell>
                        <StyledTableCell align="center">Status</StyledTableCell>
                        <StyledTableCell align="center">Date de l'Obtention</StyledTableCell>
                        <StyledTableCell align="center">Supprimer</StyledTableCell>

                    </TableRow>
                </TableHead>
                <TableBody>
                    {paginatedResults.map(result => (

                        <StyledTableRow key={result.id}>
                            <StyledTableCell component="th" scope="row" align="center">
                                {result.id}
                            </StyledTableCell>
                            <StyledTableCell align="center">
                                <Link to={"#"}>{result.learner.firstName} {result.learner.lastName}</Link>
                            </StyledTableCell>
                            <StyledTableCell align="center">{toFrench[result.status]}</StyledTableCell>
                            <StyledTableCell align="center">{formatDate(result.obtainedAt)}</StyledTableCell>
                            <StyledTableCell align="center">
                                <Button onClick={() => handleDelete(result.id)}
                                        variant="contained" color="secondary">
                                    <DeleteOutlineIcon />
                                </Button>
                            </StyledTableCell>
                        </StyledTableRow>
                    ))}

                </TableBody>
            </Table>
            {itemsPerPage <filteredResults.length &&<Pagination
                count={pagesCount}
                boundaryCount={3}
                defaultPage={currentPage}
                onChange={(event, value) => setCurrentPage(value)}
                variant="outlined"
                shape="rounded"
                color="primary"
                size="large"/>}
        </>


    );
}
export default ResultsPage;