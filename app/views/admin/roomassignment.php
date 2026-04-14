
<div class="admin-container">
    <header class="page-header">
        <div class="page-header__info">
            <h2 class="page-header__title">Yêu cầu đăng kí phòng</h2>
        </div>

    </header>
    <section class="data-card">
        <div class="table-tools">
            <form id="form-filter">
                <input type="text" placeholder="Tìm kiếm" id="keyword">
                <select name="status" id="status">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1">Đã duyệt</option>
                    <option value="0">Chưa duyệt</option>
                    <option value="2">Từ chối</option>
                </select>
                <select name="time_start" id="time_start">
                    <option value="">Thời gian</option>
                    <option value="1">1 ngày</option>
                    <option value="2">7 ngày</option>
                    <option value="3">1 tháng</option>
                    <option value="4">3 tháng</option>
                </select>
            </form>
        </div>
        <div class="table-wrapper">
            <table class="data-table" id="table">
                <thead>
                    <th style="width: 50px;text-align: center">STT</th>
                    <th class="text-nowrap">Tên sinh viên</th>
                    <th class="text-center">Loại phòng</th>
                    <th class="text-center"> Ngày Nộp đơn </th>
                    <th class="text-center">Ngày vào phòng</th>
                    <th class="text-center">Ngày ra phòng</th>
                    <th>Trạng thái</th>
                    <th style="width: 100px" class="text-center no_export">Thao tác</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="table-pagination">
            <div style="display:flex;align-items:center;gap:0.5rem;margin-right:0.3rem">
                <p>
                    Phân trang
                </p>
                <select name="" id="per_page">
                    <option value="10">10</option>
                    <option value="20" selected>20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <ul class="pagination-list">
                <li><button  class="pagination-item"><i class="fas fa-angle-left"></i></button></li>
                <li><button  class="pagination-item pagination-item--active">1</button></li>
                <li><button  class="pagination-item">2</button></li>
                <li><button  class="pagination-item">4</button></li>
                <li>...</li>
                <li><button  class="pagination-item">14</button></li>
                <li><button  class="pagination-item"><i class="fas fa-angle-right"></i></button></li>
            </ul>
        </div>
    </section>
    <div class="modal" id="modal-assign-room">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">Xếp phòng cho sinh viên</h3>
            <button type="button" class="btn btn-close modal-close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="form-assign-room">
                <input type="hidden" name="id" id ="assignment_id" >
                <div class="row">
                    <div class="input-group col">
                        <label>Sinh viên:</label>
                        <input type="text" id="student-name" readonly style="background: #f4f4f4;">
                    </div>
                    <div class="input-group col">
                        <label>Loại phòng:</label>
                        <input type="text" id="room-type-name" readonly style="background: #f4f4f4;">
                    </div>
                     
                </div>
                <div class="row">
                     <div class="input-group col">
                        <label>Số phòng còn trống:</label>
                        <select name="room_id" id="select-room-id" class="select-custom">
                           
                            </select>
                        <span class="form-message"></span>
                    </div>
                    <div class="input-group col">
                        <label>Ngày vào ở:</label>
                        <input type="date" name="check_in" id="check-in-date" rules="required">
                        <span class="form-message"></span>
                    </div>
                </div>
                <div class="row">
                        <div class="input-group col">
                        <label>Ghi chú:</label>
                        <input type="text" name="note" placeholder="Ví dụ: Em  ngủ ở giường số 2 nhé">
                    </div>
                </div>
                <div class="modal-footer" style="margin-top: 20px; text-align: right;">
                    <button type="submit" class="btn btn-primary btn-submit">Xác nhận xếp phòng</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal từ chối -->
 <div class="modal" id="modal-reject-room">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">Lý do từ chối đăng kí phòng</h3>
            <button type="button" class="btn btn-close modal-close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="form-reject-room">
                <input type="hidden" name="id" id ="assignment_id" >
                <div class="row">
                        <div class="input-group col">
                        <label>Lý do từ chối:</label>
                        <input rules="required" type="text" name="note" placeholder="Ví dụ: Cập nhật đầy đủ các thông tin cần thiết trước khi đăng khí phòng nhé">
                        <span class="form-message"></span>
                    </div>
                </div>
                <div class="modal-footer" style="margin-top: 20px; text-align: right;">
                    <button type="submit" class="btn btn-primary btn-submit">Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</div>
    </div>