import React, {useEffect, useState} from "react";
import {withStyles, makeStyles} from '@material-ui/core/styles';
import salariesCRUD from "../methods/salariesCRUD";
import {
    Table, TableBody, TableCell, TableHead,
    TableRow, Card, Link, Typography,
    Button, FormControl, Input, InputLabel
} from "@material-ui/core";
import Pagination from '@material-ui/lab/Pagination';
import moment from "moment";
import DeleteOutlineIcon from '@material-ui/icons/DeleteOutline';


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

const SalariesPage = (props) => {
    const [salaries, setSalaries] = useState([]);
    const  [currentPage, setCurrentPage] = useState(1);
    const [search, setSearch] = useState("");

    const fetchSalaries = async () => {
        try {
            const data = await salariesCRUD.findAll();
            setSalaries(data)
        } catch (error) {
            console.log(error.response)
        }
    }

    useEffect(() => {fetchSalaries()}, []);

    const handleSearch = ({currentTarget}) => {
        setSearch(currentTarget.value);
        setCurrentPage(1);
    };

    const itemsPerPage = 15;
    const filteredSalaries = salaries.filter(s =>
        s.instructor.firstName.toLowerCase().includes(search.toLowerCase()) ||
        s.instructor.lastName.toLowerCase().includes(search.toLowerCase()) ||
        s.amount.toString().includes(search.toLowerCase()));
    const pagesCount = Math.ceil(filteredSalaries.length / itemsPerPage);
    const start = currentPage * itemsPerPage - itemsPerPage;
    const paginatedSalaries = filteredSalaries.slice(start, start + itemsPerPage);

    const handleDelete = async id => {

        const initialSalaries = [...salaries];

        setSalaries(salaries.filter(salarie => salarie.id !== id));

        try {
            await salariesCRUD.delete(id);
        } catch (error) {
            setSalaries(initialSalaries);
        }
    };

    const formatDate = (str) => moment(str).format('DD/MM/YYYY');
    const classes = useStyles();

    return (
        <>
            <Card className={classes.title}>
                <Typography className={classes.typo} variant="h3" align="center" color="inherit">Liste des Salaires</Typography>

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
                        <StyledTableCell align="center">Fomateur</StyledTableCell>
                        <StyledTableCell align="center">Montant</StyledTableCell>
                        <StyledTableCell align="center">Date de payment</StyledTableCell>
                        <StyledTableCell align="center">Supprimer</StyledTableCell>

                    </TableRow>
                </TableHead>
                <TableBody>
                    {paginatedSalaries.map(salary => (
                        <StyledTableRow key={salary.id}>
                            <StyledTableCell component="th" scope="row" align="center">
                                {salary.id}
                            </StyledTableCell>
                           <StyledTableCell align="center">
                               <Link to={"#"}>{salary.instructor.firstName} {salary.instructor.lastName}</Link>
                            </StyledTableCell>
                            <StyledTableCell align="center">
                                {salary.amount}
                            </StyledTableCell>
                            <StyledTableCell align="center">{formatDate(salary.paidAt)}</StyledTableCell>
                            <StyledTableCell align="center">
                                <Button
                                    onClick={() => handleDelete(salary.id)}
                                    variant="contained"
                                    color="secondary">
                                    <DeleteOutlineIcon />
                                </Button>
                            </StyledTableCell>
                        </StyledTableRow>
                    ))}
                </TableBody>
            </Table>
            <Pagination
                count={pagesCount}
                boundaryCount={3}
                defaultPage={currentPage}
                onChange={(event, value) => setCurrentPage(value)}
                variant="outlined"
                shape="rounded"
                color="primary"
                size="large"/>
        </>
    );
}
export default SalariesPage;