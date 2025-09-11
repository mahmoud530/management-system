<?php
include'connection.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$name  = $_SESSION['name'] ?? '';
$role  = $_SESSION['role'] ?? 'user';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>profile</title>
        <link rel="icon" href="img/favicon.svg" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #007bff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* ظل خفيف */
    transition: transform 0.3s ease;
}

.profile-img:hover {
    transform: scale(1.05); /* تكبير بسيط عند تمرير الماوس */
}

    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h3 class="text-center text-primary">👋 Welcome, <?php echo htmlspecialchars($name); ?>!</h3>
            <hr>

            <?php if ($role === 'user'): ?>
                <div class="text-center">
                    <?php if (!empty($_SESSION['img'])): ?>
                        <img src="img/<?php echo htmlspecialchars($_SESSION['img']); ?>" 
                             alt="Profile Image" class="profile-img mb-3">
                    <?php else: ?>
                        <img src="img/default.png" alt="Default Image" class="profile-img mb-3">
                    <?php endif; ?>
                    
                    <h5>Department: <?php echo htmlspecialchars($_SESSION['dept_name'] ?? 'N/A'); ?></h5>
                    <p>Email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    You are logged in as <strong><?php echo ucfirst($role); ?></strong>.
                </div>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
           <div class="mt-4 text-center">
       <a href="index.php" class="btn btn-secondary">Back To Home</a>
   </div>
    </div>
</body>
</html>
