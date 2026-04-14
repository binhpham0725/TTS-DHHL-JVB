# 🏘️ Hệ thống Quản lý Ký túc xá - TTS_JVB

Hệ thống quản lý ký túc xá xây dựng trên custom MVC framework PHP thuần, không dùng framework bên ngoài.

---

## 📁 Cấu trúc dự án

```
mvc/
├── index.php                        # Entry point, khởi tạo session và App
├── bootstrap.php                    # Load config, core classes, định nghĩa constants
├── .htaccess                        # Rewrite URL về index.php
│
├── app/
│   ├── app.php                      # Class App - điều phối URL → Controller → Action
│   ├── controllers/
│   │   ├── Auth.php                 # Đăng nhập / Đăng xuất
│   │   ├── Home.php                 # Trang chủ client
│   │   ├── Product.php              # (Demo)
│   │   └── admin/
│   │       ├── Dashboard.php        # Trang tổng quan admin
│   │       ├── Room.php             # Quản lý phòng
│   │       ├── RoomAssignment.php   # Quản lý yêu cầu đăng ký phòng
│   │       ├── RoomType.php         # Quản lý loại phòng
│   │       ├── Student.php          # Quản lý sinh viên
│   │       └── User.php             # (Placeholder)
│   ├── models/
│   │   ├── AuthModel.php            # Xác thực tài khoản admin/sinh viên
│   │   ├── HomeModel.php
│   │   ├── RoomAssignmentModal.php  # CRUD yêu cầu đăng ký phòng
│   │   ├── RoomModel.php            # CRUD phòng
│   │   ├── RoomTypeModel.php        # Lấy danh sách loại phòng
│   │   └── StudentModel.php         # CRUD sinh viên
│   ├── views/
│   │   ├── admin/
│   │   │   ├── dashboard.php
│   │   │   ├── room.php
│   │   │   ├── roomassignment.php   # Giao diện quản lý yêu cầu đăng ký phòng
│   │   │   ├── roomtype.php
│   │   │   └── student.php
│   │   ├── auth/
│   │   │   └── login.php
│   │   ├── blocks/
│   │   │   ├── admin_navbar.php
│   │   │   ├── admin_sidebar.php
│   │   │   ├── header.php
│   │   │   └── footer.php
│   │   └── layouts/
│   │       ├── admin_layout.php     # Layout admin (nhúng content + js_file động)
│   │       └── client_layout.php
│   └── errors/
│       └── 404.php
│
├── configs/
│   ├── app.php                      # Cấu hình ứng dụng (hiện để trống)
│   ├── database.php                 # Thông tin kết nối MySQL
│   └── routes.php                   # Định nghĩa route alias
│
├── core/
│   ├── Connection.php               # Singleton PDO connection
│   ├── Database.php                 # Wrapper PDO (fetchAll, fetchOne, insert, update, delete)
│   ├── Model.php                    # Base Model - inject Database
│   ├── Controller.php               # Base Controller - renderView, model loader, auth check
│   └── Route.php                    # URL rewriting theo $routes config
│
└── public/assets/
    ├── admin/
    │   ├── css/  (main.css, theme.css, reponsive.css)
    │   └── js/
    │       ├── main.js
    │       ├── room.js              # JS quản lý phòng
    │       ├── room_assignment.js   # JS quản lý yêu cầu đăng ký phòng
    │       ├── toast.js             # Thông báo toast
    │       └── validator.js         # Validate form phía client
    ├── auth/
    └── client/
```

---

## 🚀 Cài đặt

### 1. Yêu cầu

- PHP >= 7.4
- MySQL / MariaDB
- Apache với `mod_rewrite` bật (`AllowOverride All`)

### 2. Database

```sql
CREATE DATABASE quanly_kytucxa;
```

Sau đó import file SQL (nếu có) hoặc tạo các bảng theo schema bên dưới.

### 3. Cấu hình

Sửa `configs/database.php`:

```php
$config['database'] = [
    'host'     => 'localhost',
    'user'     => 'root',
    'password' => '',
    'dbname'   => 'quanly_kytucxa',
];
```

### 4. Truy cập

```
http://localhost/TTS_JVB/mvc/
http://localhost/TTS_JVB/mvc/auth/login
http://localhost/TTS_JVB/mvc/admin/student
```

---

## 🗄️ Schema Database

