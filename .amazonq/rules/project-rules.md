# TTS_JVB MVC – Project Rules

## Stack & Môi trường
- PHP >= 7.4, MySQL/MariaDB, Apache (mod_rewrite)
- Custom MVC thuần, **không dùng framework bên ngoài**
- Database: `quanly_kytucxa`
- Entry point: `index.php` → `bootstrap.php` → `App`

---

## Cấu trúc thư mục

```
mvc/
├── app/controllers/          # Controller (admin/ cho admin)
├── app/models/               # Model
├── app/views/                # View (layouts/, blocks/, admin/, auth/)
├── core/                     # Framework core (KHÔNG sửa trừ khi cần thiết)
├── configs/                  # database.php, routes.php, app.php
└── public/assets/            # CSS/JS tĩnh (admin/, auth/, client/)
```

---

## Quy tắc Controller

- Kế thừa `core\Controller` (namespace `core`)
- Đặt tại `app/controllers/` hoặc `app/controllers/admin/` (admin)
- Tên file = tên class, viết hoa chữ đầu: `Student.php` → `class Student`
- Khởi tạo model trong `__construct` qua `$this->model("ModelName")`
- Lưu dữ liệu truyền view vào `$this->data = []`
- Render view qua:
  - `$this->renderView('layouts/admin_layout', $this->data)` — có layout
  - `$this->renderPlainView('auth/login')` — không layout
- Truyền `content` và `js_file` (tùy chọn) vào `$this->data` khi dùng admin layout:
  ```php
  $this->data['content'] = 'admin/room';
  $this->data['js_file']  = 'room.js'; // nếu có JS riêng
  ```
- API action phải set header trước khi echo:
  ```php
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode([...]);
  ```
- Đọc JSON body từ POST: `json_decode(file_get_contents('php://input'))`
- Validate input tối thiểu trước khi xử lý (kiểm tra `isset`)

---

## Quy tắc Model

- Kế thừa `Model` (không namespace), đặt tại `app/models/`
- Tên file = tên class + `Model`: `StudentModel.php` → `class StudentModel`
- Truy cập DB qua `$this->db` (instance của `Database`)
- Dùng **prepared statements** cho mọi query có tham số:
  ```php
  $this->db->fetchAll($sql, [':id' => $id]);
  ```
- Các method DB có sẵn: `fetchAll`, `fetchOne`, `fetch`, `insert`, `update`, `delete`, `getCount`
- Khai báo tên bảng là `protected $table` (hoặc tên mô tả nếu dùng nhiều bảng)
- Password luôn hash bằng `md5()` trước khi lưu/so sánh

---

## Quy tắc View

- View đặt tại `app/views/<module>/<name>.php`
- Admin view dùng layout `layouts/admin_layout.php` — layout tự nhúng navbar, sidebar, footer, jQuery, toast.js, validator.js, main.js
- JS riêng của từng trang được load động qua `$data['js_file']` (chỉ tên file, không path)
- Dùng `__WEB_ROOT__` cho mọi URL asset: `<?= __WEB_ROOT__ ?>/public/assets/...`
- Dùng `__DIR_ROOT__` cho đường dẫn file hệ thống

---

## Quy tắc Route

- URL mapping: `/{controller}/{action}/{params...}`
- Admin: `/{admin}/{controller}/{action}/{params...}` → file `app/controllers/admin/Controller.php`
- Alias route định nghĩa trong `configs/routes.php`:
  ```php
  $routes['san-pham'] = 'product';
  ```
- Default controller: `$routes['default_controller'] = 'Home'`

---

## Quy tắc API Response

Mọi API JSON phải trả về cấu trúc nhất quán:

```json
{ "status": "success", "message": "..." }
{ "status": "error",   "message": "..." }
```

Với dữ liệu: thêm key `data` hoặc tên mô tả:
```json
{ "status": "success", "data": [...], "total_page": 10 }
```

---

## Quy tắc Xác thực (Auth)

- Session: `$_SESSION['user']`, `$_SESSION['role']` (`admin` | `student`)
- Kiểm tra đăng nhập: `$this->isLogin()`
- Sau login: admin → `/admin/student`, student → `/`
- Logout: `session_unset()` + `session_destroy()`

---

## Quy tắc Database Schema

| Bảng              | Ghi chú                                      |
|-------------------|----------------------------------------------|
| `admins`          | id, email, password (md5)                    |
| `students`        | id, mssv, name, gender(0/1), birthday, cccd, email, phone, address, password (md5) |
| `room_types`      | id, type_name, max_people, price             |
| `rooms`           | id, room_name, room_type_id, gender(0/1), current_number, status |
| `room_assignment` | id, student_id, room_type_id, room_id(null), status(0/1/2), check_in, check_out, created_date, note |

- `gender`: 0 = Nữ, 1 = Nam
- `room_assignment.status`: 0 = Chưa duyệt, 1 = Đã duyệt, 2 = Từ chối

---

## Thêm tính năng mới (checklist)

1. (Tùy chọn) Thêm alias vào `configs/routes.php`
2. Tạo `app/controllers/[admin/]FeatureName.php` extends `Controller`
3. Tạo `app/models/FeatureNameModel.php` extends `Model`
4. Tạo `app/views/[admin/]feature_name.php`
5. (Tùy chọn) Tạo `public/assets/admin/js/feature_name.js` và truyền vào `$data['js_file']`

---

## Lưu ý quan trọng

- **Không dùng namespace** cho Controller và Model trong `app/` (chỉ `use core\Controller` để IDE nhận diện, nhưng class không có namespace)
- `bootstrap.php` tự động load toàn bộ file trong `configs/`
- `core/` load theo thứ tự: Route → App → Connection → Database → Model → Controller
- Dùng `exit()` sau `echo json_encode()` trong các action API để tránh output thừa
- Tránh dùng `die()` trong production — chỉ dùng trong core khi kết nối DB thất bại
