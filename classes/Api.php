<?php
class Api{
    public function getAllStudents($db){
        $sql = "SELECT * FROM  student WHERE is_deleted != 1";
        try {
            $stmt = $db->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $data;
        } catch (\PDOException $e) {
            $data = $e->getMessage();
            return $data;
        }
    }

    public function getSingleStudent($db, $student_id){
        $sql = "SELECT * FROM student where student_id = $student_id";
        try {
            $stmt = $db->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $data;
        } catch (\PDOException $e) {
            $data = $e->getMessage();
            return $data;
        }
    }

    public function addNewStudent($db, $student_data){
        $first_name = $student_data['first_name'];
        $last_name = $student_data['last_name'];
        $email_id = $student_data['email_id'];
        $contact_no = $student_data['contact_no'];
        $city = $student_data["city"];
        $state = $student_data["state"];
        try {
            $sql = "INSERT INTO student (first_name, last_name, email_id, contact_no, city, state) VALUES (?,?,?,?,?,?)";
            $db->prepare($sql)->execute([$first_name, $last_name, $email_id, $contact_no, $city, $state]);
            $msg = "New Student Added Successfully";
            return $msg;
        } catch (\PDOException $e) {
            $msg = $e->getMessage();
            return $msg;
        }
    }

    public function updateStudent($db, $student_data, $student_id){
        $first_name = $student_data['first_name'];
        $last_name = $student_data['last_name'];
        $contact_no = $student_data['contact_no'];
        try {
            $sql = "UPDATE student SET first_name =?, last_name=?, contact_no=? WHERE student_id=?";
            $db->prepare($sql)->execute([$first_name, $last_name, $contact_no, $student_id]);
            $msg = "Student Updated Successfully";
            return $msg;
        } catch (\PDOException $e) {
            $msg = $e->getMessage();
            return $msg;
        }
    }

    public function removeStudent($db, $student_id){
        try {
            $sql = "UPDATE student SET is_deleted = 1 WHERE student_id=?";
            $db->prepare($sql)->execute([$student_id]);
            $msg = "Student Deleted Successfully";
            return $msg;
        } catch (\PDOException $e) {
            $msg = $e->getMessage();
            return $msg;
        }
    }
}
