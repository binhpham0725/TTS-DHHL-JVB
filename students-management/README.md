# Students Management
Ứng dụng quản lý sinh viên viết bằng PHP thuần + MySQL, chạy local bằng XAMPP và thao tác cơ sở dữ liệu qua phpMyAdmin.
Project đã được tổ chức lại theo hướng MVC nhưng vẫn giữ các URL cũ để tương thích với giao diện và các file điều hướng trước đó.

## 1. Tổng quan
- Đăng nhập giảng viên hoặc admin
- Quản lý sinh viên theo khóa/lớp
- Quản lý môn học và tỷ trọng điểm
- Nhập, sửa, xóa, import, export điểm học phần
- Dashboard thống kê bằng biểu đồ

## 2. Công nghệ sử dụng
- PHP 8.x
- MySQL
- phpMyAdmin
- XAMPP
- HTML, CSS, JavaScript
- Bootstrap 5
- Font Awesome
- Chart.js

## 3. Kiến trúc thực tế
Project hiện chạy theo mô hình lai:
- `app/` chứa phần MVC chính
- `auth/`, `interface/`, `function/`, `reports/` là route mỏng
- route mỏng nhận request từ URL cũ rồi gọi vào controller mới
- cách làm này giúp nâng cấp code dần mà không phá vỡ đường dẫn cũ

