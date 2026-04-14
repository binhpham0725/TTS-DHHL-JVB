<?php

class DashboardController extends Controller
{
    public function index(): void
    {
        Session::start();
        Auth::requireLogin();

        $allowedCourses = ['all', 'D16CNTT', 'D17CNTT', 'D18CNTT'];
        $activeClass = isset($_GET['class']) && in_array($_GET['class'], $allowedCourses, true)
            ? $_GET['class']
            : 'all';

        $this->render('dashboard/index', [
            'activeClass' => $activeClass,
            'teacherName' => Session::get('teacher_name', 'Chưa đăng nhập'),
        ]);
    }
}
