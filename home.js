let students = [];
let deleteIndex = null;

function register(){

let name = document.getElementById("name").value.trim();
let gender = document.getElementById("gender").value;
let age = document.getElementById("age").value;
let email = document.getElementById("email").value.trim();
let address = document.getElementById("address").value.trim();

if(name === "" || gender === "" || age === ""){
showMessage("Thiếu thông tin bắt buộc",false);
return;
}

age = Number(age);

if(gender === "nu" && age < 18){
showMessage("Nữ phải >= 18 tuổi",false);
return;
}

let student = {name,gender,age,email,address};

students.push(student);

renderTable();

showMessage("Đăng ký thành công",true);

clearInput();
}

function renderTable(){

let tbody = document.getElementById("tableBody");

tbody.innerHTML="";

students.forEach((s,index)=>{

let row = `
<tr>
<td>${index+1}</td>
<td>${s.name}</td>
<td>${s.gender}</td>
<td>${s.age}</td>
<td>${s.email}</td>
<td>${s.address}</td>
<td><button onclick="confirmDelete(${index})">Xóa</button></td>
</tr>
`;

tbody.innerHTML += row;

});
}

function confirmDelete(index){

deleteIndex = index;

document.getElementById("deleteOverlay").style.display="flex";

}

document.getElementById("confirmDeleteBtn").onclick=function(){

students.splice(deleteIndex,1);

renderTable();

closeDeleteDialog();

};

document.getElementById("cancelDeleteBtn").onclick=function(){

closeDeleteDialog();

};

function closeDeleteDialog(){

document.getElementById("deleteOverlay").style.display="none";

deleteIndex=null;

}

function showMessage(text,success){

let msg = document.getElementById("message");

msg.innerText = text;

msg.style.color = success ? "green" : "red";

}

function clearInput(){

document.getElementById("name").value="";
document.getElementById("gender").value="";
document.getElementById("age").value="";
document.getElementById("email").value="";
document.getElementById("address").value="";

}

function logout(){

let popup = document.getElementById("logoutPopup");
let bar = document.getElementById("logoutBar");

popup.style.display="block";

bar.style.animation="none";
bar.offsetHeight;
bar.style.animation="progress 2.5s linear forwards";

setTimeout(function(){
window.location.href="login.html";
},3000);

}