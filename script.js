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