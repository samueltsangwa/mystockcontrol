<?php
include 'includes/session.php';
include 'includes/conn.php';

// Handle Add User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "User successfully added.";
        } else {
            $_SESSION['error_message'] = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Passwords do not match.";
    }
    header("Location: users.php");
    exit();
}

// Handle Edit User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $userId = intval($_POST['user_id']);
    $username = trim($_POST['edit_username']);
    $email = trim($_POST['edit_email']);

    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
    $stmt->bind_param("ssi", $username, $email, $userId);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "User successfully updated.";
    } else {
        $_SESSION['error_message'] = "Error: " . $stmt->error;
    }
    $stmt->close();

    header("Location: users.php");
    exit();
}

// Handle Delete User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $userId = intval($_POST['user_id']);

    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "User successfully deleted.";
    } else {
        $_SESSION['error_message'] = "Error: " . $stmt->error;
    }
    $stmt->close();

    header("Location: users.php");
    exit();
}

// Fetch Users for Display
$userData = [];
$sql = "SELECT * FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userData[] = $row;
    }
}
$conn->close();
?>

<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>

    <div class="content-wrapper">
        <div class="container-fluid">
        <ol class="breadcrumb">
				<li><a href="home.php">Home</a></li>		  
				<li class="active">Users</li>
			</ol>
            <section class="content-header">
                <div class="panel panel-default">
              <div class="panel-heading">
                <i class="glyphicon glyphicon-edit"></i> Manage Users
              </div>
            </section>
            
            <section class="content">
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success text-center">
                        <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger text-center">
                        <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                    </div>
                <?php endif; ?>
                
                <div class="panel-body justify-content-center">
			  	<div class="div-action pull pull-right">
					<button class="btn btn-default button1" data-toggle="modal" data-target="#addUserModal"> <i class="glyphicon glyphicon-plus-sign"></i> Add User </button>
				</div> 
                <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Add User</button> -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userData as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-user" data-id="<?php echo $user['user_id']; ?>" data-username="<?php echo htmlspecialchars($user['username']); ?>" data-email="<?php echo htmlspecialchars($user['email']); ?>">Edit</button>
                                    <button class="btn btn-danger btn-sm delete-user" data-id="<?php echo $user['user_id']; ?>">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="users.php">
                <div class="modal-header">
                    <h4>Add User</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="users.php">
                <div class="modal-header">
                    <h4>Edit User</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" id="edit_user_id" name="user_id">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" id="edit_username" name="edit_username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="edit_email" name="edit_email" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="users.php">
                <div class="modal-header">
                    <h4>Delete User</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" id="delete_user_id" name="user_id">
                    <p>Are you sure you want to delete this user?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Trigger Edit Modal
    $('.edit-user').click(function () {
        const userId = $(this).data('id');
        const username = $(this).data('username');
        const email = $(this).data('email');

        $('#edit_user_id').val(userId);
        $('#edit_username').val(username);
        $('#edit_email').val(email);

        $('#editUserModal').modal('show');
    });

    // Trigger Delete Modal
    $('.delete-user').click(function () {
        const userId = $(this).data('id');
        $('#delete_user_id').val(userId);

        $('#deleteUserModal').modal('show');
    });
</script>

<?php include 'includes/footer.php'; ?>
