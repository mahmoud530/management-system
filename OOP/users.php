<?php
require_once 'connection.php';

class User {
    private $data;

    public function __construct($connect) {
        $this->data = $connect;
    }

    public function getAllUsers() {
        $stmt = $this->data->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function deleteUser($id) {
        $stmt = $this->data->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>
