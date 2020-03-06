import React, {useEffect, useState} from "react";
import {withStyles, makeStyles} from '@material-ui/core/styles';
import invoicesCRUD from "../methods/invoicesCRUD";
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
        marginTop: 25,
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

const InvoicesPage = (props) => {
    const [invoices, setInvoices] = useState([]);
    const  [currentPage, setCurrentPage] = useState(1);
    const [search, setSearch] = useState("");

    const fetchInvoices = async () => {
        try {
            const data = await invoicesCRUD.findAll();
            setInvoices(data)
        } catch (error) {
            console.log(error.response)
        }
    }

    useEffect(() => {fetchInvoices()}, []);

    const handleSearch = ({currentTarget}) => {
        setSearch(currentTarget.value);
        setCurrentPage(1);
    };

    const itemsPerPage = 15;

    const toFrench = {
        PAID : "Payée",
        SENT: "Envoyée",
        CANCELLED : "Annulée"
    }

    const filteredInvoices = invoices.filter(i =>
        i.learner.firstName.toLowerCase().includes(search.toLowerCase()) ||
        i.learner.lastName.toLowerCase().includes(search.toLowerCase()) ||
        i.amount.toString().includes(search.toLowerCase()) ||
    toFrench[i.status].toLowerCase().includes(search.toLowerCase()));
    const pagesCount = Math.ceil(filteredInvoices.length / itemsPerPage);
    const start = currentPage * itemsPerPage - itemsPerPage;
    const paginatedInvoices = filteredInvoices.slice(start, start + itemsPerPage);

    const handleDelete = async id => {

        const initialInvoices = [...invoices];

        setInvoices(invoices.filter(invoice => invoice.id !== id));

        try {
            await invoicesCRUD.delete(id);
        } catch (error) {
            setInvoices(initialInvoices);
        }
    };

    const formatDate = (str) => moment(str).format('DD/MM/YYYY');

    const classes = useStyles();

    return (
        <>
            <Card className={classes.title}>
                <Typography className={classes.typo} variant="h3" align="center" color="inherit">Liste des Factures</Typography>

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
                        <StyledTableCell align="center">Numéro</StyledTableCell>
                        <StyledTableCell align="center">Date de l'envoi</StyledTableCell>
                        <StyledTableCell align="center">Status</StyledTableCell>
                        <StyledTableCell align="center">Montant</StyledTableCell>
                        <StyledTableCell align="center">Actions</StyledTableCell>

                    </TableRow>
                </TableHead>
                <TableBody>
                    {paginatedInvoices.map(invoice => (
                        <StyledTableRow key={invoice.id}>
                            <StyledTableCell component="th" scope="row" align="center">
                                {invoice.id}
                            </StyledTableCell>
                            <StyledTableCell align="center">
                                <Link to={"#"}>{invoice.learner.firstName} {invoice.learner.lastName}</Link>
                            </StyledTableCell>
                            <StyledTableCell align="center">{invoice.chrono}</StyledTableCell>
                            <StyledTableCell align="center">{formatDate(invoice.sentAt)}</StyledTableCell>
                            <StyledTableCell align="center">{toFrench[invoice.status]}</StyledTableCell>
                            <StyledTableCell align="center">
                                {invoice.amount.toLocaleString()}
                            </StyledTableCell>
                            <StyledTableCell align="center">
                                <Button
                                        onClick={() => handleDelete(invoice.id)}
                                        variant="contained"
                                        color="secondary">
                                    <DeleteOutlineIcon />
                                </Button>
                            </StyledTableCell>
                        </StyledTableRow>
                    ))}
                </TableBody>
            </Table>
            {itemsPerPage < filteredInvoices.length &&<Pagination
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
export default InvoicesPage;