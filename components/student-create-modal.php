<div id="formOverlay">
    <div id="formBox">
        <div class="form-tabs">
            <button type="button" class="tab active" onclick="switchTab(1)">Cá nhân</button>
            <button type="button" class="tab" onclick="switchTab(2)">Học tập</button>
        </div>

        <form id="studentForm" novalidate>
            <div class="tab-content active" id="tab1">
                <div class="form-row">
                    <input name="name" placeholder="Họ tên" required style="width:90%;">
                </div>
                <div class="form-row" style="display:flex; gap:6px;">
                    <select name="gender" required style="flex:1;">
                        <option value="">Giới tính</option>
                        <option>Nam</option>
                        <option>Nữ</option>
                    </select>
                    <input type="date" name="dob" required style="flex:1;">
                </div>
                <div class="form-row">
                    <input type="email" name="email" placeholder="Email" required style="width:90%;">
                </div>
                <div class="form-row">
                    <input name="address" placeholder="Địa chỉ" style="width:90%;">
                </div>
                <div class="form-row form-buttons">
                    <button type="submit">Lưu</button>
                    <button type="button" onclick="closeForm()">Hủy</button>
                </div>
            </div>

            <div class="tab-content" id="tab2">
                <div class="form-row" style="display:flex; gap:6px;">
                    <input name="major" placeholder="Chuyên ngành" required style="flex:7;">
                    <input name="course" placeholder="Khóa học" required style="flex:3;">
                </div>
                <div class="form-row">
                    <input type="number" step="0.01" min="0" max="4" name="gpa" placeholder="GPA" required style="width:90%;">
                </div>
                <div class="form-row" style="display:flex; gap:6px;">
                    <select name="rank" required style="flex:1;">
                        <option value="">Xếp loại</option>
                        <option>Xuất sắc</option>
                        <option>Giỏi</option>
                        <option>Khá</option>
                        <option>Trung bình</option>
                        <option>Yếu</option>
                    </select>
                    <select name="status" required style="flex:1;">
                        <option value="">Tình trạng</option>
                        <option>Năm 1</option>
                        <option>Năm 2</option>
                        <option>Năm 3</option>
                        <option>Năm 4</option>
                        <option>Đã tốt nghiệp</option>
                        <option>Khác</option>
                    </select>
                </div>
                <div class="form-row form-buttons">
                    <button type="submit">Lưu</button>
                    <button type="button" onclick="closeForm()">Hủy</button>
                </div>
            </div>
        </form>
    </div>
</div>
