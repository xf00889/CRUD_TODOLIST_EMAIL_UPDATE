<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<?php
include 'db.php';

// Establish database connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination variables
$results_per_page = 5; // Number of items per page
$number_of_rows = $conn->query("SELECT COUNT(*) FROM tasks")->fetch_row()[0]; // Total number of tasks
$total_pages = ceil($number_of_rows / $results_per_page); // Calculate total pages

// Determine current page number
if (!isset($_GET['page'])) {
    $page = 1; // Default page number
} else {
    $page = $_GET['page'];
}

// Calculate the SQL LIMIT starting number for the results on the displaying page
$this_page_first_result = ($page - 1) * $results_per_page;

// Retrieve tasks with pagination
$sql = "SELECT * FROM tasks LIMIT $this_page_first_result, $results_per_page";
$result = $conn->query($sql);

$updateSuccess = isset($_GET['update']) && $_GET['update'] == 'success';
$emailSent = isset($_GET['email']) && $_GET['email'] == 'sent';
$emailFailed = isset($_GET['email']) && $_GET['email'] == 'failed';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url("img/bg.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            background-color: #fff;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add shadow */
            border-radius: 10px; /* Add rounded corners */
            overflow: hidden; /* Ensure the shadow and radius apply properly */
        }
        .table th,
        .table td {
            padding: 0.75rem;
            text-align: left;
            border: 1px solid #dee2e6;
        }
        .table th {
            background-color: #343a40; /* Header background color */
            color: #fff; /* Header text color */
            border-color: #dee2e6;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
        .table .btn {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
        .alert {
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            display: none;
        }
        .navbar {
            padding: 10px;
        }
        .sticky-top {
            top: 0;
            z-index: 1020;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="tasks.php" style="font-weight: bold;">JN Computer Services</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php"><i class="bi bi-plus"></i> Add task</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'tasks.php' ? 'active' : ''; ?>" href="tasks.php"><i class="bi bi-list-task"></i> Task List</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'logout.php' ? 'active' : ''; ?>" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <?php if ($updateSuccess): ?>
                <div class="alert alert-success" id="success-alert">
                    Task status updated successfully!
                    <?php if ($emailSent): ?>
                        <br>Email sent successfully to the customer.
                    <?php elseif ($emailFailed): ?>
                        <br>Email could not be sent to the customer.
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="header">
                <h2 style="font-weight: bold;">Task List</h2>
                <p style="font-weight: italic;">A comprehensive task organizer for computer technicians</p>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Task Description</th>
                        <th>Customer Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Execution Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['task_description']); ?></td>
                            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['customer_email']); ?></td>
                            <td><?php echo htmlspecialchars($row['customer_address']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td><?php echo htmlspecialchars($row['execution_date']); ?></td>
                            <td>
                                <form action="update_task.php" method="post" style="display:inline-block;">
                                    <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="status" value="finished">
                                    <button type="submit" class="btn btn-success"><i class="bi bi-check-circle-fill"></i></button>
                                </form>
                                <a href="edit_task_form.php?id=<?php echo $row['id']; ?>" class="btn btn-warning"><i class="bi bi-pencil-square"></i></a>
                                <form action="delete_task.php" method="post" style="display:inline-block;">
                                    <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <?php for ($page = 1; $page <= $total_pages; $page++): ?>
                        <li class="page-item <?php if (isset($_GET['page']) && $_GET['page'] == $page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($updateSuccess): ?>
        const alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.display = 'block';
            setTimeout(() => alert.style.display = 'none', 3000);
        }
        <?php endif; ?>
    });
</script>
</body>
</html>

<?php
$conn->close();
?>
