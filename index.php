
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
    <title>Add Task</title>
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
<div class="container">
    <div class="custom-container">
        <h1 class="text-center" style="font-weight: bold;">Add Task</h1><br>
        <form id="taskForm" action="add_task.php" method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="customer_name" class="form-label">Customer Name</label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="customer_email" class="form-label">Customer Email</label>
                    <input type="email" class="form-control" id="customer_email" name="customer_email" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="customer_address" class="form-label">Customer Address</label>
                    <textarea class="form-control" id="customer_address" name="customer_address" rows="3" required></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="task_description" class="form-label">Task Description</label>
                    <textarea class="form-control" id="task_description" name="task_description" rows="3" required></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="execution_date" class="form-label">Execution Date</label>
                    <input type="date" class="form-control" id="execution_date" name="execution_date" required>
                </div>
            </div>
            <br>
            <div class="center-button">
                <button type="submit" class="btn btn-primary">Add Task</button>
            </div>
        </form>
        <hr>
    </div>
</div>
<div id="successMessage" class="alert alert-success">
    Task added successfully!
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js"></script>
<script>
    // Handle form submission
    $('#taskForm').submit(function(event) {
        event.preventDefault(); // Prevent default form submission

        // Simulate successful submission (replace with actual form submission logic)
        setTimeout(function() {
            $('#successMessage').fadeIn(); // Show success message
            setTimeout(function() {
                $('#successMessage').fadeOut(); // Hide success message after 3 seconds
                // Redirect to tasks.php after hiding success message
                window.location.href = 'tasks.php';
            }, 3000);
        }, 1000); // Simulated delay, replace with actual AJAX submission logic
    });

    $(document).ready(function () {
        // Fetch tasks only on the tasks page
        if (window.location.pathname.endsWith('tasks.php')) {
            fetchTasks();
        }

        // Add Task
        $('#taskForm').on('submit', function (e) {
            e.preventDefault();

            const customerName = $('#customer_name').val();
            const customerEmail = $('#customer_email').val();
            const customerAddress = $('#customer_address').val();
            const taskDescription = $('#task_description').val();
            const executionDate = $('#execution_date').val(); // Get execution date

            $.post('add_task.php', {
                customer_name: customerName,
                customer_email: customerEmail,
                customer_address: customerAddress,
                task_description: taskDescription,
                execution_date: executionDate // Send execution date
            }, function (response) {
                displayMessage(response, 'success');
                $('#taskForm')[0].reset();
                fetchTasks(); // Fetch tasks to update the list
            }).fail(function () {
                displayMessage('Failed to add task. Please try again.', 'error');
            });
        });

        // Fetch Tasks
        function fetchTasks() {
            $.get('fetch_tasks.php', function (data) {
                $('#taskList').html(data);
            }).fail(function () {
                displayMessage('Failed to fetch tasks. Please try again.', 'error');
            });
        }

        // Update Task
        $(document).on('click', '.btn-update', function () {
            const taskId = $(this).data('id');
            const status = $(this).data('status');

            $.post('update_task.php', { task_id: taskId, status: status }, function (response) {
                displayMessage(response, 'success');
                fetchTasks(); // Refresh task list
            }).fail(function () {
                displayMessage('Failed to update task. Please try again.', 'error');
            });
        });

        // Delete Task
         // Delete Task
         $(document).on('click', '.btn-delete', function () {
        const taskId = $(this).data('id');

        if (confirm("Are you sure you want to delete this task?")) {
            $.post('delete.php', { task_id: taskId }, function (response) {
                response = JSON.parse(response);
                if (response.status === 'success') {
                    displayMessage(response.message, 'success');
                    fetchTasks(); // Refresh task list or perform any other action
                } else {
                    displayMessage(response.message, 'error');
                }
            }).fail(function () {
                displayMessage('Failed to delete task. Please try again.', 'error');
            });
        }
    });

    // Function to display message
    function displayMessage(message, type) {
        const messageBox = $('<div class="alert"></div>');
        messageBox.text(message);
        messageBox.addClass(type === 'success' ? 'alert-success' : 'alert-danger');
        $('body').prepend(messageBox);

        setTimeout(function () {
            messageBox.fadeOut(function () {
                $(this).remove();
            });
        }, 3000);
    }

        // Display message
        function displayMessage(message, type) {
            const messageBox = $('<div class="alert"></div>');
            messageBox.text(message);
            messageBox.addClass(type === 'success' ? 'alert-success' : 'alert-danger');
            $('body').prepend(messageBox);

            setTimeout(function () {
                messageBox.fadeOut(function () {
                    $(this).remove();
                });
            }, 3000);
        }
    });
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
