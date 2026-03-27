
<?php
include 'db.php';
include 'auth.php';

// Fetch all students
$students = [];
$query = "SELECT * FROM students ORDER BY created_at DESC";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List - Secure CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Secure CRUD System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Students</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="create.php">Add New Student</a>
                    </li>
                </ul>
                <div class="navbar-nav">
                    <span class="nav-item nav-link text-white">
                        <i class="bi bi-person-circle"></i> Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </span>
                    <a class="nav-item nav-link text-white" href="logout.php">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Student List</h4>
                        <a href="create.php" class="btn btn-light btn-sm">
                            <i class="bi bi-plus-circle"></i> Add New Student
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php 
                                echo htmlspecialchars($_SESSION['message']);
                                unset($_SESSION['message']);
                                ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php 
                                echo htmlspecialchars($_SESSION['error']);
                                unset($_SESSION['error']);
                                ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (empty($students)): ?>
                            <div class="alert alert-info">No students found. <a href="create.php">Add your first student</a></div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Student Number</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email</th>
                                            <th>Course</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($students as $student): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($student['id']); ?></td>
                                                <td><?php echo htmlspecialchars($student['student_number']); ?></td>
                                                <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                                                <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                                                <td><?php echo htmlspecialchars($student['email']); ?></td>
                                                <td><?php echo htmlspecialchars($student['course']); ?></td>
                                                <td><?php echo date('M d, Y', strtotime($student['created_at'])); ?></td>
                                                <td>
                                                    <a href="edit.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>
                                                    <a href="delete.php?id=<?php echo $student['id']; ?>" 
                                                       class="btn btn-sm btn-danger" 
                                                       onclick="return confirm('Are you sure you want to delete this student?')">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
