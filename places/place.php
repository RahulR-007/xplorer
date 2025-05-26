<?php
$conn = new mysqli("localhost", "root", "", "xplorer_db");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$place = $_GET['place'] ?? '';

$stmt = $conn->prepare("SELECT * FROM places WHERE place_name = ?");
$stmt->bind_param("s", $place);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
  echo "Place not found!";
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($data['place_name']) ?> - X-Plorer</title>
  <link rel="stylesheet" href="/dashboard/xplorer/assets/css/style.css" />
  <link rel="stylesheet" href="/dashboard/xplorer/assets/css/chatbot.css" />
  <link rel="stylesheet" href="/dashboard/xplorer/assets/css/places.css" />
  <style>
    .model-tooltip {
      position: absolute;
      z-index: 100;
      background: rgba(30,30,30,0.95);
      color: #fff;
      padding: 12px 18px;
      border-radius: 8px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.4);
      pointer-events: none;
      font-size: 1em;
      display: none;
      max-width: 250px;
      transition: opacity 0.2s;
    }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="logo">X-Plorer</div>
    <ul class="nav-links">
      <li><a href="/dashboard/xplorer/index.html">Home</a></li>
      <li><a href="/dashboard/xplorer/visit/visit.php" class="active">Visit</a></li>
    </ul>
  </nav>

  <main>
  <section class="hero">
  <img src="<?= htmlspecialchars($data['cover_image']) ?>" alt="<?= htmlspecialchars($data['place_name']) ?>">
  <div class="hero-overlay">
    <h1 class="place-title"><?= htmlspecialchars($data['place_name']) ?></h1>
    <div class="hero-buttons">
      <button onclick="scrollToSection('explore')">3D Explore</button>
      <button onclick="scrollToSection('review')">Review</button>
      <button onclick="scrollToSection('location')">Location</button>
    </div>
  </div>
</section>


    <section id="explore" class="section" style="position:relative;">
      <h2>3D Explore</h2>
      <div style="position:relative;display:flex;justify-content:center;">
        <model-viewer 
          id="main-model"
          src="<?= htmlspecialchars($data['model_file']) ?>" 
          auto-rotate 
          camera-controls 
          shadow-intensity="1"
          style="width: 50vw; height: 500px; background: #111; border-radius: 12px;"
          exposure="1"
        ></model-viewer>
        <div id="model-tooltip" class="model-tooltip"></div>
      </div>
      <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/4.0.0/model-viewer.min.js"></script>
      <script src="/dashboard/xplorer/assets/js/places.js"></script>
    </section>

    <section id="review" class="section">            
      <div class="description-container">
        <h2>Description and Review</h2>
        <p><strong>📍 Location:</strong></p>
        <p><?= nl2br(htmlspecialchars($data['location_desc'])) ?></p>
        <p><strong>🏛️ Historical Significance:</strong></p>
        <p><?= nl2br(htmlspecialchars($data['history_desc'])) ?></p>
        <p><strong>🏗️ Architectural Marvel:</strong></p>
        <ul>
          <?php foreach (explode(',', $data['architecture_desc']) as $line): ?>
            <li><?= htmlspecialchars(trim($line)) ?></li>
          <?php endforeach; ?>
        </ul>
        <p><strong>🌍 Cultural & Touristic Importance:</strong></p>
        <ul>
          <?php foreach (explode(',', $data['culture_desc']) as $line): ?>
            <li><?= htmlspecialchars(trim($line)) ?></li>
          <?php endforeach; ?>
        </ul>
        <p><strong>⭐ Fun Facts:</strong></p>
        <ul>
          <?php foreach (explode(',', $data['fun_facts']) as $line): ?>
            <li>✅ <?= htmlspecialchars(trim($line)) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php if (!empty($data['place_link'])): ?>
      <div class="google-reviews" style="display: flex; justify-content: center; margin-top: 60px;">
        <a href="<?= htmlspecialchars($data['place_link']) ?>" target="_blank">
          <img src="<?= htmlspecialchars($data['rating_image']) ?>" alt="Reviews" style="width: 300px; height: 200px; border-radius: 10px; box-shadow: 0 4px 10px rgba(255, 255, 255, 0.2);">
        </a>
      </div>
      <?php endif; ?>
    </section>

    <section id="location" class="section">
      <h2>Location</h2>
      <div style="display: flex; justify-content: center; align-items: center; width: 100%; padding: 40px 0;">
        <div id="embed-map-canvas" style="width: 900px; height: 400px; max-width: 90%; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(255, 255, 255, 0.2);">
          <iframe 
            style="height: 100%; width: 100%; border: 0; border-radius: 10px;" 
            frameborder="0" 
            allowfullscreen 
            src="<?= htmlspecialchars($data['map_embed']) ?>">
          </iframe>
        </div>
      </div>
    </section>
  </main>
  <script data-id="cmaggfope000dk10bq94oyw86" src="https://tinychat.ai/tinychat.js" async defer></script>
  <footer>
    <p>&copy; 2025 X-Plorer | Explore the world like never before</p>
  </footer>
  <script>
    function scrollToSection(id) {
      const section = document.getElementById(id);
      if (section) {
        section.scrollIntoView({ behavior: 'smooth' });
      }
    }
  </script>
  <script data-id="cmaggfope000dk10bq94oyw86" src="https://tinychat.ai/tinychat.js" async defer></script>
</body>
</html>
