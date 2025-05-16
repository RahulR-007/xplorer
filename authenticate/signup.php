<?php
// signup.php

$host = 'localhost';
$user = 'root';
$password = ''; // default XAMPP password
$db = 'xplorer_db'; // your DB name

$conn = new mysqli($host, $user, $password, $db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirmPassword'];

    if ($password !== $confirm) {
        $error = "❌ Passwords do not match!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Check for duplicate email
        $check = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "⚠️ Email already exists!";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (email, name, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $name, $hashedPassword);

            if ($stmt->execute()) {
                header("Location: signin.php?signup=success");
                exit();
            } else {
                $error = "❌ Something went wrong. Try again.";
            }
        }
        $check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up - X-plorer</title>
  <link rel="stylesheet" href="../assets/css/authenticate.css">
  <link rel="stylesheet" href="../assets/css/chatbot.css">
</head>
<body>

<header>
  <nav>
    <div class="logo">X-plorer</div>
    <ul class="nav-links">
      <li><a href="../index.html">Home</a></li>
      <li><a href="signin.php">Sign In</a></li>
      <li><a href="../pages/contact.html">Contact</a></li>
    </ul>
    <div class="menu-toggle">&#9776;</div>
  </nav>
</header>

<div class="container">
<form method="POST" action="signup.php">
  <p>Create Account</p>
  <input type="text" name="username" placeholder="Full Name" required value="<?= isset($name) ? htmlspecialchars($name) : '' ?>"><br>
  <input type="email" name="email" placeholder="Email" required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>"><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <input type="password" name="confirmPassword" placeholder="Confirm Password" required><br>
  <button type="submit">Sign Up</button><br>
  <a href="signin.php">Already have an account? Sign in</a>
</form>

  <?php if (!empty($error)) echo "<p style='color:red; margin-top:10px;'>$error</p>"; ?>
</div>

</body>
<script data-id="cmaggfope000dk10bq94oyw86" src="https://tinychat.ai/tinychat.js" async defer></script>
</html>
