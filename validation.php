<?php
require_once 'connection.php';

function validate_input($data) {
    return htmlspecialchars(trim($data));
}

function check_unique($table, $col, $value) {
    global $connect;
    $check_column = $connect->query("SELECT $col FROM $table WHERE $col = '$value'");
    return $check_column->rowCount();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errors = [];
    $fullname   = validate_input($_POST['fullname'] ?? '');
    $email      = validate_input($_POST['email'] ?? '');
    $password   = $_POST['password'] ?? '';
    $department = $_POST['department'] ?? '';

    if (strlen($fullname) < 3) {
        $errors[] = "Full name must be at least 3 characters.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Enter a valid email address.";
    }

    if (strlen($password) < 4) {
        $errors[] = "Password must be at least 4 characters.";
    }

    // صورة الموظف (اختياري)
    $new_img_name = "default.png"; // صورة افتراضية
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_ext  = ['jpg', 'jpeg', 'png'];
        $allowed_mime = ['image/jpeg', 'image/png'];

        $file_name   = $_FILES['image']['name'];
        $file_tmp    = $_FILES['image']['tmp_name'];
        $file_ext    = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $file_mime   = mime_content_type($file_tmp);

        if (!in_array($file_ext, $allowed_ext) || !in_array($file_mime, $allowed_mime)) {
            $errors[] = "Only JPG, JPEG, and PNG image files are allowed.";
        } else {
            $safe_email = preg_replace('/[^a-zA-Z0-9]/', '_', $email);
            $new_img_name = $safe_email . "." . $file_ext;

            move_uploaded_file($file_tmp, "img/" . $new_img_name);
        }
    }

    if (check_unique('users', 'email', $email) > 0) {
        $errors[] = "Email is already taken.";
    }

    if (empty($errors)) {
        // users
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $insert_user = $connect->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
        $insert_user->execute([$fullname, $email, $hashed_password]);

        $user_id = $connect->lastInsertId();

        $insert_emp = $connect->prepare("INSERT INTO employees (user_id, img, dept_id) VALUES (?, ?, ?)");
        $insert_emp->execute([$user_id, $new_img_name, $department]);

        header("Location: login.php");
        exit;
    }
}
?>
