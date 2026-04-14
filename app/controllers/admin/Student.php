<?php

use core\Controller;

class Student extends Controller
{
    public $data = [];
    public $student_model;

    public function __construct()
    {
        $this->student_model = $this->model("StudentModel");
    }

    public function index()
    {
        $this->data['page_title'] = 'Quản lý sinh viên';
        $this->data['content']    = 'admin/student';
        $this->data['js_file']    = 'student.js';
        $this->renderView('layouts/admin_layout', $this->data);
    }

    public function getListData()
    {
        header('Content-Type: application/json; charset=utf-8');
        $body    = json_decode(file_get_contents('php://input'), true);
        $page    = (int)($body['page']     ?? 1);
        $limit   = (int)($body['per_page'] ?? 10);
        $keyword = trim($body['keyword']   ?? '');
        $offset  = ($page - 1) * $limit;

        $total = $this->student_model->countAll($keyword);
        $data  = $this->student_model->getStudent($limit, $offset, $keyword);

        echo json_encode([
            'status'     => 'success',
            'data'       => $data,
            'total'      => (int)$total,
            'total_page' => (int)ceil($total / $limit),
        ]);
        exit();
    }

    public function getStudentById($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->student_model->getStudentById((int)$id));
        exit();
    }

    public function addStudent()
    {
        header('Content-Type: application/json; charset=utf-8');
        $d = json_decode(file_get_contents('php://input'), true);
        try {
            $result = $this->student_model->insertStudent(
                $d['mssv'], $d['name'], $d['gender'], $d['birthday'],
                $d['cccd'], $d['email'], $d['phone'], $d['address'], $d['password']
            );
            echo json_encode($result
                ? ['status' => 'success', 'message' => 'Thêm sinh viên thành công']
                : ['status' => 'error',   'message' => 'Thêm sinh viên thất bại']
            );
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->errorInfo[2] ?? $e->getMessage()]);
        }
        exit();
    }

    public function update()
    {
        header('Content-Type: application/json; charset=utf-8');
        $d = json_decode(file_get_contents('php://input'), true);
        try {
            $result = $this->student_model->updateStudent(
                $d['id'], $d['mssv'], $d['name'], $d['gender'], $d['birthday'],
                $d['cccd'], $d['email'], $d['phone'], $d['address']
            );
            echo json_encode($result
                ? ['status' => 'success', 'message' => 'Cập nhật sinh viên thành công']
                : ['status' => 'error',   'message' => 'Cập nhật sinh viên thất bại']
            );
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->errorInfo[2] ?? $e->getMessage()]);
        }
        exit();
    }

    public function delete($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $result = $this->student_model->deleteById((int)$id);
            echo json_encode($result
                ? ['status' => 'success', 'message' => 'Xóa sinh viên thành công']
                : ['status' => 'error',   'message' => 'Xóa sinh viên thất bại']
            );
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit();
    }
}
