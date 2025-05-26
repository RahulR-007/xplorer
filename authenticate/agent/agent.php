<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../signin.php");
    exit();
}

$email = $_SESSION['email'];
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "xplorer_db";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$nameQuery = $conn->prepare("SELECT name FROM users WHERE email = ?");
$nameQuery->bind_param("s", $email);
$nameQuery->execute();
$nameResult = $nameQuery->get_result();
$nameRow = $nameResult->fetch_assoc();
$agentName = $nameRow['name'] ?? 'Agent';

$placesQuery = $conn->prepare("SELECT place_name FROM places WHERE email = ?");
$placesQuery->bind_param("s", $email);
$placesQuery->execute();
$placesResult = $placesQuery->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agent Dashboard - X-Plorer</title>
    <link rel="stylesheet" href="../../assets/css/agent.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">X-Plorer - Agent Panel</div>
    <ul class="nav-links">
        <li><a href="../../visit/visit.php">Visit</a></li>
        <li><a href="?logout=true" class="highlighted">Logout</a></li>
    </ul>
</nav>

<?php
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../signin.php");
    exit();
}
?>

<main class="container">
    <h2>Welcome, <?= htmlspecialchars($agentName) ?></h2>

    <!-- Add Place Section -->
    <section style="margin-top: 40px; text-align: center;">
        <h2>Add new Place ?</h2>
        <a href="insert.php">
            <button style="background: #22c55e; color: black; font-weight: bold; padding: 10px 30px; border-radius: 20px;">Add</button>
        </a>
    </section>

    <!-- Agent Places List -->
    <section style="margin-top: 60px;">
        <h3 style="background: #555; padding: 10px 20px; border-radius: 10px;">Your Tourists Places</h3>

        <?php if ($placesResult->num_rows > 0): ?>
            <?php while ($row = $placesResult->fetch_assoc()): ?>
                <div style="background: #111; margin: 10px 0; padding: 15px 20px; border-radius: 15px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: bold;"><?= htmlspecialchars($row['place_name']) ?></span>
                    <div>
                        <a href="edit.php?place=<?= urlencode($row['place_name']) ?>">
                            <button style="background: #facc15; color: black; padding: 8px 20px; border-radius: 20px; margin-right: 10px;">Edit</button>
                        </a>
                        <a href="delete.php?place=<?= urlencode($row['place_name']) ?>" onclick="return confirm('Are you sure you want to delete this place?')">
                            <button style="background: #ef4444; color: white; padding: 8px 20px; border-radius: 20px;">Delete</button>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="margin-top: 20px;">You haven’t added any places yet.</p>
        <?php endif; ?>
    </section>
</main>

</body>
</html>
