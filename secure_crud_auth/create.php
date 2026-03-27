<?php
include 'db.php';
include 'auth.php';

if (isset($_POST['create'])) {
    $student_number = trim($_POST['student_number']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $course = trim($_POST['course']);
    
    $errors = [];
    
    // Validation
    if (empty($student_number) || empty($first_name) || empty($last_name) || empty($email)) {
        $errors[] = "All fields except course are required";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    // Check duplicate student number
    if (empty($errors)) {
        $check_stmt = $conn->prepare("SELECT id FROM students WHERE student_number = ?");
        $check_stmt->bind_param("s", $student_number);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $errors[] = "Student number already exists";
        }
        $check_stmt->close();
    }
    
    // Insert student
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO students (student_number, first_name, last_name, email, course) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $student_number, $first_name, $last_name, $email, $course);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Student added successfully";
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Error adding student: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - Secure CRUD</title>
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
                        <a class="nav-link" href="index.php">Students</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="create.php">Add New Student</a>
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Add New Student</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="student_number" class="form-label">Student Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="student_number" name="student_number" 
                                       value="<?php echo isset($student_number) ? htmlspecialchars($student_number) : ''; ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="<?php echo isset($first_name) ? htmlspecialchars($first_name) : ''; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="<?php echo isset($last_name) ? htmlspecialchars($last_name) : ''; ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="course" class="form-label">Course</label>
                                <input type="text" class="form-control" id="course" name="course" 
                                       value="<?php echo isset($course) ? htmlspecialchars($course) : ''; ?>">
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" name="create" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Save Student
                                </button>
                                <a href="index.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
