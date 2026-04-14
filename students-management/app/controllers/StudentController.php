<?php

class StudentController extends Controller
{
    private StudentModel $students;

    public function __construct()
    {
        parent::__construct();
        $this->students = new StudentModel($this->db);
    }

    public function index(): void
    {
        Session::start();
        Auth::requireLogin();

        $search = trim($_GET['search'] ?? '');
        $class = trim($_GET['class'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $allowedCourses = $this->students->getAllowedCourses();
        $students = $this->students->filtered($search, $class);
        $totalStudents = count($students);
        $totalPages = max(1, (int)ceil($totalStudents / $perPage));
        $page = min($page, $totalPages);
        $start = ($page - 1) * $perPage;
        $currentStudents = array_slice($students, $start, $perPage);
        $exportQuery = buildQuery([]);

        $this->render('students/index', [
            'search' => $search,
            'class' => $class,
            'page' => $page,
            'perPage' => $perPage,
            'allowedCourses' => $allowedCourses,
            'currentStudents' => $currentStudents,
            'totalStudents' => $totalStudents,
            'totalPages' => $totalPages,
            'start' => $start,
            'teacherName' => Session::get('teacher_name', 'Chua dang nhap'),
            'exportUrl' => app_url('function/students/export.php') . ($exportQuery ? '?' . $exportQuery : ''),
            'addError' => Session::flash('student_add_error', ''),
            'addOld' => Session::flash('student_add_old', []),
        ]);
    }

    public function create(): void
    {
        Session::start();
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->render('students/partials/add_modal', [
                'error' => '',
                'old' => [],
            ]);
            return;
        }

        $data = [
            'mssv' => trim($_POST['mssv'] ?? ''),
            'fullname' => trim($_POST['fullname'] ?? ''),
            'birthday' => trim($_POST['birthday'] ?? ''),
            'gender' => trim($_POST['gender'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
        ];

        $validation = $this->students->validate($data, false);
        if ($validation['error'] !== null) {
            Session::set('student_add_error', $validation['error']);
            Session::set('student_add_old', $data);
            $this->redirect(app_url('interface/listsv.php'));
        }

        $data['class'] = $validation['class'];

        if (!$this->students->create($data)) {
            Session::set('student_add_error', 'Them sinh vien that bai.');
            Session::set('student_add_old', $data);
        }

        $this->redirect(app_url('interface/listsv.php'));
    }

    public function editForm(int $id): void
    {
        Session::start();
        Auth::requireLogin();

        $student = $this->students->find($id);
        if ($student === null) {
            echo '<div class="modal-card"><p style="padding:20px">Khong tim thay sinh vien.</p></div>';
            return;
        }

        $this->render('students/partials/edit_modal', [
            'student' => $student,
            'allowedCourses' => $this->students->getAllowedCourses(),
        ]);
    }

    public function update(): void
    {
        Session::start();
        Auth::requireLogin();

        $id = (int)($_POST['id'] ?? 0);
        $data = [
            'mssv' => trim($_POST['mssv'] ?? ''),
            'fullname' => trim($_POST['fullname'] ?? ''),
            'birthday' => trim($_POST['birthday'] ?? ''),
            'gender' => trim($_POST['gender'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'class' => trim($_POST['class'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
        ];

        if ($id <= 0) {
            $this->json(['success' => false, 'message' => 'Sinh vien khong hop le.'], 422);
        }

        $validation = $this->students->validate($data, true, $id);
        if ($validation['error'] !== null) {
            $this->json(['success' => false, 'message' => $validation['error']], 422);
        }

        if (!$this->students->update($id, $data)) {
            $this->json(['success' => false, 'message' => 'Cap nhat that bai.'], 500);
        }

        $this->json(['success' => true]);
    }

    public function delete(int $id): void
    {
        Session::start();
        Auth::requireLogin();
        if ($id > 0) {
            $this->students->delete($id);
        }
        $this->redirect(app_url('interface/listsv.php'));
    }

    public function import(): void
    {
        Session::start();
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['csv_file'])) {
            $this->redirect(app_url('interface/listsv.php'));
        }

        if ($_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            $this->redirect(app_url('interface/listsv.php?msg=error_file'));
        }

        $result = $this->students->importCsv($_FILES['csv_file']['tmp_name']);
        if ($result['error'] !== null) {
            $this->redirect(app_url('interface/listsv.php?msg=' . urlencode($result['error'])));
        }

        $this->redirect(app_url('interface/listsv.php?msg=import_success&imported=' . $result['imported'] . '&skipped=' . $result['skipped']));
    }

    public function export(): void
    {
        Session::start();
        Auth::requireLogin();

        $search = trim($_GET['search'] ?? '');
        $class = trim($_GET['class'] ?? '');
        $students = $this->students->filtered($search, $class);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=students_export.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['mssv', 'fullname', 'birthday', 'gender', 'phone', 'email', 'class', 'address']);
        foreach ($students as $student) {
            fputcsv($output, [
                $student['mssv'],
                $student['fullname'],
                $student['birthday'],
                $student['gender'],
                $student['phone'],
                $student['email'],
                $student['class'],
                $student['address'],
            ]);
        }
        fclose($output);
        exit;
    }
}
