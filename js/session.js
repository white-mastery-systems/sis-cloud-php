var empId = localStorage.getItem("user_id");
if(!empId){
    window.location.href='#/';
}

function logout() {
    localStorage.clear();
    window.location.href='#/';
}