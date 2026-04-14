<?php

class DashboardModel extends Model
{
    public function getTotalStudents()
    {
        return $this->db->fetch("SELECT COUNT(*) as total FROM students")['total'] ?? 0;
    }

    public function getTotalRooms()
    {
        return $this->db->fetch("SELECT COUNT(*) as total FROM rooms")['total'] ?? 0;
    }

    public function getStudentsLiving()
    {
        $sql = "SELECT COUNT(DISTINCT student_id) as total FROM room_assignment WHERE status = 1";
        return $this->db->fetch($sql)['total'] ?? 0;
    }

    public function getRoomsOccupied()
    {
        return $this->db->fetch("SELECT COUNT(*) as total FROM rooms WHERE current_number > 0")['total'] ?? 0;
    }

    public function getPendingAssignments()
    {
        return $this->db->fetch("SELECT COUNT(*) as total FROM room_assignment WHERE status = 0")['total'] ?? 0;
    }

    public function getRoomStats()
    {
        $sql = "SELECT r.room_name, rt.type_name, r.current_number, rt.max_people, r.gender,
                ROUND(r.current_number / rt.max_people * 100) as percent
                FROM rooms r
                JOIN room_types rt ON rt.id = r.room_type_id
                ORDER BY percent DESC";
        return $this->db->fetchAll($sql);
    }

    public function getRecentAssignments()
    {
        $sql = "SELECT ra.id, s.name as student_name, s.mssv, rt.type_name, ra.status, ra.created_date
                FROM room_assignment ra
                JOIN students s ON s.id = ra.student_id
                JOIN room_types rt ON rt.id = ra.room_type_id
                ORDER BY ra.created_date DESC
                LIMIT 5";
        return $this->db->fetchAll($sql);
    }
}
