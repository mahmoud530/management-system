<?php
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $errors   = [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Enter a valid email address.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    if (empty($errors)) {
        $check_user = $connect->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $check_user->execute(['email' => $email]);
        $user = $check_user->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // set user session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name']    = $user['name'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['role']    = $user['role'];

            // لو موظف عادي (user) نجيب بياناته من جدول employees
            if ($user['role'] === 'user') {
                $stmt = $connect->prepare("
                    SELECT e.*, d.dept_name 
                    FROM employees e
                    LEFT JOIN departments d ON e.dept_id = d.id
                    WHERE e.user_id = :uid
                    LIMIT 1
                ");
                $stmt->execute(['uid' => $user['id']]);
                $emp = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($emp) {
                    $_SESSION['emp_id']   = $emp['id'];
                    $_SESSION['dept_id']  = $emp['dept_id'];
                    $_SESSION['dept_name']= $emp['dept_name'];
                    $_SESSION['img']      = $emp['img'];
                }
            }

            // التوجيه حسب الـ role
            if ($user['role'] === 'admin' || $user['role'] === 'super_admin') {
                header("Location: employees_table.php"); // صفحة إدارة الموظفين
            } else {
                header("Location: index.php"); // صفحة الموظف العادي
            }
            exit;
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="./css/login.css">
        <link rel="icon" href="img/favicon.svg" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="form-container">
        <h3>LogIn</h3>
        <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        }
        ?>
        <form novalidate action="" method="POST">
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">LogIn</button>
            <a href="register.php">Go To Register</a>
        </form>
    </div>
</body>
</html>
