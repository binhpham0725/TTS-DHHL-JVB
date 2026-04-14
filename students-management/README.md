# Students Management

Ứng dụng quản lý sinh viên viết bằng PHP thuần + MySQL, tổ chức theo MVC.

## Chức năng chính

- Đăng nhập giảng viên
- Dashboard thống kê
- Quản lý sinh viên
- Quản lý môn học
- Quản lý điểm
- Import/Export CSV

## Công nghệ

- PHP 8.x
- MySQL / MariaDB
- XAMPP
- HTML, CSS, JavaScript
- Bootstrap
- Chart.js

## Cấu trúc chính

```text
students-management/
|-- app/
|   |-- core/
|   |-- controllers/
|   |-- models/
|   `-- views/
|-- assets/
|-- auth/
|-- config/
|-- function/
|-- helpers/
|-- interface/
|-- reports/
|-- index.php
`-- README.md
```

## Ghi chú cấu trúc

- `app/models`: xử lý dữ liệu và truy vấn DB
- `app/controllers`: xử lý request và điều phối nghiệp vụ
- `app/views`: giao diện
- `app/core`: base controller, auth, session
- `interface`, `auth`, `function`, `reports`: route/adapter để giữ URL cũ

## Cấu hình database

File cấu hình: [config/db.php](C:\xampp\htdocs\TTS-JVB\students-management\config\db.php:1)

Mặc định:

```php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "students_management";
```

## Bảng dữ liệu cần có

- `Teacher`
- `students`
- `subject`
- `scores`

## Cách chạy

1. Đặt project vào `C:\xampp\htdocs\students-management`
2. Bật `Apache` và `MySQL` trong XAMPP
3. Tạo database `students_management`
4. Import dữ liệu cần thiết
5. Truy cập:

```text
http://localhost/students-management/
```

Nếu máy dùng cổng khác:

```text
http://localhost:3000/students-management/
```

## URL chính

- `/students-management/`
- `/students-management/auth/login.php`
- `/students-management/interface/index.php`
- `/students-management/interface/listsv.php`
- `/students-management/interface/scores.php`
- `/students-management/interface/subjects.php`

## Lưu ý

- Không mở trực tiếp file trong `app/views/...`
- Nên truy cập qua `index.php`, `auth/`, `interface/`
- Nếu lỗi đăng nhập, kiểm tra bảng `Teacher`, session PHP và cấu hình DB

## Kiểm tra syntax PHP

```powershell
Get-ChildItem -Recurse -Filter *.php | ForEach-Object { php -l $_.FullName }
```
