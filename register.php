
<?php
session_start();
include "config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($fullname == "" || $email == "" || $password == "") {
        $message = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $message = "Password must have at least 6 characters.";
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE email=?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Email already registered.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO users(fullname,email,password)
                 VALUES(?,?,?)"
            );

            $stmt->bind_param("sss", $fullname, $email, $hashed);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit;
            } else {
                $message = "Registration failed.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>CodeGen AI | Register</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="auth-box">
<h1>CodeGen AI</h1>
<h3>Create Your Account</h3>

<p class="message">
<?php echo htmlspecialchars($message); ?>
</p>

<form method="POST">
<input type="text" name="fullname"
placeholder="Full Name" required>

<input type="email" name="email"
placeholder="Email Address" required>

<input type="password" name="password"
placeholder="Password (minimum 6 characters)" required>

<button type="submit">Register</button>
</form>

<p>Already registered?
<a href="login.php">Login here</a></p>
</div>

</body>
</html>
