<?php

use core\Controller;

class Dashboard extends Controller
{
    public $data = [];
    public $dashboard_model;

    public function __construct()
    {
        $this->dashboard_model = $this->model("DashboardModel");
    }

    public function index()
    {
        $this->data['page_title']   = 'Dashboard';
        $this->data['content']      = 'admin/dashboard';
        $this->data['total_students']    = $this->dashboard_model->getTotalStudents();
        $this->data['total_rooms']       = $this->dashboard_model->getTotalRooms();
        $this->data['students_living']   = $this->dashboard_model->getStudentsLiving();
        $this->data['rooms_occupied']    = $this->dashboard_model->getRoomsOccupied();
        $this->data['pending']           = $this->dashboard_model->getPendingAssignments();
        $this->data['room_stats']        = $this->dashboard_model->getRoomStats();
        $this->data['recent_assignments'] = $this->dashboard_model->getRecentAssignments();
        $this->renderView('layouts/admin_layout', $this->data);
    }
}
