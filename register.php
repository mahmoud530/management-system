<?php
require_once 'connection.php';
require 'validation.php';

// الأقسام
$run_dep = $connect->query("SELECT * FROM departments")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="./css/register.css">
        <link rel="icon" href="img/favicon.svg" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="form-container">
        <h3>Sign Up</h3>
        <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        }
        ?>
        <form novalidate action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" name="fullname" placeholder="Enter your name">
            </div>
 
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" class="form-control" name="email" placeholder="Enter your email">
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Enter your password">
            </div>

            <div class="mb-3">
                <label class="form-label">Profile Image</label>
                <input type="file" class="form-control" name="image">
            </div>

            <div class="mb-3">
                <label class="form-label">Department</label>
                <select name="department" class="form-control">
                    <?php foreach ($run_dep as $dep) { ?>
                        <option value="<?php echo $dep['id']?>"><?php echo $dep['dept_name']?></option>
                    <?php } ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Register</button>
            <a href="login.php">Go To Login!</a>
        </form>
    </div>
</body>
</html>
