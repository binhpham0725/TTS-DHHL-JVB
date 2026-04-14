<?php

class SubjectController extends Controller
{
    private SubjectModel $subjects;

    public function __construct()
    {
        parent::__construct();
        // SubjectModel chịu trách nhiệm toàn bộ CRUD và validate cho bảng môn học.
        $this->subjects = new SubjectModel($this->db);
    }

    public function index(): void
    {
        Session::start();
        Auth::requireLogin();

        // Lấy toàn bộ môn học để render thành các card ở trang danh sách môn học.
        $this->render('subjects/index', [
            'subjects' => $this->subjects->all(),
            'teacherName' => Session::get('teacher_name', 'Chua dang nhap'),
        ]);
    }

    public function create(): void
    {
        Session::start();
        Auth::requireLogin();

        // Gom dữ liệu từ form về một mảng thống nhất để dễ validate và lưu xuống database.
        $data = [
            'subject_code' => trim($_POST['subject_code'] ?? ''),
            'subject_name' => trim($_POST['subject_name'] ?? ''),
            'credits' => (int)($_POST['credits'] ?? 3),
            'description' => trim($_POST['description'] ?? ''),
            'attendance_weight' => (int)($_POST['attendance_weight'] ?? 10),
            'midterm_weight' => (int)($_POST['midterm_weight'] ?? 30),
            'final_weight' => (int)($_POST['final_weight'] ?? 60),
        ];

        // Chỉ validate khi request là POST; còn GET thì mở form trống để nhập mới.
        $errors = $_SERVER['REQUEST_METHOD'] === 'POST' ? $this->subjects->validate($data) : [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
            // Nếu dữ liệu hợp lệ thì gọi model tạo môn học mới rồi quay về danh sách.
            if ($this->subjects->create($data)) {
                $this->redirect(app_url('interface/subjects.php?msg=add_success'));
            }

            $errors[] = 'Them mon hoc that bai.';
        }

        $this->render('subjects/form', [
            'title' => 'Them mon hoc',
            'subtitle' => 'Nhap thong tin mon hoc moi',
            'submitLabel' => 'Luu mon hoc',
            'backUrl' => app_url('interface/subjects.php'),
            'errors' => $errors,
            'subject' => $data,
        ]);
    }

    public function edit(int $id): void
    {
        Session::start();
        Auth::requireLogin();

        // Tải sẵn môn học theo id; nếu không tồn tại thì quay về trang danh sách.
        $subject = $this->subjects->find($id);
        if ($subject === null) {
            $this->redirect(app_url('interface/subjects.php'));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ghi đè lại dữ liệu bằng nội dung vừa submit để validate và hiển thị lại form nếu có lỗi.
            $subject = [
                'subject_code' => trim($_POST['subject_code'] ?? ''),
                'subject_name' => trim($_POST['subject_name'] ?? ''),
                'credits' => (int)($_POST['credits'] ?? 3),
                'description' => trim($_POST['description'] ?? ''),
                'attendance_weight' => (int)($_POST['attendance_weight'] ?? 10),
                'midterm_weight' => (int)($_POST['midterm_weight'] ?? 30),
                'final_weight' => (int)($_POST['final_weight'] ?? 60),
            ];

            // Validate có truyền thêm id hiện tại để bỏ qua chính bản ghi đang sửa khi kiểm tra trùng mã môn.
            $errors = $this->subjects->validate($subject, $id);
            if (empty($errors) && $this->subjects->update($id, $subject)) {
                $this->redirect(app_url('interface/subjects.php?msg=edit_success'));
            }

            if (empty($errors)) {
                $errors[] = 'Cap nhat that bai.';
            }
        } else {
            $errors = [];
        }

        $this->render('subjects/form', [
            'title' => 'Sua mon hoc',
            'subtitle' => 'Cap nhat thong tin mon hoc',
            'submitLabel' => 'Cap nhat',
            'backUrl' => app_url('interface/subjects.php'),
            'errors' => $errors,
            'subject' => $subject,
        ]);
    }

    public function delete(int $id): void
    {
        Session::start();
        Auth::requireLogin();

        // Chặn các id không hợp lệ trước khi thao tác xóa.
        if ($id <= 0) {
            $this->redirect(app_url('interface/subjects.php'));
        }

        // Nếu id không tồn tại trong DB thì trả người dùng về danh sách kèm thông báo.
        if ($this->subjects->find($id) === null) {
            $this->redirect(app_url('interface/subjects.php?msg=not_found'));
        }

        // Xóa môn học; các bản ghi điểm liên quan sẽ đi theo ràng buộc khóa ngoại trong SQL.
        $this->subjects->delete($id);
        $this->redirect(app_url('interface/subjects.php?msg=del_success'));
    }
}
