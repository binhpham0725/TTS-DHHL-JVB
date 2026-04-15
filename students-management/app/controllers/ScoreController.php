<?php

class ScoreController extends Controller
{
    private ScoreModel $scores;
    private SubjectModel $subjects;

    public function __construct()
    {
        parent::__construct();
        // ScoreModel xử lý bảng điểm, SubjectModel dùng để lấy thông tin môn học và danh sách lựa chọn.
        $this->scores = new ScoreModel($this->db);
        $this->subjects = new SubjectModel($this->db);
    }

    public function index(): void
    {
        Session::start();
        Auth::requireLogin();

        // Đọc bộ lọc hiện tại từ query string: môn học, lớp và trang đang xem.
        $selectedSubject = (int)($_GET['subject_id'] ?? 0);
        $selectedClass = trim($_GET['class'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;

        // Nạp dữ liệu phục vụ form lọc và bảng điểm.
        $subjects = $this->subjects->options();
        $classes = $this->scores->getAllClasses();
        $subjectInfo = $selectedSubject > 0 ? $this->subjects->find($selectedSubject) : null;
        $scoreRows = $selectedSubject > 0 ? $this->scores->getScoreRows($selectedSubject, $selectedClass) : [];

        // Phân trang ở tầng controller sau khi đã lấy danh sách sinh viên/điểm theo môn và lớp.
        $totalStudents = count($scoreRows);
        $totalPages = max(1, (int)ceil($totalStudents / $perPage));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $perPage;
        $scoreRows = array_slice($scoreRows, $offset, $perPage);

        $this->render('scores/index', [
            'selectedSubject' => $selectedSubject,
            'selectedClass' => $selectedClass,
            'page' => $page,
            'perPage' => $perPage,
            'totalStudents' => $totalStudents,
            'totalPages' => $totalPages,
            'offset' => $offset,
            'subjects' => $subjects,
            'classes' => $classes,
            'subjectInfo' => $subjectInfo,
            'scoreRows' => $scoreRows,
            'teacherName' => Session::get('teacher_name', app_text('common.not_logged_in')),
        ]);
    }

    public function save(): void
    {
        Session::start();
        Auth::requireLogin();

        // scores là mảng lồng nhau theo cấu trúc scores[student_id][attendance_score|midterm_score|final_score].
        $subjectId = (int)($_POST['subject_id'] ?? 0);
        $class = trim($_POST['class'] ?? '');
        $scoreData = $_POST['scores'] ?? [];

        try {
            // Nếu chưa chọn môn học thì không thể lưu vì không xác định được trọng số và đích cập nhật.
            if ($subjectId <= 0) {
                $this->redirect(app_url('interface/scores.php?msg=error_subject'));
            }

            // Model sẽ tự tính lại điểm tổng kết và quyết định UPDATE hay INSERT cho từng sinh viên.
            if (!$this->scores->saveBatch($subjectId, $scoreData)) {
                $this->redirect(app_url('interface/scores.php?msg=error_subject'));
            }
        } catch (Throwable $exception) {
            $this->reportException($exception);
            $this->redirect(app_url('interface/scores.php?subject_id=' . $subjectId . '&class=' . urlencode($class) . '&msg=error_save'));
        }

        $this->redirect(app_url('interface/scores.php?subject_id=' . $subjectId . '&class=' . urlencode($class) . '&msg=saved'));
    }

    public function delete(int $id): void
    {
        Session::start();
        Auth::requireLogin();

        // Giữ lại subject_id và class để quay về đúng màn hình người dùng đang thao tác.
        $subjectId = (int)($_GET['subject_id'] ?? 0);
        $class = trim($_GET['class'] ?? '');

        try {
            if ($id > 0) {
                $this->scores->delete($id);
            }
        } catch (Throwable $exception) {
            $this->reportException($exception);
            $this->redirect(app_url('interface/scores.php?subject_id=' . $subjectId . '&class=' . urlencode($class) . '&msg=error_delete'));
        }

        $this->redirect(app_url('interface/scores.php?subject_id=' . $subjectId . '&class=' . urlencode($class) . '&msg=del_success'));
    }

    public function averageStats(): void
    {
        // Endpoint JSON cho card thống kê tổng quan ngoài dashboard.
        $class = trim($_GET['class'] ?? 'all');
        $this->json($this->scores->dashboardAverage($class));
    }

    public function rankingStats(): void
    {
        // Endpoint JSON cho biểu đồ phân bố xếp loại học lực.
        $class = trim($_GET['class'] ?? 'all');
        $this->json($this->scores->rankingStats($class));
    }

    public function resultStats(): void
    {
        // Endpoint JSON cho dữ liệu tổng hợp kết quả theo môn và lớp.
        $class = trim($_GET['class'] ?? 'all');
        $this->json($this->scores->resultStats($class));
    }
}
