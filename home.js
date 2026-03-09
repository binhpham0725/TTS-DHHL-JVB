// mảng lưu danh sách sinh viên
let students = [];


// function nút đăng ký
function register(){

// lấy dữ liệu từ input
let name = document.getElementById("name").value.trim();
let gender = document.getElementById("gender").value;
let age = document.getElementById("age").value;
let email = document.getElementById("email").value.trim();
let address = document.getElementById("address").value.trim();

// kiểm tra thông tin bắt buộc
if(name === "" || gender === "" || age === ""){
showMessage("Thiếu thông tin bắt buộc",false);
return;
}
age = Number(age);

// điều kiện nữ phải >= 18 tuổi
if(gender === "nu" && age < 18){
showMessage("Nữ phải >= 18 tuổi",false);
return;
}

// tạo object sinh viên
let student = {name,gender,age,email,address};

// thêm vào danh sách
students.push(student);

// cập nhật bảng
renderTable();

// thông báo thành công
showMessage("Đăng ký thành công",true);

// xóa dữ liệu input
clearInput();
}

// hiển thị dữ liệu ra bảng
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
<td><button onclick="removeStudent(${index})">Xóa</button></td>
</tr>
`;
tbody.innerHTML += row;
});
}


// xóa sinh viên khỏi danh sách
function removeStudent(index){
students.splice(index,1);
renderTable();
}


// thông báo lỗi hoặc thành công
function showMessage(text,success){
let msg = document.getElementById("message");
msg.innerText = text;
msg.style.color = success ? "green" : "red";
}


// xóa dữ liệu sau khi đăng ký
function clearInput(){

document.getElementById("name").value="";
document.getElementById("gender").value="";
document.getElementById("age").value="";
document.getElementById("email").value="";
document.getElementById("address").value="";
}


// xử lý logout
function logout(){

let popup = document.getElementById("logoutPopup");
let bar = document.getElementById("logoutBar");

// hiển thị thông báo
popup.style.display="block";

// reset animation progress bar
bar.style.animation="none";
bar.offsetHeight;
bar.style.animation="progress 2.5s linear forwards";

// chuyển về trang login sau 3 giây
setTimeout(function(){
window.location.href="login.html";
},3000);
}