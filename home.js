let deleteId=null;

function register(){

let name=document.getElementById("name").value.trim();
let gender=document.getElementById("gender").value;
let age=document.getElementById("age").value;
let email=document.getElementById("email").value.trim();
let address=document.getElementById("address").value.trim();

if(name===""||gender===""||age===""){
showMessage("Thiếu thông tin bắt buộc",false);
return;
}

let formData=new FormData();
formData.append("name",name);
formData.append("gender",gender);
formData.append("age",age);
formData.append("email",email);
formData.append("address",address);

fetch("register.php",{
method:"POST",
body:formData
})
.then(res=>res.text())
.then(data=>{

if(data==="success"){
showMessage("Đăng ký thành công",true);
location.reload();
}else{
showMessage("Lỗi lưu dữ liệu",false);
}

});

}

function confirmDelete(id){

deleteId=id;
document.getElementById("deleteOverlay").style.display="flex";

}

document.getElementById("confirmDeleteBtn").onclick=function(){

let formData=new FormData();
formData.append("id",deleteId);

fetch("delete.php",{
method:"POST",
body:formData
})
.then(res=>res.text())
.then(data=>{
if(data==="success"){
location.reload();
}
});

};

document.getElementById("cancelDeleteBtn").onclick=function(){

document.getElementById("deleteOverlay").style.display="none";

};

function showMessage(text,success){

let msg=document.getElementById("message");

msg.innerText=text;

msg.style.color=success?"green":"red";

}

function logout(){

let popup=document.getElementById("logoutPopup");
let bar=document.getElementById("logoutBar");

popup.style.display="block";

bar.style.animation="none";
bar.offsetHeight;
bar.style.animation="progress 2.5s linear forwards";

setTimeout(function(){
window.location.href="login.html";
},3000);

}