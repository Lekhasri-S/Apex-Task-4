<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $_SESSION['flash'] = "Please fill both fields.";
        header("Location: register.php");
        exit;
    }

    // check existing user
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['flash'] = "Username already exists.";
        header("Location: register.php");
        exit;
    }
    $stmt->close();

    // insert with hashed password
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hash);
    $stmt->execute();
    $stmt->close();

    $_SESSION['flash'] = "Registration successful. Please login.";
    header("Location: login.php");
    exit;
}
?>

<h2>Register</h2>
<form method="post">
    <label>Username:<br><input type="text" name="username" required></label><br><br>
    <label>Password:<br><input type="password" name="password" required></label><br><br>
    <button type="submit">Register</button>
</form>

<?php include 'footer.php'; ?>
