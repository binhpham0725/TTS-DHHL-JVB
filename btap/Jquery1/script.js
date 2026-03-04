function toggleForm() {
    const form = document.getElementById("loginForm");
    const btn = document.getElementById("mainLoginBtn");
    if (form) {
        form.classList.toggle("active");
        if(form.classList.contains("active")) btn.style.display = "none";
    }
}

let danhSach = [];

const btnDK = document.getElementById("btnDK");
const btnLogout = document.getElementById("btnLogout");
const thongbao = document.getElementById("thongbao");
const formDK = document.getElementById("formDK");

function hienThongBao(noiDung, mau) {
    thongbao.innerText = noiDung;
    thongbao.style.color = mau;
    thongbao.classList.add("active");

    setTimeout(() => {
        thongbao.classList.remove("active");
    }, 2500);
}

if (formDK) {
    formDK.addEventListener("submit", function (e) {
        e.preventDefault();

        let hoten = document.getElementById("hoten").value.trim();
        let gioitinh = document.getElementById("gioitinh").value;
        let tuoi = parseInt(document.getElementById("tuoi").value);
        let mail = document.getElementById("mail").value;
        let diachi = document.getElementById("diachi").value;

        if (hoten === "" || gioitinh === "" || isNaN(tuoi)) {
            hienThongBao("Vui lòng nhập đầy đủ thông tin bắt buộc", "#ff4757");
            return;
        }

        if (gioitinh !== "Nữ" || tuoi < 18) {
            hienThongBao("Đăng ký thất bại: Chỉ nhận Nữ và tuổi ≥ 18", "#ff4757");
            return;
        }

        hienThongBao("Đang gửi dữ liệu...", "#00d2ff");

        setTimeout(function () {
            let hocvien = { hoten, gioitinh, tuoi, mail, diachi };
            danhSach.push(hocvien);
            renderTable();
            hienThongBao("Đăng ký thành công!", "#2fee7f");
            clearForm();
        }, 800);
    });
}

function renderTable() {
    let bang = document.getElementById("bang");
    if (!bang) return;
    bang.innerHTML = "";
    danhSach.forEach(function (item, index) {
        let row = `
            <tr>
                <td>${index + 1}</td>
                <td>${item.hoten}</td>
                <td>${item.gioitinh}</td>
                <td>${item.tuoi}</td>
                <td>${item.mail}</td>
                <td>${item.diachi}</td>
                <td><button class="btn-xoa" onclick="xoaDong(${index})">Xóa</button></td>
            </tr>`;
        bang.innerHTML += row;
    });
}

function xoaDong(index) {
    danhSach.splice(index, 1);
    renderTable();
}

function clearForm() {
    formDK.reset();
}

if (btnLogout) {
    btnLogout.addEventListener("click", function () {
        localStorage.setItem("logoutMessage", "Logout thành công");
        window.location.href = "index.html";
    });
}

window.addEventListener("load", function () {
    const message = localStorage.getItem("logoutMessage");
    const logoutBox = document.getElementById("logoutNotice");

    if (message && logoutBox) {
        logoutBox.innerText = message;
        setTimeout(() => { logoutBox.classList.add("show"); }, 100);
        setTimeout(() => {
            logoutBox.classList.remove("show");
            localStorage.removeItem("logoutMessage");
        }, 3000);
    }
});

const loginForm = document.getElementById("loginForm");

if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const email = document.getElementById("email");
        const password = document.getElementById("password");

        if (!email.checkValidity()) {
            alert("Email không đúng định dạng!");
            return;
        }

        if (password.value.length < 8) {
            alert("Mật khẩu phải ít nhất 8 ký tự!");
            return;
        }

        window.location.href = "noidung.html";
    });
}