<?php
require_once 'connection.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // جلب بيانات الموظف كاملة مع المستخدم + القسم
    $stmt = $connect->prepare("
        SELECT 
            employees.id AS emp_id,
            employees.phone,
            employees.hire_date,
            employees.salary,
            employees.position,
            employees.img,
            departments.dept_name,
            users.id AS user_id,
            users.name,
            users.email,
            users.role
        FROM employees
        LEFT JOIN departments ON employees.dept_id = departments.id
        LEFT JOIN users ON employees.user_id = users.id
        WHERE employees.id = :id
        LIMIT 1
    ");
    $stmt->execute(['id' => $id]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$employee) {
        header("Location: employees_table.php");
        exit();
    }
} else {
    header("Location: employees_table.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Details</title>
        <link rel="icon" href="img/favicon.svg" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="mb-4">Employee Details</h1>

        <div class="card shadow p-4">
            <div class="row">
                <div class="col-md-3 text-center">
                    <?php if (!empty($employee['img'])): ?>
                        <img src="./img/<?php echo htmlspecialchars($employee['img']); ?>" alt="Profile" class="img-fluid rounded-circle mb-3" style="width:150px; height:150px; object-fit:cover;">
                    <?php else: ?>
                        <img src="./img/default.png" alt="Default" class="img-fluid rounded-circle mb-3" style="width:150px; height:150px; object-fit:cover;">
                    <?php endif; ?>
                </div>
                <div class="col-md-9">
                    <table class="table table-bordered">
                        <tr>
                            <th>ID</th>
                            <td><?php echo $employee['emp_id']; ?></td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td><?php echo htmlspecialchars($employee['name']); ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo htmlspecialchars($employee['email']); ?></td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td><?php echo htmlspecialchars($employee['dept_name']); ?></td>
                        </tr>
                        <tr>
                            <th>Position</th>
                            <td><?php echo htmlspecialchars($employee['position']); ?></td>
                        </tr>
                        <tr>
                            <th>Salary</th>
                            <td><?php echo htmlspecialchars($employee['salary']); ?></td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td><?php echo htmlspecialchars($employee['phone']); ?></td>
                        </tr>
                        <tr>
                            <th>Hire Date</th>
                            <td><?php echo htmlspecialchars($employee['hire_date']); ?></td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td><?php echo htmlspecialchars($employee['role']); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="employees_table.php" class="btn btn-secondary">Back to Employees</a>
        </div>
    </div>
</body>
</html>
