import React, {useEffect, useState} from "react";
import {withStyles, makeStyles} from '@material-ui/core/styles';
import coursesCRUD from "../methods/coursesCRUD";
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

const CoursesPage = (props) => {
    const [courses, setCourses] = useState([]);
    const  [currentPage, setCurrentPage] = useState(1);
    const [search, setSearch] = useState("");

    const fetchCourses = async () => {
        try {
            const data = await coursesCRUD.findAll();
            setCourses(data)
        } catch (error) {
            console.log(error.response)
        }
    }

    useEffect(() => {fetchCourses()}, []);

    const handleSearch = ({currentTarget}) => {
        setSearch(currentTarget.value);
        setCurrentPage(1);
    };

    const itemsPerPage = 15;

    const toFrench = {
        EXTENSIVE : "Extensive",
        INTENSIVE : "Intensive",
        YOUNG : "Jeune",
        SHORT : "Courte",
        LONG : "Longue"
    }

    const filteredCourses = courses.filter(c =>
        (toFrench[c.category] && toFrench[c.category].toLowerCase().includes(search.toLowerCase())) ||
        (toFrench[c.duration] && toFrench[c.duration].toLowerCase().includes(search.toLowerCase())) );
    const pagesCount = Math.ceil(filteredCourses.length / itemsPerPage);
    const start = currentPage * itemsPerPage - itemsPerPage;
    const paginatedCourses = filteredCourses.slice(start, start + itemsPerPage);

    const handleDelete = async id => {

        const initialCourses = [...courses];

        setCourses(courses.filter(course => course.id !== id));

        try {
            await coursesCRUD.delete(id);
        } catch (error) {
            setCourses(initialCourses);
        }
    };


    const classes = useStyles();

    return (
        <>
            <Card className={classes.title}>
                <Typography className={classes.typo} variant="h3" align="center" color="inherit">Liste des Cours</Typography>
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
                        <StyledTableCell  align="center">Code</StyledTableCell>
                        <StyledTableCell align="center">Categorie</StyledTableCell>
                        <StyledTableCell align="center">Durée</StyledTableCell>
                        <StyledTableCell align="center">Prix</StyledTableCell>
                        <StyledTableCell align="center">Formateurs</StyledTableCell>
                        <StyledTableCell align="center">Supprimer</StyledTableCell>

                    </TableRow>
                </TableHead>
                <TableBody>
                    {paginatedCourses.map(course => (
                        <StyledTableRow key={course.id}>
                            <StyledTableCell component="th" scope="row"  align="center">
                                {course.id}
                            </StyledTableCell>
                            <StyledTableCell align="center">
                                {toFrench[course.category]}
                            </StyledTableCell>
                            <StyledTableCell align="center">{toFrench[course.duration]}</StyledTableCell>
                            <StyledTableCell align="center">{course.price} €</StyledTableCell>
                            <StyledTableCell align="center">{course.instructors.length}</StyledTableCell>
                            <StyledTableCell align="center">
                                <Button onClick={() => handleDelete(course.id)}
                                    variant="contained" color="secondary">
                                    <DeleteOutlineIcon />
                                </Button>
                            </StyledTableCell>
                        </StyledTableRow>
                    ))}
                </TableBody>
            </Table>
            {itemsPerPage <filteredCourses.length &&<Pagination
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
export default CoursesPage;