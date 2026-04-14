<div class="admin-container">
    <header class="page-header">
        <h2 class="page-header__title">Quản lý phòng</h2>
        <div style="display:flex;gap:0.5rem">
            <button class="btn" id="btnExport" style="background:var(--bs-success)">
                <i class="fa-solid fa-file-export"></i> Xuất CSV
            </button>
            <button class="btn" id="btn-open-add-room">
                <i class="fas fa-plus-circle"></i> Thêm phòng
            </button>
        </div>
    </header>

    <section class="data-card">
        <div class="table-tools">
            <div class="search-input">
                <input type="text" id="keyword-room" placeholder="Tìm tên phòng...">
                <i class="fas fa-search"></i>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="data-table" id="table-rooms">
                <thead>
                    <tr class="text-nowrap">
                        <th>STT</th>
                        <th>Số phòng</th>
                        <th>Loại phòng</th>
                        <th>Giới tính</th>
                        <th>Sĩ số</th>
                        <th>Trạng thái</th>
                        <th class="no_export">Thao tác</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </section>

    <!-- MODAL THÊM / SỬA -->
    <div class="modal" id="modal-room">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">Thêm phòng</h3>
                <button type="button" class="btn btn-close modal-close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-room">
                    <input type="hidden" name="id">
                    <div class="row">
                        <div class="input-group col">
                            <label>Tên phòng</label>
                            <input type="text" name="room_name" rules="required" placeholder="VD: A101">
                            <span class="form-message"></span>
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
                            <label>Loại phòng</label>
                            <select name="room_type" id="room_type"></select>
                        </div>
                        <div class="input-group col" id="wrap-room-status" style="display:none">
                            <label>Trạng thái</label>
                            <select name="room_status">
                                <option value="1">Hoạt động</option>
                                <option value="0">Sửa chữa</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer" style="margin-top:1rem;text-align:right">
                        <button type="submit" class="btn btn-submit">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL XÁC NHẬN XÓA -->
    <div class="modal" id="modal-delete-room">
        <div class="modal-container" style="max-width:400px">
            <div class="modal-header">
                <h3>Xác nhận xóa</h3>
                <button class="btn btn-close modal-close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem 1rem">
                <p>Bạn có chắc muốn xóa phòng <strong id="delete-room-name"></strong>?</p>
                <input type="hidden" id="delete-room-id">
                <div style="margin-top:1.5rem;display:flex;gap:0.5rem;justify-content:flex-end">
                    <button class="btn modal-close" style="background:var(--n-200);color:var(--n-800)">Hủy</button>
                    <button class="btn" id="btn-confirm-delete-room" style="background:var(--bs-danger)">Xóa</button>
                </div>
            </div>
        </div>
    </div>
</div>
