<?php
session_start();
if (!isset($_SESSION['email'])) {
    die("Unauthorized access");
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "xplorer_db";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];
$originalName = $_POST['originalName'];
$place_name = trim($_POST['placeName']);
$location_desc = trim($_POST['locationDesc']);
$history_desc = trim($_POST['historyDesc']);
$architecture_desc = trim($_POST['architectureDesc']);
$culture_desc = trim($_POST['cultureDesc']);
$fun_facts = trim($_POST['funFacts']);
$map_embed = trim($_POST['mapEmbed']);
$place_link = trim($_POST['placeLink']);
$place_keywords = trim($_POST['placeKeywords']);
$country = trim($_POST['country']);
$category = trim($_POST['category']);
$rating = intval($_POST['rating']);

$uploadDir = dirname(__DIR__, 2) . "/uploads/";
$publicPath = "/dashboard/xplorer/uploads/";
$maxSize = 5 * 1024 * 1024; // 5 MB

function validateFileSize($file, $maxSize, $fieldName) {
    if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
        if ($file['size'] > $maxSize) {
            echo "<script>alert('❌ $fieldName exceeds the 5MB size limit. Please upload a smaller file.'); window.history.back();</script>";
            exit();
        }
    }
}

function saveFileIfUploaded($field, $uploadDir, $publicPath) {
    if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
        $fileName = uniqid() . "_" . basename($_FILES[$field]["name"]);
        $targetPath = $uploadDir . $fileName;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        if (move_uploaded_file($_FILES[$field]["tmp_name"], $targetPath)) {
            return $publicPath . $fileName;
        }
    }
    return null;
}

validateFileSize($_FILES['coverImage'], $maxSize, "Cover Image");
validateFileSize($_FILES['cardImage'], $maxSize, "Card Image");
validateFileSize($_FILES['ratingImage'], $maxSize, "Rating Image");

$coverImage = saveFileIfUploaded('coverImage', $uploadDir, $publicPath);
$cardImage = saveFileIfUploaded('cardImage', $uploadDir, $publicPath);
$ratingImage = saveFileIfUploaded('ratingImage', $uploadDir, $publicPath);
$modelUpload = saveFileIfUploaded('modelUpload', $uploadDir, $publicPath);

$sql = "UPDATE places SET 
    location_desc = ?, history_desc = ?, architecture_desc = ?, culture_desc = ?,
    fun_facts = ?, map_embed = ?, place_link = ?, place_keywords = ?, 
    country = ?, category = ?, rating = ?";

$params = [
    $location_desc, $history_desc, $architecture_desc, $culture_desc,
    $fun_facts, $map_embed, $place_link, $place_keywords,
    $country, $category, $rating
];
$types = "ssssssssssi";

if ($coverImage !== null) {
    $sql .= ", cover_image = ?";
    $params[] = $coverImage;
    $types .= "s";
}
if ($cardImage !== null) {
    $sql .= ", card_image = ?";
    $params[] = $cardImage;
    $types .= "s";
}
if ($ratingImage !== null) {
    $sql .= ", rating_image = ?";
    $params[] = $ratingImage;
    $types .= "s";
}
if ($modelUpload !== null) {
    $sql .= ", model_file = ?"; 
    $params[] = $modelUpload;
    $types .= "s";
}

$sql .= " WHERE place_name = ? AND email = ?";
$params[] = $originalName;
$params[] = $email;
$types .= "ss";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo "<script>alert('✅ Place updated successfully!'); window.location.href='edit.php?place=" . urlencode($place_name) . "';</script>";
} else {
    echo "<script>alert('❌ Failed to update place: " . $stmt->error . "'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
