<?php

class SubjectController extends Controller
{
    private SubjectModel $subjects;

    public function __construct()
    {
        parent::__construct();
        $this->subjects = new SubjectModel($this->db);
    }

    public function index(): void
    {
        Session::start();
        Auth::requireLogin();

        $this->render('subjects/index', [
            'subjects' => $this->subjects->all(),
            'teacherName' => Session::get('teacher_name', 'Chua dang nhap'),
        ]);
    }

    public function create(): void
    {
        Session::start();
        Auth::requireLogin();

        $data = [
            'subject_code' => trim($_POST['subject_code'] ?? ''),
            'subject_name' => trim($_POST['subject_name'] ?? ''),
            'credits' => (int)($_POST['credits'] ?? 3),
            'description' => trim($_POST['description'] ?? ''),
            'attendance_weight' => (int)($_POST['attendance_weight'] ?? 10),
            'midterm_weight' => (int)($_POST['midterm_weight'] ?? 30),
            'final_weight' => (int)($_POST['final_weight'] ?? 60),
        ];

        $errors = $_SERVER['REQUEST_METHOD'] === 'POST' ? $this->subjects->validate($data) : [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
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

        $subject = $this->subjects->find($id);
        if ($subject === null) {
            $this->redirect(app_url('interface/subjects.php'));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subject = [
                'subject_code' => trim($_POST['subject_code'] ?? ''),
                'subject_name' => trim($_POST['subject_name'] ?? ''),
                'credits' => (int)($_POST['credits'] ?? 3),
                'description' => trim($_POST['description'] ?? ''),
                'attendance_weight' => (int)($_POST['attendance_weight'] ?? 10),
                'midterm_weight' => (int)($_POST['midterm_weight'] ?? 30),
                'final_weight' => (int)($_POST['final_weight'] ?? 60),
            ];

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

        if ($id <= 0) {
            $this->redirect(app_url('interface/subjects.php'));
        }

        if ($this->subjects->find($id) === null) {
            $this->redirect(app_url('interface/subjects.php?msg=not_found'));
        }

        $this->subjects->delete($id);
        $this->redirect(app_url('interface/subjects.php?msg=del_success'));
    }
}
