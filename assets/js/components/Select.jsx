import React from "react";
import {makeStyles} from "@material-ui/core/styles";
import {InputLabel} from "@material-ui/core";
import FormControl from "@material-ui/core/FormControl";


const useStyles = makeStyles(theme => ({

    formControl: {
        margin: theme.spacing(1),
    },
    selectEmpty: {
        marginTop: theme.spacing(2),
    },
}));

const Select = ({name, value, error="", label, onChange, children }) => {
    const classes = useStyles();
    return (
        <FormControl className={classes.formControl} fullWidth={true} variant="filled" margin="dense">
            <InputLabel htmlFor={name}>{label}</InputLabel>
            <select name={name} id={name} value={value} onChange={onChange} className={"form-control form-control-lg" + (error && " is-invalid")}>
                {children}
            </select>
            <p className="invalid-feedback">{error}</p>
        </FormControl>
    );
};

export default Select;
