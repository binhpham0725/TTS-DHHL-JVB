function showToast(message, redirect=false){

const toast=document.getElementById("toast");
const msg=document.getElementById("toastMsg");

msg.textContent=message;

toast.style.display="block";

if(redirect){
setTimeout(()=>{
window.location="home.php";
},3000);
}

}

document.addEventListener("DOMContentLoaded",function(){

const back=document.querySelector(".edit-back");

if(back){
back.addEventListener("click",function(e){
e.preventDefault();
showToast("Đang quay lại trang chủ sau 3 giây...",true);
});
}

});