### Bảng `admins`
| Cột       | Kiểu         | Ghi chú          |
|-----------|--------------|------------------|
| id        | INT PK AI    |                  |
| email     | VARCHAR      | Dùng để đăng nhập|
| password  | VARCHAR      | MD5              |

### Bảng `students`
| Cột      | Kiểu    | Ghi chú           |
|----------|---------|-------------------|
| id       | INT PK  |                   |
| mssv     | VARCHAR | Mã số sinh viên   |
| name     | VARCHAR |                   |
| gender   | TINYINT | 0: Nữ, 1: Nam     |
| birthday | DATE    |                   |
| cccd     | VARCHAR | Căn cước công dân |
| email    | VARCHAR |                   |
| phone    | VARCHAR |                   |
| address  | TEXT    |                   |
| password | VARCHAR | MD5               |

### Bảng `room_types`
| Cột        | Kiểu    | Ghi chú         |
|------------|---------|-----------------|
| id         | INT PK  |                 |
| type_name  | VARCHAR | Tên loại phòng  |
| max_people | INT     | Sức chứa tối đa |
| price      | DECIMAL | Giá phòng       |

### Bảng `rooms`
| Cột            | Kiểu    | Ghi chú                    |
|----------------|---------|----------------------------|
| id             | INT PK  |                            |
| room_name      | VARCHAR | Tên/số phòng               |
| room_type_id   | INT FK  | → room_types.id            |
| gender         | TINYINT | 0: Nữ, 1: Nam              |
| current_number | INT     | Số người đang ở            |
| status         | TINYINT | Trạng thái phòng           |

### Bảng `room_assignment`
| Cột          | Kiểu     | Ghi chú                              |
|--------------|----------|--------------------------------------|
| id           | INT PK   |                                      |
| student_id   | INT FK   | → students.id                        |
| room_type_id | INT FK   | → room_types.id                      |
| room_id      | INT FK   | → rooms.id (null khi chưa xếp phòng)|
| status       | TINYINT  | 0: Chưa duyệt, 1: Đã duyệt, 2: Từ chối |
| check_in     | DATE     | Ngày vào ở                           |
| check_out    | DATE     | Ngày ra                              |
| created_date | DATETIME | Ngày nộp đơn                         |
| note         | TEXT     | Ghi chú / lý do từ chối             |

---

## 🔗 Routes & API

### Client
| Method | URL              | Controller@Action  | Mô tả       |
|--------|------------------|--------------------|-------------|
| GET    | `/`              | `Home@index`       | Trang chủ   |
| GET    | `/auth/login`    | `Auth@login`       | Form đăng nhập |
| POST   | `/auth/loginApi` | `Auth@loginApi`    | API đăng nhập (JSON) |
| GET    | `/auth/logout`   | `Auth@logout`      | Đăng xuất   |

### Admin - Sinh viên
| Method | URL                          | Controller@Action         |
|--------|------------------------------|---------------------------|
| GET    | `/admin/student`             | `Student@index`           |
| GET    | `/admin/student/getAllstudents` | `Student@getAllstudents` |
| GET    | `/admin/student/getStudentById/{id}` | `Student@getStudentById` |
| POST   | `/admin/student/addStudent`  | `Student@addStudent`      |
| POST   | `/admin/student/update`      | `Student@update`          |
| GET    | `/admin/student/delete/{id}` | `Student@delete`          |

### Admin - Phòng
| Method | URL                              | Controller@Action       |
|--------|----------------------------------|-------------------------|
| GET    | `/admin/room`                    | `Room@index`            |
| GET    | `/admin/room/getRooms`           | `Room@getRooms`         |
| POST   | `/admin/room/add`                | `Room@add`              |
| GET    | `/admin/room/details/{id}`       | `Room@details`          |
| POST   | `/admin/room/update`             | `Room@update`           |
| GET    | `/admin/room/delete/{id}`        | `Room@delete`           |

### Admin - Yêu cầu đăng ký phòng
| Method | URL                                                    | Controller@Action                  |
|--------|--------------------------------------------------------|------------------------------------|
| GET    | `/admin/roomassignment`                                | `RoomAssignment@index`             |
| POST   | `/admin/roomassignment/getListData`                    | `RoomAssignment@getListData`       |
| GET    | `/admin/roomassignment/detail/{id}`                    | `RoomAssignment@detail`            |
| GET    | `/admin/roomassignment/getAvailableRooms/{type}/{gender}` | `RoomAssignment@getAvailableRooms` |
| POST   | `/admin/roomassignment/approve`                        | `RoomAssignment@approve`           |
| POST   | `/admin/roomassignment/reject`                         | `RoomAssignment@reject`            |

