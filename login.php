<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hash);
        $stmt->fetch();
        if (password_verify($password, $hash)) {
            $_SESSION['user'] = $username;
            $_SESSION['user_id'] = $id;
            $_SESSION['flash'] = "Logged in successfully.";
            header("Location: index.php");
            exit;
        }
    }
    $_SESSION['flash'] = "Invalid username or password.";
    header("Location: login.php");
    exit;
}

?>

<h2>Login</h2>
<form method="post">
    <label>Username:<br><input type="text" name="username" required></label><br><br>
    <label>Password:<br><input type="password" name="password" required></label><br><br>
    <button type="submit">Login</button>
</form>

<?php include 'footer.php'; ?>
