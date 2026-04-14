
<div class="admin-container">
    <header class="page-header">
        <div class="page-header__info">
            <h2 class="page-header__title">Quản lý phòng</h2>
        </div>
        <div class="page-header__actions">
            <button  class="btn btn--primary btn--open__model"  id = "btn-model-room">
                <i class="fas fa-plus-circle"></i> Thêm phòng
            </button>
        </div>
    </header>
    <section class="data-card">
        <div class="table-tools">
            <div class="table-tools__limit">
                <span>Hiển thị</span>
                <select class="select-custom" id = "room-limit-select">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                </select>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="data-table" id="table-rooms">
                <thead>
                <tr class="text-nowrap">
                    <th style="width: 50px;text-align: center">STT</th>
                    <th>Số phòng</th>
                    <th>Loại phòng</th>
                    <th>Số người ở hiện tại</th>
                    <th>Trạng thái</th>
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
    <div class="modal" id="modal-room">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">Cập nhật thông tin phòng</h3>
                <button type="button" class="btn btn-close modal-close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-room">
                    <input type="text" name="id" id="edit-id" hidden>
                    <div class="row">
                        <div class="input-group col">
                            <label>Tên phòng:</label>
                            <input type="text" rules="required" name="room_name" id="edit-name" placeholder="Nhập tên phòng">
                            <span class="form-message"></span>
                        </div>
                        <div class="input-group col">
                            <label for="room_gender">Phòng dành cho:</label>
                            <select name="gender" id="room_gender" class="select-custom">
                                <option value="0">Nam</option>
                                <option value="1">Nữ</option>
                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="input-group col">
                            <label for="">Loại phòng:</label>
                            <select name="room_type" id="room_type" class="select-custom">

                            </select>
                        </div>
                        <div class="input-group col">
                            <label for="">Số thành viên tối đa:</label>
                            <input type="text" rules="required" name="maxPeople" id="edit-phone">
                            <span class="form-message"></span>
                        </div>
                    </div>

                    <div class="modal-footer" style="margin-top: 20px; text-align: right;">
                        <button type="submit" class="btn btn-primary btn-submit">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>