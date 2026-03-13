document.getElementById("backLogin").addEventListener("click", function(){
window.location.href = "login.html";
});

const container = document.querySelector(".bubble-container");
for(let i=0;i<35;i++){
let bubble = document.createElement("span");
bubble.classList.add("bubble");
let size = Math.random()*80 + 20;
bubble.style.width = size + "px";
bubble.style.height = size + "px";
bubble.style.left = Math.random()*100 + "%";
bubble.style.animationDuration =
(12 + Math.random()*10) + "s";
bubble.style.animationDelay =
Math.random()*10 + "s";
container.appendChild(bubble);
}