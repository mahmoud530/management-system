<?php
class Employee {
    private $data;

    public function __construct($connect) {
        $this->data = $connect;
    }

    public function getAllEmployees() {
        $stmt = $this->data->prepare("
            SELECT u.id, u.name, u.email, e.img, d.dept_name, u.role
            FROM users u
            JOIN employees e ON u.id = e.id
            JOIN departments d ON e.dept_num = d.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteEmployee($id) {
        // نمسح الموظف من employees + المستخدم من users
        $this->data->prepare("DELETE FROM employees WHERE id = :id")->execute(['id' => $id]);
        $this->data->prepare("DELETE FROM users WHERE id = :id")->execute(['id' => $id]);
    }
}

?>