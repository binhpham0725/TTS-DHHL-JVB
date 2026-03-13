let deleteId=null;
let editId=null;

/* helper toast */

function showToast(toastId,barId,duration){

let toast=document.getElementById(toastId);
let bar=document.getElementById(barId);

toast.style.display="block";

bar.style.animation="none";
bar.offsetHeight;
bar.style.animation="progress "+duration+"ms linear forwards";

}

/* register */

function register(){

let name=document.getElementById("name").value.trim();
let gender=document.getElementById("gender").value;
let age=document.getElementById("age").value;
let email=document.getElementById("email").value.trim();
let address=document.getElementById("address").value.trim();

if(name===""||gender===""||age===""){

let text=document.getElementById("loadingText");
text.innerText="⚠ Thiếu thông tin bắt buộc";

showToast("loadingToast","loadingBar",2000);

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

showToast("registerToast","registerBar",2000);

setTimeout(function(){
location.reload();
},2000);

}else{

let text=document.getElementById("loadingText");
text.innerText="❌ Lỗi lưu dữ liệu";

showToast("loadingToast","loadingBar",2000);

}

});

}

/* delete */

function confirmDelete(id){
deleteId=id;
document.getElementById("deleteOverlay").style.display="flex";
}

document.getElementById("confirmDeleteBtn").onclick=function(){

document.getElementById("deleteOverlay").style.display="none";

let formData=new FormData();
formData.append("id",deleteId);

fetch("delete.php",{
method:"POST",
body:formData
})
.then(res=>res.text())
.then(data=>{

if(data==="success"){

showToast("deleteToast","deleteBar",2000);

setTimeout(function(){
location.reload();
},2000);

}

});

};

document.getElementById("cancelDeleteBtn").onclick=function(){
document.getElementById("deleteOverlay").style.display="none";
};

/* edit */

function confirmEdit(id){
editId=id;
document.getElementById("editOverlay").style.display="flex";
}

document.getElementById("confirmEditBtn").onclick=function(){

document.getElementById("editOverlay").style.display="none";

let text=document.getElementById("loadingText");
text.innerText="⏳ Đang lấy dữ liệu...";

showToast("loadingToast","loadingBar",1500);

setTimeout(function(){
window.location.href="edit.php?id="+editId;
},1500);

};

document.getElementById("cancelEditBtn").onclick=function(){
document.getElementById("editOverlay").style.display="none";
};

/* pagination */

function goPage(page){

let text=document.getElementById("pageText");
text.innerText="⏳ Đang lấy dữ liệu trang "+page+"...";

showToast("pageToast","pageBar",1200);

setTimeout(function(){

let url=new URL(window.location.href);
url.searchParams.set("page",page);

window.location=url;

},1200);

}

/* logout */

function logout(){

showToast("logoutToast","logoutBar",2500);

setTimeout(function(){
window.location.href="login.html";
},3000);

}

/* search */

function liveSearch(){

let input=document.getElementById("search").value.toLowerCase();
let rows=document.querySelectorAll("#tableBody tr");

rows.forEach(row=>{
let text=row.innerText.toLowerCase();
row.style.display=text.includes(input)?"":"none";
});

}

/* sorting */

function sortTable(column){

let url=new URL(window.location.href);

let currentOrder=url.searchParams.get("order");
let order=currentOrder==="asc"?"desc":"asc";

url.searchParams.set("sort",column);
url.searchParams.set("order",order);

window.location=url;

}