
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Blog</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

    <link rel="stylesheet" href="style.css">
    

</head>
<?php
require 'db.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if ($username === '' || $password === '') {
        $_SESSION['flash'] = "Please fill both fields.";
        header("Location: register.php");
        exit;
    }

    if (strlen($username) < 3) {
        $_SESSION['flash'] = "Username must be at least 3 characters.";
        header("Location: register.php");
        exit;
    }

    if (strlen($password) < 6) {
        $_SESSION['flash'] = "Password must be at least 6 characters.";
        header("Location: register.php");
        exit;
    }

    // check existing user
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['flash'] = "Username already exists.";
        $stmt->close();
        header("Location: register.php");
        exit;
    }
    $stmt->close();

    // insert with hashed password and default role
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $role = "user"; // default role

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sss", $username, $hash, $role);
    $stmt->execute();
    $stmt->close();

    $_SESSION['flash'] = "Registration successful. Please login.";
    header("Location: login.php");
    exit;
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-10">
            <h2 class="text-center text-white bg-info mt-5 mb-3">Register</h2>
            <form method="post" class="border p-5 rounded shadow bg-light">
                <div class="mb-4">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" required minlength="3">
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required minlength="6">
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
