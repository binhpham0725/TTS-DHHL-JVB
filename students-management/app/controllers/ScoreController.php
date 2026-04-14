<?php

class ScoreController extends Controller
{
    private ScoreModel $scores;
    private SubjectModel $subjects;

    public function __construct()
    {
        parent::__construct();
        $this->scores = new ScoreModel($this->db);
        $this->subjects = new SubjectModel($this->db);
    }

    public function index(): void
    {
        Session::start();
        Auth::requireLogin();

        $selectedSubject = (int)($_GET['subject_id'] ?? 0);
        $selectedClass = trim($_GET['class'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $subjects = $this->subjects->options();
        $classes = $this->scores->getAllClasses();
        $subjectInfo = $selectedSubject > 0 ? $this->subjects->find($selectedSubject) : null;
        $scoreRows = $selectedSubject > 0 ? $this->scores->getScoreRows($selectedSubject, $selectedClass) : [];
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
            'teacherName' => Session::get('teacher_name', 'Chua dang nhap'),
        ]);
    }

    public function save(): void
    {
        Session::start();
        Auth::requireLogin();

        $subjectId = (int)($_POST['subject_id'] ?? 0);
        $class = trim($_POST['class'] ?? '');
        $scoreData = $_POST['scores'] ?? [];

        if ($subjectId <= 0) {
            $this->redirect(app_url('interface/scores.php?msg=error_subject'));
        }

        if (!$this->scores->saveBatch($subjectId, $scoreData)) {
            $this->redirect(app_url('interface/scores.php?msg=error_subject'));
        }

        $this->redirect(app_url('interface/scores.php?subject_id=' . $subjectId . '&class=' . urlencode($class) . '&msg=saved'));
    }

    public function delete(int $id): void
    {
        Session::start();
        Auth::requireLogin();

        $subjectId = (int)($_GET['subject_id'] ?? 0);
        $class = trim($_GET['class'] ?? '');

        if ($id > 0) {
            $this->scores->delete($id);
        }

        $this->redirect(app_url('interface/scores.php?subject_id=' . $subjectId . '&class=' . urlencode($class) . '&msg=del_success'));
    }

    public function averageStats(): void
    {
        $class = trim($_GET['class'] ?? 'all');
        $this->json($this->scores->dashboardAverage($class));
    }

    public function rankingStats(): void
    {
        $class = trim($_GET['class'] ?? 'all');
        $this->json($this->scores->rankingStats($class));
    }

    public function resultStats(): void
    {
        $class = trim($_GET['class'] ?? 'all');
        $this->json($this->scores->resultStats($class));
    }
}
