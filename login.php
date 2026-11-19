
<?php
session_start();
include "config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT id, fullname, password FROM users WHERE email=?"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["fullname"] = $user["fullname"];

            header("Location: dashboard.php");
            exit;

        } else {
            $message = "Incorrect password.";
        }
    } else {
        $message = "Account not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>CodeGen AI | Login</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="auth-box">
<h1>CodeGen AI</h1>
<h3>Welcome Back!</h3>

<p class="message">
<?php echo htmlspecialchars($message); ?>
</p>

<form method="POST">
<input type="email" name="email"
placeholder="Email Address" required>

<input type="password" name="password"
placeholder="Password" required>

<button type="submit">Login</button>
</form>

<p>Don't have an account?
<a href="register.php">Register</a></p>
</div>

</body>
</html>
