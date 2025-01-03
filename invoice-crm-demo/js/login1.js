$(document).ready(function(){
$("#login").click(function(){
var email = $("#myusername").val();
var password = $("#mypassword").val();
// Checking for blank fields.
if( email =='' || password ==''){
$('input[type="text"],input[type="password"]').css("border","2px solid red");
$('input[type="text"],input[type="password"]').css("box-shadow","0 0 3px red");
document.getElementById("err").innerHTML = "Please fill all fields...!!!!!!";
}else {
$.post("logincode.php",{ email1: email, password1:password},
function(data) {
if(data=='Invalid Email.......') {
$('input[type="text"]').css({"border":"2px solid red","box-shadow":"0 0 3px red"});
$('input[type="password"]').css({"border":"2px solid #00F5FF","box-shadow":"0 0 5px #00F5FF"});
document.getElementById("err").innerHTML= data;
}else if(data=='Email or Password is wrong...!!!!'){
$('input[type="text"],input[type="password"]').css({"border":"2px solid red","box-shadow":"0 0 3px red"});
document.getElementById("err").innerHTML= data;
} else if(data=='true'){
location.href="dashboard.php";

}
else
{
	alert("error");
}
});
}
});
});