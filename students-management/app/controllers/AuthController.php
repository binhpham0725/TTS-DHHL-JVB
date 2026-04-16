<?php

class AuthController extends Controller
{
    private TeacherModel $teachers;
    private const HLUV_EMAIL_PATTERN = '/^[A-Za-z0-9._%+-]+@(?:[A-Za-z0-9-]+\.)*hluv\.edu\.com\.vn$/i';

    public function __construct()
    {
        parent::__construct();
        $this->teachers = new TeacherModel($this->db);
    }

    public function showLogin(): void
    {
        Session::start();

        if (Auth::check()) {
            $this->redirect(app_url('interface/index.php'));
        }

        // Dùng flash để giữ lại lỗi và email vừa nhập sau một lần redirect.
        $this->render('auth/login', [
            'error' => Session::flash('error', ''),
            'fieldErrors' => Session::flash('auth_login_errors', []),
            'oldEmail' => Session::flash('old_email', ''),
        ]);
    }

    public function login(): void
    {
        Session::start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(app_url('auth/login.php'));
        }

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $fieldErrors = [];

        Session::set('old_email', $email);

        if ($email === '') {
            $fieldErrors['email'] = app_text('auth.errors.email_required');
        } elseif (!preg_match(self::HLUV_EMAIL_PATTERN, $email)) {
            $fieldErrors['email'] = app_text('auth.errors.email_invalid');
        }

        if ($password === '') {
            $fieldErrors['password'] = app_text('auth.errors.password_required');
        } elseif (mb_strlen($password) < 8) {
            $fieldErrors['password'] = app_text('auth.errors.password_min');
        }

        if ($fieldErrors !== []) {
            Session::set('auth_login_errors', $fieldErrors);
            $this->redirect(app_url('auth/login.php'));
        }

        try {
            $teacher = $this->teachers->findByEmail($email);
            if ($teacher && $this->teachers->verifyPassword($teacher, $password)) {
                Auth::login($teacher);
                $this->redirect(app_url('interface/index.php'));
            }
        } catch (Throwable $exception) {
            $this->reportException($exception);
            Session::set('auth_login_errors', [
                'password' => app_text('auth.errors.login_unavailable'),
            ]);
            $this->redirect(app_url('auth/login.php'));
        }

        Session::set('auth_login_errors', [
            'password' => app_text('auth.errors.login_failed'),
        ]);
        $this->redirect(app_url('auth/login.php'));
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect(app_url('auth/login.php'));
    }
}
