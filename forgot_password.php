<?php
include __DIR__ . '/connectDb.php'; // Include database connection
session_start();

$errors = [];
$success = false;
$emailVerified = false;
$email = '';

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['verify_email'])) {
        // Verify email step
        $email = trim($_POST['email']);
        
        if (empty($email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        } else {
            // Check if email exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $emailVerified = true;
                $_SESSION['reset_email'] = $email; // Store email for password update
            } else {
                $errors[] = "No account found with that email.";
            }
            $stmt->close();
        }
    } elseif (isset($_POST['reset_password'])) {
        // Reset password step
        $email = $_SESSION['reset_email'];
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);
        
        if (empty($password) || empty($confirm_password)) {
            $errors[] = "Both password fields are required.";
        } elseif ($password !== $confirm_password) {
            $errors[] = "Passwords do not match.";
        } elseif (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        } else {
            // Update password in database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashedPassword, $email);
            
            if ($stmt->execute()) {
                $success = true;
                unset($_SESSION['reset_email']); // Clear the session
                
                // Redirect to login after 3 seconds
                header("Refresh: 3; url=login.php");
            } else {
                $errors[] = "Error updating password. Please try again.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .card {
            max-width: 500px;
            margin: 0 auto;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Sales Tracker</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about_us.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact_us.php">Contact Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="sign_up.php">Sign Up</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-primary text-white" href="login.php">Log In</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center">Password Recovery</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error) echo "<p>$error</p>"; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success text-center">
                <p>Password updated successfully! Redirecting to login page...</p>
            </div>
        <?php else: ?>
            <div class="card p-4">
                <!-- Email Verification Step -->
                <div class="form-step <?php echo !$emailVerified ? 'active' : ''; ?>" id="step1">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Enter your email address</label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                        <button type="submit" name="verify_email" class="btn btn-primary w-100">Verify Email</button>
                        <p class="text-center mt-3"><a href="login.php">Back to Login</a></p>
                    </form>
                </div>

                <!-- Password Reset Step -->
                <div class="form-step <?php echo $emailVerified ? 'active' : ''; ?>" id="step2">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" 
                                   placeholder="At least 8 characters" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" name="reset_password" class="btn btn-primary w-100">Reset Password</button>
                        <p class="text-center mt-3"><a href="login.php">Back to Login</a></p>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>