<?php
include 'db.php';

// Establish database connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO tasks (customer_name, customer_email, customer_address, task_description, execution_date, status) VALUES (?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    die("Preparation failed: " . $conn->error);
}

// Set parameters from POST data
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
    // Execution date is in the past, default status to 'pending'
    $status = 'pending';
}

// Bind parameters
$stmt->bind_param("ssssss", $customer_name, $customer_email, $customer_address, $task_description, $execution_date, $status);

// Execute statement and check for errors
if ($stmt->execute()) {
    echo "New task added successfully";
} else {
    echo "Error: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
