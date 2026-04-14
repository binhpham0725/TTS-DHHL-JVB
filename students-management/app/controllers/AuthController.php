<?php

class AuthController extends Controller
{
    private TeacherModel $teachers;

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

        $this->render('auth/login', [
            'error' => Session::flash('error', ''),
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

        Session::set('old_email', $email);

        if ($email === '' || $password === '') {
            Session::set('error', 'Vui long nhap day du email va mat khau');
            $this->redirect(app_url('auth/login.php'));
        }

        $teacher = $this->teachers->findByEmail($email);
        if ($teacher && $password === $teacher['password']) {
            Auth::login($teacher);
            $this->redirect(app_url('interface/index.php'));
        }

        Session::set('error', 'Sai email hoac mat khau');
        $this->redirect(app_url('auth/login.php'));
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect(app_url('auth/login.php'));
    }
}
