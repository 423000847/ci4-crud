
<?php
include 'db.php';
include 'auth.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invalid student ID";
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Check if student exists
$check_stmt = $conn->prepare("SELECT id FROM students WHERE id = ?");
$check_stmt->bind_param("i", $id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows === 0) {
    $_SESSION['error'] = "Student not found";
    header("Location: index.php");
    exit();
}
$check_stmt->close();

// Delete student using prepared statement
$delete_stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
$delete_stmt->bind_param("i", $id);

if ($delete_stmt->execute()) {
    $_SESSION['message'] = "Student deleted successfully";
} else {
    $_SESSION['error'] = "Error deleting student: " . $conn->error;
}

$delete_stmt->close();
header("Location: index.php");
exit();
?>
