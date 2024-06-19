<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js"></script>
    <style>
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }
        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }
        .table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }
        .table .table {
            background-color: #fff;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="header">
                <h2>Task List</h2>
                <p>A comprehensive task organizer for computer technicians</p>
            </div>
            <table class="table">
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
                <?php
                include 'db.php';

                // Establish database connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $result = $conn->query("SELECT * FROM tasks");

                while ($row = $result->fetch_assoc()): ?>
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
                                <button type="submit" class="btn btn-success">Mark as Finished</button>
                            </form>
                            <a href="#" class="btn btn-danger btn-delete" data-id="<?php echo $row['id']; ?>">Delete</a>
                            <a href="edit_task_form.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                        </td>
                    </tr>
                <?php endwhile; ?>

                <?php
                $conn->close();
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Show pop-up message after updating task status
        <?php
        session_start();
        if (isset($_SESSION['update_message']) && isset($_SESSION['update_status'])):
            $updateMessage = $_SESSION['update_message'];
            $updateStatus = $_SESSION['update_status'];
            unset($_SESSION['update_message']);
            unset($_SESSION['update_status']);
        ?>
        alert('<?php echo $updateMessage; ?>');
        <?php endif; ?>
    });
</script>

</body>
</html>
