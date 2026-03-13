const container = document.querySelector(".bubble-container");
for(let i=0;i<30;i++){
let bubble = document.createElement("span");
bubble.classList.add("bubble");
let size = Math.random()*80 + 20;
bubble.style.width = size+"px";
bubble.style.height = size+"px";
bubble.style.left = Math.random()*100+"%";
bubble.style.animationDuration =
(15 + Math.random()*15)+"s,"+
(4 + Math.random()*6)+"s";
bubble.style.animationDelay =
Math.random()*10+"s";
container.appendChild(bubble);
}

const form = document.querySelector("form");
const emailInput = form.querySelector('input[type="email"]');
const passwordInput = form.querySelector('input[type="password"]');
form.addEventListener("submit", function(e){
e.preventDefault();
const email = emailInput.value.trim();
const password = passwordInput.value.trim();

const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
if(email === ""){
  alert("Email không được để trống");
  return;
}
if(!emailRegex.test(email)){
  alert("Email không đúng định dạng");
  return;
}
if(password.length < 8){
  alert("Mật khẩu phải có ít nhất 8 ký tự");
  return;
}

window.location.href = "dashboard.html";
});