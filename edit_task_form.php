<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .custom-container {
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            background-color: white;
            border-radius: 10px;
        }

        .custom-container input.form-control,
        .custom-container textarea.form-control {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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

        .main-content {
            height: calc(100vh - 80px);
            overflow-y: auto;
        }

        .container {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .center-button {
            display: flex;
            justify-content: center;
        }
        body{
           
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url("img/bg.jpg");
            background-size: cover; /* Ensures the image covers the entire background */
            background-position: center; /* Centers the image */
             background-repeat: no-repeat; /* Prevents the image from repeating */
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="tasks.php" style="font-weight: bold;">JN Computer Services</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"
                            href="index.php"><i class="bi bi-plus"></i> Add task</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'tasks.php' ? 'active' : ''; ?>"
                            href="tasks.php"><i class="bi bi-list-task"></i> Task List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'logout.php' ? 'active' : ''; ?>"
                            href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Success Alert -->
    <div id="successMessage" class="alert alert-success" style="display: none;">
        Task updated successfully.
    </div>

    <div class="container">
        <div class="custom-container">
            <h1 class="text-center" style="font-weight: bold;">Edit Task</h1><br>
            <?php
            include 'db.php';
            $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            if (isset($_GET['id'])) {
                $task_id = $_GET['id'];
                $sql = "SELECT * FROM tasks WHERE id='$task_id'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    ?>
                    <form id="editTaskForm" action="edit_task.php" method="post">
                        <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_name" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name"
                                    value="<?php echo $row['customer_name']; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="customer_email" class="form-label">Customer Email</label>
                                <input type="email" class="form-control" id="customer_email" name="customer_email"
                                    value="<?php echo $row['customer_email']; ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_address" class="form-label">Customer Address</label>
                                <textarea class="form-control" id="customer_address" name="customer_address" rows="3"
                                    required><?php echo $row['customer_address']; ?></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="task_description" class="form-label">Task Description</label>
                                <textarea class="form-control" id="task_description" name="task_description"
                                    rows="3" required><?php echo $row['task_description']; ?></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="execution_date" class="form-label">Execution Date</label>
                                <input type="date" class="form-control" id="execution_date" name="execution_date"
                                    value="<?php echo $row['execution_date']; ?>" required>
                            </div>
                        </div>
                        <br>
                        <div class="center-button">
                            <button type="submit" class="btn btn-primary">Update Task</button>
                        </div>
                    </form>
                    <?php
                } else {
                    echo "Task not found";
                }
            } else {
                echo "Task ID not provided";
            }
            $conn->close();
            ?>
            <hr>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle form submission
        $(document).ready(function () {
            $('#editTaskForm').submit(function (event) {
                event.preventDefault(); // Prevent default form submission

                // Perform AJAX request to submit form data
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function (response) {
                        // Show success message
                        $('#successMessage').fadeIn();

                        // Hide success message after 3 seconds
                        setTimeout(function () {
                            $('#successMessage').fadeOut();
                        }, 3000);

                        // Redirect to tasks.php after hiding success message
                        setTimeout(function () {
                            window.location.href = 'tasks.php';
                        }, 3500); // Adjust delay if necessary
                    },
                    error: function (xhr, status, error) {
                        // Show error message if AJAX request fails
                        console.error(error);
                        alert('Failed to update task.');
                    }
                });
            });
        });
    </script>
</body>

</html>
