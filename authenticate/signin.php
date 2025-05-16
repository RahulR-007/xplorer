<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = ''; // Default XAMPP password
$db = 'xplorer_db'; // Your DB name

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    // Fetch user
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // User found
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['email'] = $email;
            header("Location: ./agent/agent.php");
            exit();
        } else {
            $error = "❌ Incorrect password.";
        }
    } else {
        $error = "⚠️ Email not registered.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign In - X-plorer</title>
  <link rel="stylesheet" href="../assets/css/authenticate.css">
  <link rel="stylesheet" href="../assets/css/chatbot.css">
</head>
<body>

<header>
  <nav>
    <div class="logo">X-plorer</div>
    <ul class="nav-links">
      <li><a href="../index.html">Home</a></li>
      <li><a href="signup.php">Sign Up</a></li>
      <li><a href="../pages/contact.html">Contact</a></li>
    </ul>
    <div class="menu-toggle">&#9776;</div>
  </nav>
</header>

<div class="container">
  <form method="POST" action="signin.php">
    <p>Welcome Back</p>
    <input type="email" name="email" placeholder="Email" required /><br />
    <input type="password" name="password" placeholder="Password" required /><br />
    <button type="submit">Sign In</button><br />
    <a href="signup.php">New? Create New Account</a>
  </form>
  <?php if (!empty($error)) echo "<div class='error-message'>$error</div>"; ?>
</div>

</body>
<script data-id="cmaggfope000dk10bq94oyw86" src="https://tinychat.ai/tinychat.js" async defer></script>
</html>
