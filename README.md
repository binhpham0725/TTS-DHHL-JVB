# Refactor Structure
## Structure mới
```text
Project/
|-- index.php
|-- config/
|   |-- database.php
|-- core/
|   |-- AuthService.php
|   |-- StudentService.php
|-- pages/
|   |-- auth/
|   |   |-- login.php
|   |-- students/
|       |-- index.php
|       |-- edit.php
|-- components/
|   |-- auth-toast.php
|   |-- student-create-modal.php
|   |-- student-dialogs.php
|   |-- student-table.php
|   |-- student-toasts.php
|-- services/
|   |-- authService.js
|   |-- studentService.js
|-- api/
|   |-- auth/
|   |   |-- login.php
|   |   |-- signup.php
|   |-- students/
|       |-- count.php
|       |-- create.php
|       |-- delete.php
|       |-- export.php
|       |-- inline-update.php
|       |-- update.php
|-- assets/
|   |-- css/
|   |   |-- auth.css
|   |   |-- students.css
|   |   |-- edit-student.css
|   |-- js/
|   |   |-- auth.js
|   |   |-- auth-particles.js
|   |   |-- students-page.js
|   |   |-- edit-student.js
```

## Phân chia từng phần
### `pages/`
Chứa các trang chính để mở trên trình duyệt.
- `pages/auth/login.php`: trang đăng nhập / đăng ký
- `pages/students/index.php`: trang danh sách sinh viên
- `pages/students/edit.php`: trang sửa thông tin sinh viên

### `components/`
Chứa các phần giao diện được tách ra từ page để file chính đỡ dài hơn.
- bảng sinh viên
- toast
- popup xác nhận
- form thêm sinh viên

### `core/`
Chứa phần xử lý dữ liệu phía PHP.
- xử lý user
- xử lý sinh viên
- query database
- create / update / delete / export

### `services/`
Chứa JS gọi API bằng `fetch`.
- login / signup
- tạo sinh viên
- xóa sinh viên
- sửa nhanh inline
- lấy số lượng sinh viên

### `api/`
Chứa các file PHP nhận request từ frontend và xử lý logic.
- validate dữ liệu
- gọi hàm trong `core/`
- trả kết quả về cho frontend

### `assets/`
Chứa file tĩnh như CSS và các JS điều khiển UI theo từng màn hình.

### `config/`
Chứa phần cấu hình chung, hiện tại là file kết nối database.

## Flow hiện tại
### Chạy project
- import file SQL vào phpMyAdmin
- kiểm tra database name là `student_registration`
- mở project bằng Visual Studio Code
- chạy `PHP Server: Serve Project`

### Entry point
- `index.php` sẽ chuyển qua `pages/auth/login.php`

### Trang login
- giao diện ở `pages/auth/login.php`
- JS điều khiển giao diện ở `assets/js/auth.js`
- JS gọi API ở `services/authService.js`
- gọi API:
  - `api/auth/login.php`
  - `api/auth/signup.php`

### Trang danh sách sinh viên
- giao diện chính ở `pages/students/index.php`
- bảng và popup được tách ra ở `components/`
- JS điều khiển giao diện ở `assets/js/students-page.js`
- JS gọi API ở `services/studentService.js`

### Trang sửa sinh viên
- giao diện ở `pages/students/edit.php`
- submit sang `api/students/update.php`

## Từ structure cũ sang structure mới
### Cũ
```text
login/
homepage/
edit/
database/
images/
```

### Mới
- `login/` -> tách thành `pages/auth`, `api/auth`, `assets/css`, `assets/js`
- `homepage/` -> tách thành `pages/students`, `components`, `api/students`, `services`, `assets`
- `edit/` -> chuyển vào `pages/students/edit.php` và `assets/...`
- `database/` -> tách thành `config/database.php`, `core/` và `api/`
- `images/` -> đã bỏ do không còn dùng social icon

## Những gì đã cải thiện
- nhìn structure rõ hơn
- page không còn ôm quá nhiều xử lý trong cùng một file
- phần giao diện, API, logic và assets đã tách riêng
- phần PHP xử lý và phần JS gọi API đã tách vai trò rõ hơn
- dễ theo dõi hơn khi đọc project
- search sinh viên hiện tại tìm trên toàn bộ database, không chỉ trong page đang mở
- đã thêm validate backend cho signup, create và update
- GPA hiện được giới hạn tối đa là 4.0

## Phần chưa làm sâu
Hiện tại em mới tập trung vào phần structure nên chưa tối ưu những phần sau:
- naming
- tối ưu performance
- chuẩn hóa code style kỹ hơn
- chia service nhỏ thêm nữa

## Notes
> Em đã tách lại project theo hướng page, component, service, api và assets riêng.
> Trước đó nhiều file đang vừa có HTML vừa có xử lý logic nên hơi khó theo dõi.
> Sau khi tách lại thì structure rõ hơn và dễ đọc hơn.
