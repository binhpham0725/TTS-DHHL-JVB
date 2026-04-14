<div class="admin-container">
    <header class="page-header">
        <h2 class="page-header__title">Quản lý sinh viên</h2>
        <button class="btn" id="btn-open-add">
            <i class="fas fa-plus-circle"></i> Thêm sinh viên
        </button>
    </header>

    <section class="data-card">
        <div class="table-tools">
            <div class="search-input">
                <input type="text" id="keyword" placeholder="Tìm mã SV, tên, email...">
                <i class="fas fa-search"></i>
            </div>
            <div style="display:flex;align-items:center;gap:0.5rem">
                <span>Hiển thị</span>
                <select class="select-custom" id="per_page">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="data-table" id="table-students">
                <thead>
                    <tr class="text-nowrap">
                        <th>STT</th>
                        <th>Mã SV</th>
                        <th>Họ và tên</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>CCCD</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Địa chỉ</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div class="table-pagination" style="margin-top:1rem">
            <ul class="pagination-list"></ul>
        </div>
    </section>

    <!-- MODAL THÊM -->
    <div class="modal" id="modal-add-student">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Thêm sinh viên</h3>
                <button class="btn btn-close modal-close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <form id="form-add-student">
                    <div class="row">
                        <div class="input-group col">
                            <label>Mã số sinh viên</label>
                            <input type="text" name="mssv" rules="required" placeholder="VD: SV001">
                            <span class="form-message"></span>
                        </div>
                        <div class="input-group col">
                            <label>Họ và tên</label>
                            <input type="text" name="name" rules="required" placeholder="Nguyễn Văn A">
                            <span class="form-message"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group col">
                            <label>Ngày sinh</label>
                            <input type="date" name="birthday">
                        </div>
                        <div class="input-group col">
                            <label>Giới tính</label>
                            <select name="gender">
                                <option value="1">Nam</option>
                                <option value="0">Nữ</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group col">
                            <label>CCCD</label>
                            <input type="text" name="cccd" rules="required" placeholder="012345678901">
                            <span class="form-message"></span>
                        </div>
                        <div class="input-group col">
                            <label>Email</label>
                            <input type="text" name="email" rules="required|email" placeholder="example@email.com">
                            <span class="form-message"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group col">
                            <label>Số điện thoại</label>
                            <input type="text" name="phone" rules="required" placeholder="0901234567">
                            <span class="form-message"></span>
                        </div>
                        <div class="input-group col">
                            <label>Địa chỉ</label>
                            <input type="text" name="address" rules="required">
                            <span class="form-message"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group col">
                            <label>Mật khẩu</label>
                            <input type="password" name="password" rules="required" placeholder="Tối thiểu 6 ký tự">
                            <span class="form-message"></span>
                        </div>
                    </div>
                    <div class="modal-footer" style="margin-top:1rem;text-align:right">
                        <button type="submit" class="btn btn-submit">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL SỬA -->
    <div class="modal" id="modal-edit-student">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Cập nhật sinh viên</h3>
                <button class="btn btn-close modal-close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <form id="form-edit-student">
                    <input type="hidden" name="id">
                    <div class="row">
                        <div class="input-group col">
                            <label>Mã số sinh viên</label>
                            <input type="text" name="mssv" rules="required">
                            <span class="form-message"></span>
                        </div>
                        <div class="input-group col">
                            <label>Họ và tên</label>
                            <input type="text" name="name" rules="required">
                            <span class="form-message"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group col">
                            <label>Ngày sinh</label>
                            <input type="date" name="birthday">
                        </div>
                        <div class="input-group col">
                            <label>Giới tính</label>
                            <select name="gender">
                                <option value="1">Nam</option>
                                <option value="0">Nữ</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group col">
                            <label>CCCD</label>
                            <input type="text" name="cccd" rules="required">
                            <span class="form-message"></span>
                        </div>
                        <div class="input-group col">
                            <label>Email</label>
                            <input type="text" name="email" rules="required|email">
                            <span class="form-message"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group col">
                            <label>Số điện thoại</label>
                            <input type="text" name="phone" rules="required">
                            <span class="form-message"></span>
                        </div>
                        <div class="input-group col">
                            <label>Địa chỉ</label>
                            <input type="text" name="address" rules="required">
                            <span class="form-message"></span>
                        </div>
                    </div>
                    <div class="modal-footer" style="margin-top:1rem;text-align:right">
                        <button type="submit" class="btn btn-submit">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL XÁC NHẬN XÓA -->
    <div class="modal" id="modal-delete-student">
        <div class="modal-container" style="max-width:400px">
            <div class="modal-header">
                <h3>Xác nhận xóa</h3>
                <button class="btn btn-close modal-close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem 1rem">
                <p>Bạn có chắc muốn xóa sinh viên <strong id="delete-student-name"></strong>?</p>
                <input type="hidden" id="delete-student-id">
                <div style="margin-top:1.5rem;text-align:right;display:flex;gap:0.5rem;justify-content:flex-end">
                    <button class="btn modal-close" style="background:var(--n-200);color:var(--n-800)">Hủy</button>
                    <button class="btn" id="btn-confirm-delete" style="background:var(--bs-danger)">Xóa</button>
                </div>
            </div>
        </div>
    </div>
</div>
