const signinTab = document.getElementById("signinTab");
const signupTab = document.getElementById("signupTab");

const signinForm = document.getElementById("signinForm");
const signupForm = document.getElementById("signupForm");

const card = document.querySelector(".card");

/* smooth switch + card resize */

function animateSwitch(hideForm, showForm, direction){

const startHeight = card.offsetHeight;

hideForm.animate([
{ transform:"translateX(0)", opacity:1 },
{ transform:`translateX(${direction*-40}px)`, opacity:0 }
],{
duration:300,
easing:"ease"
}).onfinish = () => {

hideForm.classList.remove("active");

showForm.classList.add("active");

const endHeight = card.offsetHeight;

card.style.height = startHeight + "px";

requestAnimationFrame(()=>{
card.style.height = endHeight + "px";
});

setTimeout(()=>{
card.style.height = "auto";
},350);

showForm.animate([
{ transform:`translateX(${direction*40}px)`, opacity:0 },
{ transform:"translateX(0)", opacity:1 }
],{
duration:300,
easing:"ease"
});

};

}

signinTab.onclick = () => {

if(signinForm.classList.contains("active")) return;

animateSwitch(signupForm, signinForm, -1);

signinTab.classList.add("active");
signupTab.classList.remove("active");

};

signupTab.onclick = () => {

if(signupForm.classList.contains("active")) return;

animateSwitch(signinForm, signupForm, 1);

signupTab.classList.add("active");
signinTab.classList.remove("active");

};


/* password toggle */

function togglePassword(open, closed, input){

open.onclick = () => {
input.type="text";
open.style.display="none";
closed.style.display="block";
};

closed.onclick = () => {
input.type="password";
closed.style.display="none";
open.style.display="block";
};

}

/* signin password */

togglePassword(
document.getElementById("signinEyeOpen"),
document.getElementById("signinEyeClosed"),
document.getElementById("signinPassword")
);

/* signup password */

togglePassword(
document.getElementById("signupEyeOpen"),
document.getElementById("signupEyeClosed"),
document.getElementById("signupPassword")
);

/* confirm password */

togglePassword(
document.getElementById("confirmEyeOpen"),
document.getElementById("confirmEyeClosed"),
document.getElementById("confirmPassword")
);


/* SIGN IN */

const emailInput = document.getElementById("signinEmail");
const passwordInput = document.getElementById("signinPassword");

signinForm.addEventListener("submit", function(e){

e.preventDefault();

let email = emailInput.value.trim();
let password = passwordInput.value.trim();

let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

if(email === ""){
alert("Email is required");
return;
}

if(!emailPattern.test(email)){
alert("Email format is invalid");
return;
}

if(password === ""){
alert("Password is required");
return;
}

if(password.length < 8){
alert("Password must be at least 8 characters");
return;
}

let formData = new FormData();

formData.append("email",email);
formData.append("password",password);

fetch("login.php",{
method:"POST",
body:formData
})
.then(res=>res.text())
.then(data=>{

if(data==="success"){
window.location.href="home.php";
}
else if(data==="wrong_password"){
alert("Sai mật khẩu");
}
else{
alert("Email không tồn tại");
}

});

});


/* SIGN UP */

signupForm.addEventListener("submit",function(e){

e.preventDefault();

let username = signupForm.querySelector("input[type=text]").value.trim();
let email = signupForm.querySelector("input[type=email]").value.trim();
let birthday = document.getElementById("birthday").value;
let password = document.getElementById("signupPassword").value;
let confirm = document.getElementById("confirmPassword").value;

if(username === ""){
alert("Username required");
return;
}

if(email === ""){
alert("Email required");
return;
}

if(password.length < 8){
alert("Password must be at least 8 characters");
return;
}

if(password !== confirm){
alert("Password không khớp");
return;
}

let formData = new FormData();

formData.append("username",username);
formData.append("email",email);
formData.append("password",password);
formData.append("birthday",birthday);

fetch("signup.php",{
method:"POST",
body:formData
})
.then(res=>res.text())
.then(data=>{

if(data==="success"){
alert("Tạo tài khoản thành công");
signinTab.click();
}
else{
alert("Không tạo được tài khoản");
}

});

});