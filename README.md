# Refactor Structure
## Structure mới
```text
student-app/
|-- index.php
|-- config/
|   |-- database.php
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
|   |-- AuthService.php
|   |-- StudentService.php
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

### `services/`
Chứa phần xử lý dữ liệu phía PHP.
- xử lý user
- xử lý sinh viên
- query database
- create / update / delete / export

### `api/`
Chứa các file nhận request từ frontend.
- login
- signup
- tạo sinh viên
- xóa sinh viên
- sửa sinh viên
- export csv

### `assets/`
Chứa file tĩnh: CSS và JS.

### `config/`
Chứa phần cấu hình chung, hiện tại là file kết nối database.

## Flow hiện tại
### Dùng XAMPP
- bật `Apache` và `MySQL` trong XAMPP
- import file SQL vào phpMyAdmin
- kiểm tra database name là `student_registration`
- Mở Visual Studio Code và chạy index.php
Ví dụ đường dẫn:
```text
C:\xampp\htdocs\student-app
```

Mở trên trình duyệt:
```text
http://localhost/student-app
```

### Entry point
- `index.php` sẽ chuyển qua `pages/auth/login.php`

### Trang login
- giao diện ở `pages/auth/login.php`
- JS ở `assets/js/auth.js`
- gọi API:
  - `api/auth/login.php`
  - `api/auth/signup.php`

### Trang danh sách sinh viên
- giao diện chính ở `pages/students/index.php`
- bảng và popup được tách ra ở `components/`
- JS gọi các API trong `api/students/`

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
- `homepage/` -> tách thành `pages/students`, `components`, `api/students`, `assets`
- `edit/` -> chuyển vào `pages/students/edit.php` và `assets/...`
- `database/` -> tách thành `config/database.php` và `services/`
- `images/` -> đã bỏ do không còn dùng social icon

## Những gì đã cải thiện
- nhìn structure rõ hơn
- page không còn ôm quá nhiều xử lý trong cùng một file
- phần giao diện, API, logic và assets đã tách riêng
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
