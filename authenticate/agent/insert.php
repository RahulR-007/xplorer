<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../signin.php");
    exit();
}

// Fetch previously entered data
function getVal($field) {
    return isset($_SESSION['form_data'][$field]) ? htmlspecialchars($_SESSION['form_data'][$field]) : '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <h2>Add New Location</h2>

    <form id="uploadForm" method="POST" action="upload.php" enctype="multipart/form-data">
        <label>Place Name:</label>
        <input type="text" name="placeName" value="<?= getVal('placeName') ?>" required>

        <label>Place Keywords (Comma-separated):</label>
        <input type="text" name="placeKeywords" value="<?= getVal('placeKeywords') ?>" required>

        <label>📍 Location:</label>
        <textarea name="locationDesc" rows="2" required><?= getVal('locationDesc') ?></textarea>

        <label>🏛️ Historical Significance:</label>
        <textarea name="historyDesc" rows="3" required><?= getVal('historyDesc') ?></textarea>

        <label>🏗️ Architectural Marvel:</label>
        <textarea name="architectureDesc" rows="3" required><?= getVal('architectureDesc') ?></textarea>

        <label>🌍 Cultural & Touristic Importance:</label>
        <textarea name="cultureDesc" rows="3" required><?= getVal('cultureDesc') ?></textarea>

        <label>⭐ Fun Facts (Comma-separated):</label>
        <input type="text" name="funFacts" value="<?= getVal('funFacts') ?>" required>

        <label>Cover Image:</label>
        <input type="file" name="coverImage" accept="image/*" required>

        <label>Card Image (Visit Page):</label>
        <input type="file" name="cardImage" accept="image/*" required>

        <label>Rating Image (Optional):</label>
        <input type="file" name="ratingImage" accept="image/*">

        <label>Upload 3D Model (GLB):</label>
        <input type="file" name="modelUpload" accept=".glb" required>

        <label>Google Map Embed Link:</label>
        <input type="text" name="mapEmbed" value="<?= getVal('mapEmbed') ?>" required>

        <label>Website to Book / External Link:</label>
        <input type="text" name="placeLink" value="<?= getVal('placeLink') ?>" required>

        <label>🌎 Country:</label>
        <input type="text" name="country" value="<?= getVal('country') ?>" required>

        <label>📂 Category:</label>
        <input type="text" name="category" value="<?= getVal('category') ?>" required>

        <label>⭐ Rating (1 to 5):</label>
        <input type="number" name="rating" min="1" max="5" value="<?= getVal('rating') ?>" required>

        <button type="submit">Upload & Save Place</button>
    </form>

    <?php
    if (isset($_SESSION['error_message'])) {
        echo "<script>alert('{$_SESSION['error_message']}');</script>";
        unset($_SESSION['error_message']);
    }
    unset($_SESSION['form_data']);
    ?>
</main>

<!-- Loading Spinner Overlay -->
<div id="loadingOverlay">
  <div class="spinner"></div>
  <p style="margin-top: 20px;">Uploading... Please wait</p>
</div>

</body>
</html>
