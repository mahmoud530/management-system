<?php
require_once 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
//get employee data
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $result = $connect->query("
        SELECT employees.*, users.name, users.email 
        FROM employees 
        JOIN users ON employees.user_id = users.id 
        WHERE employees.id = $id
    ");
    $employee = $result->fetch(PDO::FETCH_ASSOC);

    if (!$employee) {
        header("Location: employees_table.php");
        exit();
    }
} else {
    header("Location: employees_table.php");
    exit();
}

$errors = [];

// edit
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $fullname   = $_POST['fullname'] ?? '';
    $email      = $_POST['email'] ?? '';
    $phone      = $_POST['phone'] ?? '';
    $position   = $_POST['position'] ?? '';
    $salary     = $_POST['salary'] ?? null;
    $hire_date  = $_POST['hire_date'] ?? null;
    $department = $_POST['department'] ?? null;

    // employee image
    $img = $employee['img'];
    if (!empty($_FILES['img']['name'])) {
        $imgName = time() . "_" . basename($_FILES["img"]["name"]);
        if (move_uploaded_file($_FILES["img"]["tmp_name"], "img/" . $imgName)) {
            $img = $imgName;
        } else {
            $errors[] = "Image upload failed.";
        }
    }

    // validation
    if (empty($fullname) || empty($email)) {
        $errors[] = "Full name and email are required.";
    }

    // update if no errors
    if (empty($errors)) {
        // update users table
        $connect->query("UPDATE users SET name='$fullname', email='$email' WHERE id=" . $employee['user_id']);

        // update employees table
        $connect->query("UPDATE employees SET 
            phone='$phone', 
            position='$position', 
            salary='$salary', 
            hire_date='$hire_date', 
            dept_id='$department', 
            img='$img'
            WHERE id=$id
        ");

        header("Location: employees_table.php");
        exit();
    }
}

// get departments
$departments = $connect->query("SELECT * FROM departments")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Employee</title>
    <link rel="icon" href="img/favicon.svg" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <style>
    body {
        background-color: #f8f9fa;
        direction: ltr;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        color: #212529;
    }

    .form-container {
        max-width: 600px;
        margin: 50px auto;
        background-color: #ffffff;
        padding: 30px 25px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
    }

    .form-container:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    h3 {
        text-align: center;
        margin-bottom: 25px;
        color: #0d6efd;
        font-size: 1.8rem;
        font-weight: bold;
    }
</style>
</head>
<body>
    <div class="form-container">
        <h3>Edit Employee</h3>

        <!-- error messages -->
        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endforeach; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" name="fullname" value="<?= $employee['name']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?= $employee['email']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" class="form-control" name="phone" value="<?= $employee['phone']; ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Position</label>
                <input type="text" class="form-control" name="position" value="<?= $employee['position']; ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Salary</label>
                <input type="number" step="0.01" class="form-control" name="salary" value="<?= $employee['salary']; ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Hire Date</label>
                <input type="date" class="form-control" name="hire_date" value="<?= $employee['hire_date']; ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Department</label>
                <select name="department" class="form-select" required>
                    <?php foreach ($departments as $dep): ?>
                        <option value="<?= $dep['id']; ?>" <?= $dep['id'] == $employee['dept_id'] ? 'selected' : '' ?>>
                            <?= $dep['dept_name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Profile Image</label><br>
                <?php if (!empty($employee['img'])): ?>
                    <img src="img/<?= $employee['img']; ?>" alt="Profile" style="width:80px; height:80px; object-fit:cover;" class="rounded mb-2">
                <?php endif; ?>
                <input type="file" class="form-control" name="img">
            </div>

            <button type="submit" class="btn btn-primary w-100">Save Changes</button>
        </form>

        <div class="mt-4 text-center">
            <a href="employees_table.php" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</body>
</html>
