<?php
require_once 'connection.php';
require_once './OOP/users.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') === 'user') {
    header("Location: login.php");
    exit;
}

$sql = "
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
    ORDER BY employees.id DESC
";

$stmt = $connect->prepare($sql);
$stmt->execute();
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

// user oop
$userObj = new User($connect);
$users = $userObj->getAllUsers();

//currentUser
$currentUserId = $_SESSION['user_id'];
$currentUser = null;

foreach ($users as $data) {
    if ($data['id'] == $currentUserId) {
        $currentUser = $data;
        break;
    }
}

// حذف موظف (فقط super_admin مسموح له)
if ($currentUser && $currentUser['role'] === 'super_admin' && isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];

    if ($deleteId !== $currentUserId) {
        $userObj->deleteUser($deleteId);
        header("Location: employee_table.php");
        exit;
    }
}

//filter departments 
$deptStmt = $connect->query("SELECT DISTINCT dept_name FROM departments ORDER BY dept_name");
$departments = $deptStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>
        <link rel="icon" href="img/favicon.svg" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <style>
    .highlight {
        background-color: #fff3cd; 
        font-weight: bold;
        padding: 3px 6px;
        border-radius: 4px;
    }

    .filter-controls {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .no-results {
        display: none;
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
        font-size: 1.2rem;
        border: 2px dashed #ced4da;
        border-radius: 10px;
        background-color: #f1f3f5;
    }
   
</style>

</head>

<body>
    <div class="container my-5">
        <h1>Employee List</h1>
        <p>Welcome, <b><?php echo htmlspecialchars($_SESSION['name']); ?></b> (<?php echo $_SESSION['role']; ?>)</p>

        <!-- Live Search & Filter Controls -->
        <div class="filter-controls">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="searchName" class="form-label">Search by Name:</label>
                    <input type="text" id="searchName" class="form-control" placeholder="Type to search by name...">
                </div>
                <div class="col-md-4">
                    <label for="filterDepartment" class="form-label">Filter by Department:</label>
                    <select id="filterDepartment" class="form-control">
                        <option value="">All Departments</option>
                        <?php foreach ($departments as $dept) { ?>
                            <option value="<?php echo htmlspecialchars($dept['dept_name']); ?>">
                                <?php echo htmlspecialchars($dept['dept_name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filterSalary" class="form-label">Minimum Salary:</label>
                    <input type="number" id="filterSalary" class="form-control" placeholder="Enter minimum salary" min="0">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button type="button" id="clearFilters" class="btn btn-outline-secondary">Clear All Filters</button>
                    <span class="ms-3">
                        <strong>Results: <span id="resultCount"><?php echo count($employees); ?></span> employees</strong>
                    </span>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="employeeTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Salary</th>
                        <th>Phone</th>
                        <th>Hire Date</th>
                        <th>Image</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="employeeTableBody">
                    <?php if (count($employees) > 0) { ?>
                        <?php foreach ($employees as $emp) { ?>
                            <tr class="employee-row" 
                                data-name="<?php echo strtolower(htmlspecialchars($emp['name'])); ?>"
                                data-department="<?php echo htmlspecialchars($emp['dept_name']); ?>"
                                data-salary="<?php echo $emp['salary']; ?>">
                                <td><?php echo $emp['emp_id']; ?></td>
                                <td class="employee-name"><?php echo htmlspecialchars($emp['name']); ?></td>
                                <td><?php echo htmlspecialchars($emp['email']); ?></td>
                                <td class="employee-department"><?php echo htmlspecialchars($emp['dept_name']); ?></td>
                                <td><?php echo htmlspecialchars($emp['position']); ?></td>
                                <td class="employee-salary"><?php echo number_format($emp['salary'], 2); ?></td>
                                <td><?php echo htmlspecialchars($emp['phone']); ?></td>
                                <td><?php echo htmlspecialchars($emp['hire_date']); ?></td>
                                <td>
                                    <?php if (!empty($emp['img'])): ?>
                                        <img src="./img/<?php echo htmlspecialchars($emp['img']); ?>" alt="Image"
                                            style="width: 50px; height: 50px; border-radius:50%;">
                                    <?php else: ?>
                                        <img src="./img/default.png" alt="Default"
                                            style="width: 50px; height: 50px; border-radius:50%;">
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($emp['role']); ?></td>
                                <td>
                                    <a href="show.php?id=<?php echo $emp['emp_id']; ?>" class="btn btn-primary btn-sm">Show</a>
                                    <a href="edit.php?id=<?php echo $emp['emp_id']; ?>" class="btn btn-success btn-sm">Edit</a>
                                    <?php if ($currentUser && $currentUser['role'] === 'super_admin' && $emp['user_id'] != $currentUserId) { ?>
                                        <a href="employee_table.php?delete=<?php echo $emp['user_id']; ?>" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure?')">Delete</a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="no-results">
            <h4>No employees found</h4>
            <p>Try adjusting your search criteria.</p>
        </div>

        <div class="mt-4">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

 <script>
// العناصر الأساسية
const searchInput = document.getElementById('searchName');
const deptFilter = document.getElementById('filterDepartment');
const salaryFilter = document.getElementById('filterSalary');
const clearBtn = document.getElementById('clearFilters');
const rows = document.querySelectorAll('.employee-row');

function filterTable() {
    // جلب القيم المدخلة
    const searchText = searchInput.value.toLowerCase();  
    const selectedDept = deptFilter.value;               
    const minSalary = Number(salaryFilter.value) || 0;   
    
    let visibleRows = 0;  
    
    // تكرار على كل صف
    rows.forEach(row => {
        // جلب البيانات من data attributes
        const name = row.dataset.name;          
        const dept = row.dataset.department;    
        const salary = Number(row.dataset.salary) || 0; 
        
        // شروط الإظهار
        const matchName = !searchText || name.includes(searchText);    
        const matchDept = !selectedDept || dept === selectedDept;      
        const matchSalary = salary >= minSalary;                       
        
        // إذا تحققت كل الشروط
        if (matchName && matchDept && matchSalary) {
            row.style.display = '';       
            visibleRows++;                
            highlightName(row, searchText);
        } else {
            row.style.display = 'none';   
        }
    });
    
    // تحديث عدد النتائج
    document.getElementById('resultCount').textContent = visibleRows;
    
    // إظهار/إخفاء رسالة عدم وجود نتائج
    const noResults = document.getElementById('noResults');
    noResults.style.display = visibleRows === 0 ? 'block' : 'none';
}
// تمييز النص
function highlightName(row, searchText) {
    const nameCell = row.querySelector('.employee-name');
    let text = nameCell.textContent;
    
    if (searchText) {
        text = text.replace(new RegExp(`(${searchText})`, 'gi'), '<mark>$1</mark>');
    }
    
    nameCell.innerHTML = text;
}

// مسح الفلاتر
function clearAll() {
    searchInput.value = '';
    deptFilter.value = '';
    salaryFilter.value = '';
    
    // إزالة التمييز
    rows.forEach(row => {
        const nameCell = row.querySelector('.employee-name');
        nameCell.innerHTML = nameCell.textContent;
    });
    
    filterTable();
}

// ربط الأحداث
searchInput.oninput = filterTable;
deptFilter.onchange = filterTable;
salaryFilter.oninput = filterTable;
clearBtn.onclick = clearAll;

// تشغيل عند التحميل
window.onload = filterTable;
</script>
</body>
</html>