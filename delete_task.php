<?php
include 'db.php';

// Establish database connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$task_id = $_POST['task_id'];

$sql = "DELETE FROM tasks WHERE id='$task_id'";

if ($conn->query($sql) === TRUE) {
    // Task deleted successfully
    session_start();
    $_SESSION['delete_message'] = 'Task deleted successfully.';
    $_SESSION['delete_status'] = 'success';
} else {
    // Error deleting task
    session_start();
    $_SESSION['delete_message'] = 'Error: ' . $sql . '<br>' . $conn->error;
    $_SESSION['delete_status'] = 'error';
}

// Close the database connection
$conn->close();

// Redirect back to tasks.php
header('Location: tasks.php');
exit;
?>
