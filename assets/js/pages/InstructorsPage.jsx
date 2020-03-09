import React, {useEffect, useState} from "react";
import {withStyles, makeStyles} from '@material-ui/core/styles';
import instructorsCRUD from "../methods/instructorsCRUD";
import {
    Table, TableBody, TableCell, TableHead,
    TableRow, Card, Link, Typography,
    Button, FormControl, Input, InputLabel
} from "@material-ui/core";
import Pagination from "@material-ui/lab/Pagination";
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

const InstructorsPage = (props) => {
    const [instructors, setInstructors] = useState([]);
    const  [currentPage, setCurrentPage] = useState(1);
    const [search, setSearch] = useState("");

    const fetchInstructors = async () => {
        try {
            const data = await instructorsCRUD.findAll();
            setInstructors(data)
        } catch (error) {
            console.log(error.response)
        }
    }

    useEffect(() => {fetchInstructors()}, []);

    const handleSearch = ({currentTarget}) => {
        setSearch(currentTarget.value);
        setCurrentPage(1);
    };

    const itemsPerPage = 15;
    const filteredInstructors = instructors.filter(i =>
        i.firstName.toLowerCase().includes(search.toLowerCase())||
        i.lastName.toLowerCase().includes(search.toLowerCase()) ||
        i.email.toLowerCase().includes(search.toLowerCase()));
    const pagesCount = Math.ceil(filteredInstructors .length / itemsPerPage);
    const start = currentPage * itemsPerPage - itemsPerPage;
    const paginatedInstructors = filteredInstructors .slice(start, start + itemsPerPage);

    const handleDelete = async id => {

        const initialInstructors = [...instructors];

        setInstructors(instructors.filter(instructor=> instructor.id !== id));

        try {
            await instructorsCRUD.delete(id);
        } catch (error) {
            setInstructors(initialInstructors);
        }
    };

    const toFrench = {
        EXTENSIVE : "Extensive",
        INTENSIVE : "Intensive",
        YOUNG : "Jeune"
    }
    const classes = useStyles();

    return (
        <>
            <Card className={classes.title}>
                <Typography className={classes.typo} variant="h3" align="center" color="inherit">Liste des Formateurs</Typography>

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
                        <StyledTableCell align="center">Formateur</StyledTableCell>
                        <StyledTableCell align="center">email</StyledTableCell>
                        <StyledTableCell align="center">Téléphone</StyledTableCell>
                        <StyledTableCell align="center">Cours</StyledTableCell>
                        <StyledTableCell align="center">Salaires</StyledTableCell>
                        <StyledTableCell align="center">Supprimer</StyledTableCell>

                    </TableRow>
                </TableHead>
                <TableBody>
                    {paginatedInstructors.map(instructor => (
                        <StyledTableRow key={instructor.id}>
                            <StyledTableCell component="th" scope="row" align="center">
                                {instructor.id}
                            </StyledTableCell>
                            <StyledTableCell align="center">
                                {instructor.firstName} {instructor.lastName}
                            </StyledTableCell>
                            <StyledTableCell align="center">{instructor.email}</StyledTableCell>
                            <StyledTableCell align="center">{instructor.telephone}</StyledTableCell>
                            <StyledTableCell align="center"><Link to={"#"}>{toFrench[instructor.course.category]}</Link></StyledTableCell>
                            <StyledTableCell align="center">{instructor.salaries.length}</StyledTableCell>
                            <StyledTableCell align="center">
                                <Button onClick={() => handleDelete(instructor.id)}
                                    variant="contained" color="secondary">
                                    <DeleteOutlineIcon />
                                </Button>
                            </StyledTableCell>
                        </StyledTableRow>
                    ))}
                </TableBody>
            </Table>
            {itemsPerPage < filteredInstructors.length &&<Pagination
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
export default InstructorsPage;