<?php
include 'db.php';

// Establish database connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve POST data
$task_id = $_POST['task_id'];
$customer_name = $_POST['customer_name'];
$customer_email = $_POST['customer_email'];
$customer_address = $_POST['customer_address'];
$task_description = $_POST['task_description'];
$execution_date = $_POST['execution_date'];

// Determine status based on execution date
$current_date = date('Y-m-d'); // Current date in YYYY-MM-DD format
if ($execution_date > $current_date) {
    // Execution date is in the future, set status to 'pending'
    $status = 'pending';
} elseif ($execution_date == $current_date) {
    // Execution date is today, set status to 'on going'
    $status = 'on going';
} else {
    // Execution date is in the past, set status to 'finished'
    $status = 'finished';
}

// Update SQL query with prepared statement
$stmt = $conn->prepare("UPDATE tasks SET customer_name=?, customer_email=?, customer_address=?, task_description=?, execution_date=?, status=? WHERE id=?");
if (!$stmt) {
    die("Preparation failed: " . $conn->error);
}

// Bind parameters
$stmt->bind_param("ssssssi", $customer_name, $customer_email, $customer_address, $task_description, $execution_date, $status, $task_id);

// Execute statement and check for errors
if ($stmt->execute()) {
    header("Location: tasks.php?status=success");
} else {
    header("Location: edit_task_form.php?id=$task_id&status=error");
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