## 4. Cấu trúc thư mục
```text
students-management/
|-- .gitignore
|-- index.php
|-- README.md
|-- students_management.sql
|-- app/
|   |-- bootstrap.php
|   |-- core/ (Auth.php, Controller.php, Session.php)
|   |-- controllers/ (AuthController.php, DashboardController.php, ScoreController.php, StudentController.php, SubjectController.php)
|   |-- models/ (TeacherModel.php, StudentModel.php, SubjectModel.php, ScoreModel.php)
|   `-- views/ (auth/login.php, dashboard/index.php, scores/index.php, students/index.php, students/partials/*.php, subjects/index.php, subjects/form.php)
|-- assets/ (css/*.css, js/*.js)
|-- auth/ (login.php, logout.php)
|-- config/ (db.php)
|-- function/
|   |-- students/ (add.php, edit.php, del.php, import.php, export.php)
|   |-- subjects/ (add.php, edit.php, del.php)
|   `-- scores/ (add.php, edit.php, del.php, import.php, export.php, list.php, save.php)
|-- helpers/ (functions.php)
|-- interface/ (index.php, listsv.php, scores.php, subjects.php)
`-- reports/ (average.php, ranking.php, result.php)
```

## 5. Vai trò từng khu vực
- `index.php`: điểm vào đầu tiên, kiểm tra session rồi chuyển trang
- `app/bootstrap.php`: nạp kết nối DB, helper, autoload class
- `app/core`: xử lý session, auth, render view, trả JSON
- `app/controllers`: điều phối request và nghiệp vụ
- `app/models`: truy vấn database, validate, CRUD, thống kê
- `app/views`: giao diện HTML cho từng màn hình
- `assets`: CSS và JavaScript cho giao diện
- `auth`, `interface`, `function`, `reports`: lớp tương thích URL cũ
- `helpers/functions.php`: hàm dùng chung cho tính điểm, xếp loại, build query
- `students_management.sql`: file SQL để import vào phpMyAdmin

## 6. Luồng chạy chính
1. Người dùng truy cập `index.php`
2. `bootstrap.php` nạp DB và class
3. Session được khởi tạo
4. Nếu đã đăng nhập thì chuyển vào dashboard
5. Nếu chưa đăng nhập thì chuyển sang trang login
6. Các màn hình trong `interface/` gọi controller tương ứng
7. Các thao tác thêm, sửa, xóa, import, export đi qua `function/`
8. Dashboard lấy dữ liệu JSON từ `reports/`

## 7. Tính năng hiện có
- Đăng nhập và đăng xuất bằng bảng `teacher`
- Dashboard theo khóa: tổng số sinh viên, GPA trung bình, tỷ lệ đạt, biểu đồ xếp loại và GPA theo môn
- Quản lý sinh viên: tìm kiếm, lọc lớp, phân trang, thêm, sửa, xóa
- Import và export CSV sinh viên
- Quản lý môn học: thêm, sửa, xóa, cấu hình tỷ trọng điểm
- Quản lý điểm theo môn và lớp
- Lưu điểm hàng loạt, xóa điểm, import và export CSV điểm

## 8. Lưu ý về database
Theo file `students_management.sql` và code hiện tại:
- bảng đăng nhập thật là `teacher`
- hiện không có bảng `admins`
- password đang được so sánh trực tiếp trong PHP
- dữ liệu mẫu đang lưu plain text, chưa dùng `password_hash()`
- tài liệu cũ nếu có nhắc MD5 hoặc `admins` thì không còn đúng với code hiện tại

## 9. Schema database
### Bảng `teacher`
| Cột | Kiểu | Ghi chú |
|---|---|---|
| `id` | `INT` | PK, tự tăng |
| `name` | `VARCHAR(100)` | Tên giảng viên |
| `email` | `VARCHAR(100)` | Email đăng nhập, unique |
| `password` | `VARCHAR(255)` | Mật khẩu hiện đang lưu thẳng |
| `phone` | `VARCHAR(15)` | Số điện thoại |

### Bảng `students`
| Cột | Kiểu | Ghi chú |
|---|---|---|
| `id` | `INT` | PK, tự tăng |
| `mssv` | `VARCHAR(20)` | Mã sinh viên, unique |
| `fullname` | `VARCHAR(100)` | Họ và tên |
| `gender` | `ENUM('Nam','Nữ','Khác')` | Giới tính |
| `class` | `VARCHAR(50)` | Lớp như `D16CNTT`, `D17CNTT`, `D18CNTT` |
| `phone` | `VARCHAR(15)` | Số điện thoại |
| `address` | `VARCHAR(255)` | Địa chỉ |
| `birthday` | `DATE` | Ngày sinh |
| `email` | `VARCHAR(30)` | Email, unique |

### Bảng `subject`
| Cột | Kiểu | Ghi chú |
|---|---|---|
| `id` | `INT` | PK, tự tăng |
| `subject_code` | `VARCHAR(20)` | Mã môn, unique |
| `subject_name` | `VARCHAR(100)` | Tên môn học |
| `credits` | `INT` | Số tín chỉ |
| `description` | `TEXT` | Mô tả môn học |
| `attendance_weight` | `INT` | Tỷ trọng chuyên cần |
| `midterm_weight` | `INT` | Tỷ trọng giữa kỳ |
| `final_weight` | `INT` | Tỷ trọng cuối kỳ |

### Bảng `scores`
| Cột | Kiểu | Ghi chú |
|---|---|---|
| `id` | `INT` | PK, tự tăng |
| `student_id` | `INT` | FK tới `students.id` |
| `subject_id` | `INT` | FK tới `subject.id` |
| `attendance_score` | `DECIMAL(4,1)` | Điểm chuyên cần |
| `midterm_score` | `DECIMAL(4,1)` | Điểm giữa kỳ |
| `final_score` | `DECIMAL(4,1)` | Điểm cuối kỳ |
| `scores` | `DECIMAL(5,2)` | Điểm tổng kết |

## 10. Quan hệ giữa các bảng
- `teacher` dùng để xác thực đăng nhập
- `students` chứa danh sách sinh viên
- `subject` chứa danh sách môn học
- `scores` là bảng liên kết giữa sinh viên và môn học
- một sinh viên có thể có nhiều điểm ở nhiều môn
- một môn có thể có điểm của nhiều sinh viên
- khi xóa sinh viên hoặc môn học, điểm liên quan sẽ bị xóa theo `ON DELETE CASCADE`

## 11. Tài khoản mẫu Admin
- Email: `admin@hluv.edu.com.vn`
- Password: `Abcxyz@123`
- Tên hiển thị: `UnKnow`
- Bảng lưu: `teacher`

## 12. Quy tắc nghiệp vụ trong code
- MSSV phải đúng 8 chữ số
- khi thêm mới hoặc import, lớp được suy ra từ 4 số đầu MSSV
- `2023 -> D16CNTT`
- `2024 -> D17CNTT`
- `2025 -> D18CNTT`
- khi sửa sinh viên, lớp được chọn lại từ danh sách cho phép
- điểm từng thành phần chỉ nằm trong khoảng `0` đến `10`
- tổng tỷ trọng môn học phải bằng `100`
- đạt học phần khi điểm tổng kết `>= 5`
- xếp loại theo mức: Xuất sắc, Giỏi, Khá, Trung bình, Yếu

## 13. Công thức tính điểm
Điểm tổng kết được tính trong `helpers/functions.php` theo trọng số từng môn:
```text
Điểm tổng kết =
(Chuyên cần * attendance_weight / 100)
+ (Giữa kỳ * midterm_weight / 100)
+ (Cuối kỳ * final_weight / 100)
```

## 14. Cài đặt bằng XAMPP và phpMyAdmin
1. Chép project vào thư mục `htdocs`
2. Mở `XAMPP Control Panel`, start `Apache` và `MySQL`
3. Truy cập `http://localhost/phpmyadmin`
4. Tạo database tên `students_management`
5. Chọn tab `Import`, nạp file `students_management.sql`, bấm `Import`
6. Kiểm tra file `config/db.php`
7. Cấu hình mặc định hiện tại:
8. Host: `localhost`, User: `root`, Password: rỗng, Database: `students_management`
9. Truy cập project tại:
10. `http://localhost/TTS-DHHL-JVB-PhamBaoKhoa/students-management/`

## 15. URL chính
- `/students-management/`
- `/students-management/auth/login.php`
- `/students-management/auth/logout.php`
- `/students-management/interface/index.php`
- `/students-management/interface/listsv.php`
- `/students-management/interface/scores.php`
- `/students-management/interface/subjects.php`

## 16. Định dạng CSV
- Import sinh viên: `mssv, fullname, birthday, gender, phone, email, address`
- Export sinh viên: `mssv, fullname, birthday, gender, phone, email, class, address`
- Import điểm: `Ma mon, MSSV, Ho va ten, Lop, Chuyen can, Giua ky, Cuoi ky`
- nếu sinh viên không tồn tại hoặc không đúng lớp đang lọc thì dòng đó sẽ bị bỏ qua

## 17. Mô tả các màn hình
- Login: nhập email và password để vào hệ thống
- Dashboard: xem thống kê tổng quan theo khóa
- Sinh viên: tìm kiếm, lọc, thêm, sửa, xóa, import/export
- Môn học: xem danh sách môn, thêm mới, chỉnh sửa, xóa
- Điểm: chọn môn, chọn lớp, nhập điểm hàng loạt, import/export CSV

## 18. Điểm mạnh và hạn chế
- dễ chạy trong môi trường học tập, không cần framework nặng
- đã tách controller/model/view cho phần chính
- có import/export CSV cho sinh viên và điểm, có dữ liệu mẫu để test nhanh
- có dashboard trực quan để theo dõi kết quả
- hạn chế lớn nhất là mật khẩu còn lưu plain text
- một số file trong `function/scores/` vẫn còn procedural, tìm kiếm sinh viên vẫn lọc trong PHP
- dữ liệu SQL cũ còn chỗ lỗi encoding và project chưa có test tự động

## 19. Kết luận
Đây là một project quản lý sinh viên quy mô nhỏ, phù hợp cho bài tập môn học, demo nội bộ hoặc làm nền để học cách tổ chức code PHP theo kiểu MVC.
Tài liệu này được viết theo đúng môi trường bạn đang dùng là `XAMPP + phpMyAdmin`, không mô tả theo hướng MariaDB nữa.
