document.getElementById("logoutBtn").onclick = function(){
window.location.href = "logout.html";
};
const container = document.querySelector(".bubble-container");
for(let i=0;i<35;i++){
let bubble = document.createElement("span");
bubble.classList.add("bubble");
let size = Math.random()*80 + 20;
bubble.style.width = size + "px";
bubble.style.height = size + "px";
bubble.style.left = Math.random()*100 + "%";
bubble.style.animationDuration =
(15 + Math.random()*15) + "s";
bubble.style.animationDelay =
Math.random()*10 + "s";
container.appendChild(bubble);
}

const dob = document.getElementById("dob");
const age = document.getElementById("age");
dob.addEventListener("change",function(){
let birth = new Date(this.value);
let today = new Date();
let result = today.getFullYear() - birth.getFullYear();
let m = today.getMonth() - birth.getMonth();
if(m < 0 || (m === 0 && today.getDate() < birth.getDate())){
result--;
}
age.value = result;
});

let stt = 1;

document.querySelector(".register-btn").onclick = function(){
let name = document.getElementById("name").value;
let email = document.getElementById("email").value;
let address = document.getElementById("address").value;
let dobValue = dob.value;
let ageValue = age.value;
let course = document.getElementById("course").value;
let gender = document.querySelector('input[name="gender"]:checked');
if(!name || !dobValue || !gender || !course){
alert("Vui lòng nhập đầy đủ thông tin bắt buộc");
return;
}
gender = gender.value;
if(gender !== "Nữ" || ageValue < 18){
alert("Chỉ cho phép Nữ và tuổi >=18");
return;
}
let table = document.getElementById("studentTable");
let row = `
<tr class="table-row">
<td>${stt++}</td>
<td>${name}</td>
<td>${gender}</td>
<td>${dobValue}</td>
<td>${ageValue}</td>
<td>${email}</td>
<td>${address}</td>
<td>
<button class="delete-btn" onclick="deleteRow(this)">
<span class="material-symbols-outlined">delete</span>
</button>
</td>
</tr>
`;
table.innerHTML += row;
updateStudentCount();
let totalRows = document.querySelectorAll("#studentTable tr").length;
currentPage = Math.ceil(totalRows / rowsPerPage);
renderTable();
alert("Đăng ký thành công");
};

function deleteRow(btn){
btn.parentElement.parentElement.remove();
updateStudentCount();
}

const rowsPerPage = 10;
let currentPage = 1;
function renderTable(){
let rows = document.querySelectorAll("#studentTable tr");
let start = (currentPage - 1) * rowsPerPage;
let end = start + rowsPerPage;
rows.forEach((row,index)=>{
row.style.display =
(index >= start && index < end)
? ""
: "none";
});
renderPagination(rows.length);
}
function renderPagination(totalRows){
let pageCount = Math.ceil(totalRows / rowsPerPage);
let pagination = document.getElementById("pagination");
pagination.innerHTML = "";
for(let i=1;i<=pageCount;i++){
let btn = document.createElement("button");
btn.innerText = i;
btn.className =
"px-3 py-1 rounded bg-white border hover:bg-primary hover:text-white";
btn.onclick = function(){
currentPage = i;
renderTable();
};
pagination.appendChild(btn);
}
}
function updateStudentCount(){
let total = document.querySelectorAll("#studentTable tr").length;
document.getElementById("studentCount").innerText =
total + " học viên";
}