### Admin - Loại phòng
| Method | URL                    | Controller@Action  |
|--------|------------------------|--------------------|
| GET    | `/admin/roomtype/list` | `RoomType@list`    |

---

## 🏗️ Kiến trúc

### Luồng xử lý request

```
index.php
  └── bootstrap.php        (load config, core)
        └── App::__construct()
              └── handleUrl()
                    ├── Route::handelRoute()   (áp dụng alias từ routes.php)
                    ├── Tìm file controller theo URL segments
                    ├── Khởi tạo Controller
                    └── Gọi Action với params
```

### Core Classes

**Connection** (`core/Connection.php`)
- Singleton pattern, tạo PDO connection một lần duy nhất.

**Database** (`core/Database.php`)
- Wrapper PDO với các method: `fetchAll`, `fetchOne`, `fetch`, `insert`, `update`, `delete`, `getCount`.
- Dùng prepared statements, tránh SQL injection.

**Model** (`core/Model.php`)
- Base class, inject `Database` vào `$this->db`.

**Controller** (`core/Controller.php`)
- `model($name)`: load và khởi tạo model theo tên class.
- `renderView($view, $data)`: render view với layout, extract data thành biến.
- `renderPlainView($view, $data)`: render view không layout.
- `isLogin()`: kiểm tra session.

**Route** (`core/Route.php`)
- Dùng regex để map URL alias sang controller path (ví dụ: `san-pham` → `product`).

### Constants

| Constant       | Giá trị                        |
|----------------|--------------------------------|
| `__DIR_ROOT__` | Đường dẫn tuyệt đối thư mục `mvc/` |
| `__WEB_ROOT__` | URL gốc của ứng dụng           |

---

## 🔐 Xác thực

- Đăng nhập qua `POST /auth/loginApi` với JSON `{ email, password, role }`.
- Password hash bằng MD5.
- Session lưu `$_SESSION['user']` và `$_SESSION['role']` (`admin` hoặc `student`).
- Sau đăng nhập admin redirect về `/admin/student`, student về `/`.

---

## 📋 Tính năng

| Module                  | Chức năng                                                    |
|-------------------------|--------------------------------------------------------------|
| Xác thực                | Đăng nhập/đăng xuất cho admin và sinh viên                  |
| Quản lý sinh viên       | Xem, thêm, sửa, xóa, tìm kiếm sinh viên                    |
| Quản lý phòng           | Xem, thêm, sửa, xóa phòng; kiểm tra tên phòng trùng        |
| Quản lý loại phòng      | Xem danh sách loại phòng                                     |
| Yêu cầu đăng ký phòng  | Xem danh sách, lọc theo trạng thái/thời gian/từ khóa, phân trang, duyệt xếp phòng, từ chối kèm lý do |

---

## 🛠️ Thêm tính năng mới

### 1. Thêm route alias (tùy chọn)

`configs/routes.php`:
```php
$routes['tin-tuc'] = 'news';
```

### 2. Tạo Controller

`app/controllers/News.php`:
```php
use core\Controller;
class News extends Controller {
    public function index() {
        $this->renderView('news/list', ['title' => 'Tin tức']);
    }
}
```

### 3. Tạo Model

`app/models/NewsModel.php`:
```php
class NewsModel extends Model {
    public function getAll() {
        return $this->db->fetchAll("SELECT * FROM news");
    }
}
```

### 4. Tạo View

`app/views/news/list.php`:
```php
<h1><?= $title ?></h1>
```

---

## ⚠️ Lưu ý

- Bật `mod_rewrite` và `AllowOverride All` trong Apache config.
- Restart Apache sau khi thay đổi `.htaccess`.
- PHP >= 7.4 (dùng typed properties, null coalescing).
- Tất cả API admin trả về JSON, frontend dùng `fetch()` + jQuery.
- File JS được load động qua biến `$data['js_file']` trong admin layout.

---

**Version:** 1.0  
**Database:** `quanly_kytucxa`  
**Stack:** PHP (MVC thuần) + MySQL + jQuery + Font Awesome
