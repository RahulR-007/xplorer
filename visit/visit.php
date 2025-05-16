<?php
$conn = new mysqli("localhost", "root", "", "xplorer_db");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$searchTerm = $_GET['search'] ?? '';
$location = $_GET['location'] ?? '';
$category = $_GET['category'] ?? '';
$rating = $_GET['rating'] ?? '';

$query = "SELECT place_name, card_image FROM places WHERE 
          (place_name LIKE CONCAT('%', ?, '%') OR place_keywords LIKE CONCAT('%', ?, '%'))";

$params = [$searchTerm, $searchTerm];
$types = "ss";

if (!empty($location)) {
  $query .= " AND location_desc LIKE CONCAT('%', ?, '%')";
  $params[] = $location;
  $types .= "s";
}

if (!empty($category)) {
  $query .= " AND category LIKE CONCAT('%', ?, '%')";
  $params[] = $category;
  $types .= "s";
}

if (!empty($rating)) {
  $query .= " AND rating = ?";
  $params[] = $rating;
  $types .= "i"; // Rating is an integer, so use 'i' here
}

$query .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>X-Plorer | Visit Places</title>
  <link rel="stylesheet" href="../assets/css/style.css" />
  <link rel="stylesheet" href="../assets/css/chatbot.css" />
  <link rel="stylesheet" href="../assets/css/visit.css" />
</head>
<body>
  <header>
    <nav>
      <div class="logo">X-Plorer</div>
      <ul class="nav-links">
        <li><a href="../index.html">Home</a></li>
        <li><a href="../pages/about.html">About</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <section class="visit-section">
      <h1>Explore the Wonders of the World in 3D</h1>
      <p>Step into history and experience breathtaking monuments in immersive 3D.</p>

      <!-- 🔍 Search and Filters -->
      <form method="GET" action="visit.php" style="margin: 20px auto; max-width: 700px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
        <input type="text" name="search" placeholder="Search by place name or keyword..." value="<?= htmlspecialchars($searchTerm) ?>" style="padding: 10px; width: 60%; border-radius: 10px; border: none;">

        <select name="location" style="padding: 10px; border-radius: 10px;">
          <option value="">All Locations</option>
          <option value="India" <?= $location === 'India' ? 'selected' : '' ?>>India</option>
          <option value="Egypt" <?= $location === 'Egypt' ? 'selected' : '' ?>>Egypt</option>
          <option value="Italy" <?= $location === 'Italy' ? 'selected' : '' ?>>Italy</option>
          <!-- Add more based on your data -->
        </select>

        <select name="category" style="padding: 10px; border-radius: 10px;">
          <option value="">All Categories</option>
          <option value="Temple" <?= $category === 'Temple' ? 'selected' : '' ?>>Temple</option>
          <option value="Fort" <?= $category === 'Fort' ? 'selected' : '' ?>>Fort</option>
          <option value="Palace" <?= $category === 'Palace' ? 'selected' : '' ?>>Palace</option>
          <!-- Add more -->
        </select>

        <select name="rating" style="padding: 10px; border-radius: 10px;">
          <option value="">All Ratings</option>
          <option value="5" <?= $rating === '5' ? 'selected' : '' ?>>⭐⭐⭐⭐⭐</option>
          <option value="4" <?= $rating === '4' ? 'selected' : '' ?>>⭐⭐⭐⭐</option>
          <option value="3" <?= $rating === '3' ? 'selected' : '' ?>>⭐⭐⭐</option>
          <option value="3" <?= $rating === '2' ? 'selected' : '' ?>>⭐⭐</option>
          <option value="3" <?= $rating === '1' ? 'selected' : '' ?>>⭐</option>
          
        </select>

        <button type="submit" style="padding: 10px 15px; background: #3a3a3a; color: white; border-radius: 10px; border: none;">Search</button>
      </form>

      <?php if (empty(trim($searchTerm)) && empty($location) && empty($category) && empty($rating)): ?>
        <p style="color: lightgray;">Showing all places. Use the search bar or filters to narrow down results.</p>
      <?php endif; ?>

      <!-- 📍 Results -->
      <div class="places-container">
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <a href="/dashboard/xplorer/places/place.php?place=<?= urlencode($row['place_name']) ?>" class="place-card">
              <img src="<?= htmlspecialchars($row['card_image']) ?>" alt="<?= htmlspecialchars($row['place_name']) ?>">
              <div class="place-info"><?= htmlspecialchars($row['place_name']) ?></div>
            </a>
          <?php endwhile; ?>
        <?php else: ?>
          <p style="color: white;">No results found. Try changing search or filter options.</p>
        <?php endif; ?>
      </div>
    </section>
  </main>
  <script data-id="cmaggfope000dk10bq94oyw86" src="https://tinychat.ai/tinychat.js" async defer></script>
  <footer>
    <p>&copy; 2025 X-Plorer | Explore the world like never before</p>
  </footer>
</body>
</html>
