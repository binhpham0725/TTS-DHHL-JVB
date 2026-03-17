// =================
// LOGOUT
// =================
document.getElementById("logoutBtn").onclick = function(){
    window.location.href = "logout.html";
};


// =================
// BUBBLE BACKGROUND
// =================
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


// =================
// TÍNH TUỔI
// =================
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


// =================
// PAGINATION
// =================
const rowsPerPage = 10;
let currentPage = 1;
let stt = 1;


// =================
// LOAD DATA FROM MYSQL
// =================
function loadStudents(){

    fetch("get_students.php")
    .then(res => res.json())
    .then(data => {

        let table = document.getElementById("studentTable");
        table.innerHTML = "";

        stt = 1;

        data.forEach(item => {

            let row = `
            <tr class="table-row">
                <td>${stt++}</td>
                <td>${item.name}</td>
                <td>${item.gender}</td>
                <td>${item.dob}</td>
                <td>${item.age}</td>
                <td>${item.email}</td>
                <td>${item.address}</td>
                <td>
                    <button class="delete-btn" onclick="deleteRow(this)">
                        <span class="material-symbols-outlined">delete</span>
                    </button>
                </td>
            </tr>
            `;

            table.innerHTML += row;

        });

        updateStudentCount();
        currentPage = 1;
        renderTable();

    })
    .catch(err => {
        console.error("Lỗi load dữ liệu:", err);
    });

}


// =================
// ĐĂNG KÝ
// =================
document.querySelector(".register-btn").onclick = function(){

    let name = document.getElementById("name").value.trim();
    let email = document.getElementById("email").value.trim();
    let address = document.getElementById("address").value.trim();

    let dobValue = dob.value;
    let ageValue = parseInt(age.value);

    let course = document.getElementById("course").value;

    let genderEl = document.querySelector('input[name="gender"]:checked');

    // VALIDATE
    if(!name || !dobValue || !genderEl || !course){
        alert("Vui lòng nhập đầy đủ thông tin bắt buộc");
        return;
    }

    let gender = genderEl.value;

    if(gender !== "Nữ"){
        alert("Chỉ cho phép Nữ");
        return;
    }

    if(ageValue < 18){
        alert("Tuổi phải >= 18");
        return;
    }

    // CALL API
    fetch("api.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            name,
            gender,
            dob: dobValue,
            age: ageValue,
            email,
            address,
            course
        })
    })
    .then(res => res.json())
    .then(data => {

        console.log("API response:", data);

        if(data.status === "success"){

            loadStudents();

            alert("Đăng ký thành công 🎉");

        }else{
            alert("Lỗi lưu database ❌");
        }

    })
    .catch(err => {
        console.error("Lỗi API:", err);
        alert("Không kết nối được server ❌");
    });

};


// =================
// DELETE (UI)
// =================
function deleteRow(btn){

    btn.parentElement.parentElement.remove();

    updateStudentCount();
    renderTable();

}


// =================
// RENDER TABLE
// =================
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


// =================
// PAGINATION BUTTON
// =================
function renderPagination(totalRows){

    let pageCount = Math.ceil(totalRows / rowsPerPage);

    let pagination = document.getElementById("pagination");

    pagination.innerHTML = "";

    for(let i=1;i<=pageCount;i++){

        let btn = document.createElement("button");

        btn.innerText = i;

        btn.className =
        "px-3 py-1 rounded bg-white border hover:bg-blue-500 hover:text-white";

        btn.onclick = function(){
            currentPage = i;
            renderTable();
        };

        pagination.appendChild(btn);

    }

}


// =================
// COUNT STUDENTS
// =================
function updateStudentCount(){

    let total = document.querySelectorAll("#studentTable tr").length;

    document.getElementById("studentCount").innerText =
    total + " học viên";

}


// =================
// LOAD PAGE
// =================
window.onload = function(){
    loadStudents();
};