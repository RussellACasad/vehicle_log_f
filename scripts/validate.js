function validate(id) {
    var field;
    try {
        field = document.getElementById(id);
    } catch (ex) {
        console.log("Id invalid: " + id); 
        return; 
    }
    if (field.value == "")
    {
        field.classList.add("error")
    }
    else
    {
        field.classList.remove("error"); 
    }
}

function validate(id, min, max) {
    var field;
    try {
        field = document.getElementById(id);
    } catch (ex) {
        console.log("Id invalid: " + id); 
        return; 
    }
    if (field.value == "" || field.value < min || field.value > max)
    {
        field.classList.add("error")
    }
    else
    {
        field.classList.remove("error"); 
    }
}