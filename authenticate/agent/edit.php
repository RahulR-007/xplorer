<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../signin.php");
    exit();
}

$agentEmail = $_SESSION['email'];
$host = "localhost";
$user = "root";
$pass = "";
$db = "xplorer_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$place = $_GET['place'] ?? '';  
if (empty($place)) {
    header("Location: agent.php"); 
    exit();
}

$stmt = $conn->prepare("SELECT * FROM places WHERE place_name = ? AND email = ?");
$stmt->bind_param("ss", $place, $agentEmail);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    header("Location: agent.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Place - X-Plorer</title>
  <link rel="stylesheet" href="../../assets/css/agent.css">
</head>
<body>

<nav class="navbar">
  <div class="logo">X-Plorer - Edit Place</div>
  <ul class="nav-links">
    <li><a href="../../visit/visit.php">Visit</a></li>
    <li><a href="agent.php">Back to Dashboard</a></li>
  </ul>
</nav>

<main class="container">
  <h2>Edit: <?= htmlspecialchars($data['place_name']) ?></h2>
  <form action="update.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="originalName" value="<?= htmlspecialchars($data['place_name']) ?>">

    <label>Place Name</label>
    <input type="text" name="placeName" value="<?= htmlspecialchars($data['place_name']) ?>" required>

    <label>📍 Location</label>
    <textarea name="locationDesc" required><?= htmlspecialchars($data['location_desc']) ?></textarea>

    <label>🏛️ History</label>
    <textarea name="historyDesc" required><?= htmlspecialchars($data['history_desc']) ?></textarea>

    <label>🏗️ Architecture</label>
    <textarea name="architectureDesc" required><?= htmlspecialchars($data['architecture_desc']) ?></textarea>

    <label>🌍 Culture & Touristic Importance</label>
    <textarea name="cultureDesc" required><?= htmlspecialchars($data['culture_desc']) ?></textarea>

    <label>⭐ Fun Facts</label>
    <input type="text" name="funFacts" value="<?= htmlspecialchars($data['fun_facts']) ?>" required>

    <label>🔍 Keywords</label>
    <input type="text" name="placeKeywords" value="<?= htmlspecialchars($data['place_keywords']) ?>" required>

    <label>🌐 Booking/External Link</label>
    <input type="text" name="placeLink" value="<?= htmlspecialchars($data['place_link']) ?>" required>

    <label>📍 Google Map Embed (only src)</label>
    <input type="text" name="mapEmbed" value="<?= htmlspecialchars($data['map_embed']) ?>" required>

    <label>📷 Replace Cover Image (optional)</label>
    <input type="file" name="coverImage" accept="image/*">

    <label>📷 Replace Card Image (optional)</label>
    <input type="file" name="cardImage" accept="image/*">

    <label>📷 Replace Rating Image (optional)</label>
    <input type="file" name="ratingImage" accept="image/*">

    <label>🔄 Replace 3D Model (optional)</label>
    <input type="file" name="modelUpload" accept=".glb">

    <label>🌎 Country:</label>
    <input type="text" name="country" value="<?= htmlspecialchars($data['country']) ?>" required>

    <label>📂 Category:</label>
    <input type="text" name="category" value="<?= htmlspecialchars($data['category']) ?>" required>

    <label>⭐ Rating (1 to 5):</label>
    <input type="number" name="rating" min="1" max="5" value="<?= htmlspecialchars($data['rating']) ?>" required>

    <button type="submit">Update Place</button>
  </form>
</main>

</body>
</html>
