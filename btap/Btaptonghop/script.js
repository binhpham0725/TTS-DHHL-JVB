var giaMonAn = [33000, 20000, 25000];
var soLuong = [0, 0, 0];

function format(so) {
    return so.toLocaleString('vi-VN') + 'đ';
}

function tinhTien(viTri, sl) {
    sl = parseInt(sl) || 0;
    if (sl < 0) sl = 0;

    soLuong[viTri] = sl;

    var tien = giaMonAn[viTri] * sl;
    document.getElementById('tt' + viTri).textContent = format(tien);
    document.getElementById('hd' + viTri).textContent = format(tien);

    tinhTong();
}

function tinhTong() {
    var tong = 0;
    for (var i = 0; i < soLuong.length; i++) {
        tong += giaMonAn[i] * soLuong[i];
    }

    var phanTram = parseInt(document.getElementById('phanTramGiam').value) || 0;
    var soTienGiam = Math.round(tong * phanTram / 100);
    var thanhTien = tong - soTienGiam;

    document.getElementById('tongCong').textContent = format(tong);
    document.getElementById('soTienGiam').textContent = format(soTienGiam);
    document.getElementById('thanhTien').textContent = format(thanhTien);
}

function datHang() {
    var tt = document.getElementById('thanhTien').textContent;
    alert('Đặt hàng thành công!\nThành tiền: ' + tt);
}