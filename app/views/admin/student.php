<div class="admin-container">
    <header class="page-header">
        <div class="page-header__info">
            <h2 class="page-header__title">Quản tài khoản sinh viên</h2>
        </div>
        <div class="page-header__actions">
            <button  class="btn btn--primary btn--open__model" data-target="#model-student">
                <i class="fas fa-plus-circle"></i> Thêm sinh viên
            </button>
        </div>
    </header>
    <section class="data-card">
        <div class="table-tools">
            <div class="table-tools__limit">
                <span>Hiển thị</span>
                <select class="select-custom" id = "student-limit-select">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                </select>
            </div>
            <div class="table-tools__search">
                <div class="search-input" >
                    <input type="text" placeholder="Tìm mã SV, tên..." id="search-student">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="data-table" id="table-students">
                <thead>
                <tr class="text-nowrap">
                    <th style="width: 50px;text-align: center">STT</th>
                    <th>Mã SV</th>
                    <th>Họ và tên</th>
                    <th>Ngày sinh</th>
                    <th>Giới tính</th>
                    <th>CCCD</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ</th>
                    <th style="width: 100px" class="text-center">Thao tác</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="table-pagination" id="student-pagination">
            <ul class="pagination-list">
                <li><a href="#" class="pagination-item"><i class="fas fa-angle-left"></i></a></li>
                <li><a href="#" class="pagination-item pagination-item--active">1</a></li>
                <li><a href="#" class="pagination-item">2</a></li>

                <li><a href="#" class="pagination-item">4</a></li>
                <li>...</li>
                <li><a href="#" class="pagination-item">14</a></li>
                <li><a href="#" class="pagination-item"><i class="fas fa-angle-right"></i></a></li>
            </ul>
        </div>
    </section>
    <div class="modal" id="model-student">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Cập nhật thông tin sinh viên</h3>
                <button class="btn btn-close modal-close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="" class="" id="formCapNhatSV">
                    <input type="text" name="id" hidden>
                    <div class="row">
                        <div class="input-group col">
                            <label>
                                Mã số sinh viên:
                            </label>
                            <input type="text" rules="required" name="mssv" id="mssv" placeholder="Nhập mã số sinh viên">
                            <span class="form-message"></span>
                        </div>
                        <div class="input-group  col">
                            <label>Họ và tên:</label>
                            <input class="" type="text" rules="required" name="name" placeholder="Nhập họ tên sinh viên ">
                            <span class="form-message"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group col">
                            <label for="">Ngày sinh:</label>
                            <input type="date" name="birthday" id="">
                        </div>
                        <div class="input-group col">
                            <label for="">Email:</label>
                            <input name="email" rules="required|email" id="" placeholder="Nhập địa chỉ email">
                            <span class="form-message"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group col">
                            <label>
                                Giới tính:
                            </label>
                            <select  id="gender" name="gender" >
                                <option value="0">Nam</option>
                                <option value="1">Nữ</option>
                            </select>
                        </div>
                        <div class="input-group col">
                            <label>CCCD:</label>
                            <input type="text" name="cccd" rules="required|cccd" placeholder="">
                            <span class="form-message"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-group col">
                            <label for="">Số điện thoại:</label>
                            <input type="text" rules="required" name="phone">
                            <span class="form-message"></span>
                        </div>
                        <div class="input-group col">
                            <label for="">Địa chỉ:</label>
                            <input type="text" rules="required" name="address">
                            <span class="form-message"></span>
                        </div>
                    </div>
                    <div class="row">

                        <div class="input-group col">
                            <label for="">Mật khẩu tài khoản:</label>
                            <input type="text" rules="required" name="password">
                            <span class="form-message"></span>
                        </div>
                    </div>
                <button class="btn btn-submit">
                    Lưu
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
