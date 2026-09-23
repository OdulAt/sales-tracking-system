<?php
session_start();
include __DIR__ . '/connectDb.php'; // Database connection

// Ensure only admins can access
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle delete request directly in this file
if (isset($_GET['delete_id'])) {
    $user_id = intval($_GET['delete_id']);

    if ($user_id == $_SESSION['admin_id']) {
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Action Denied',
                text: 'You can\'t delete yourself!',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            }).then(() => { window.location.href = 'manage_users.php'; });
        </script>";
    } else {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param('i', $user_id);

        if ($stmt->execute()) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'User Deleted',
                    text: 'The user has been successfully deleted.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then(() => { window.location.href = 'manage_users.php'; });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to delete user.',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK'
                }).then(() => { window.location.href = 'manage_users.php'; });
            </script>";
        }
        $stmt->close();
    }
}

// Fetch users from database
$result = $conn->query("SELECT id, name, email, role FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-4">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php">Admin Panel</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="manage_users.php">Manage Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_products.php">Manage Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="sales_reports.php">Sales Reports</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
        <h2>Manage Users</h2>
        <table class="table table-bordered">
            <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Action</th></tr>
            <?php while ($user = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= $user['name'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><?= $user['role'] ?></td>
                    <td>
                        <?php if ($user['id'] == $_SESSION['admin_id']): ?>
                            <button class='btn btn-secondary' disabled>Can't Delete</button>
                        <?php else: ?>
                            <button class='btn btn-danger' onclick="confirmDelete(<?= $user['id'] ?>)">Delete</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
    
    <script>
    function confirmDelete(userId) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Deleting...",
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                window.location.href = `manage_users.php?delete_id=${userId}`;
            }
        });
    }
    </script>
</body>
</html>