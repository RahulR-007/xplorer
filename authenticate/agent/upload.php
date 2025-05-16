<?php
session_start();

if (!isset($_SESSION['email'])) {
    die("<script>alert('Session expired. Please log in again.'); window.location.href='../signin.php';</script>");
}

$email = $_SESSION['email'];

$conn = new mysqli("localhost", "root", "", "xplorer_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// ✅ Input sanitizer
function cleanInput($data) {
    return trim(strip_tags($data));
}

// ✅ Save all inputs
$inputs = [
    'placeName', 'placeKeywords', 'locationDesc', 'historyDesc', 'architectureDesc',
    'cultureDesc', 'funFacts', 'mapEmbed', 'placeLink', 'country', 'category', 'rating'
];
$form_data = [];
foreach ($inputs as $field) {
    $form_data[$field] = cleanInput($_POST[$field]);
}
$_SESSION['form_data'] = $form_data;

extract($form_data);
$rating = (int)$rating;

// ✅ File Uploads
$uploadDir  = dirname(__DIR__, 2) . "/uploads/";
$publicPath = "/dashboard/xplorer/uploads/";

// ✅ Improved file upload handler
function saveFile($field, $uploadDir, $publicPath, $allowedTypes, $strictMime = true) {
    $originalName = $_FILES[$field]["name"];
    $fileTmpPath  = $_FILES[$field]["tmp_name"];
    $fileType     = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    $validMimeTypes = [
        'jpg'  => ['image/jpeg', 'image/pjpeg'],
        'jpeg' => ['image/jpeg', 'image/pjpeg'],
        'png'  => ['image/png'],
        'glb'  => ['model/gltf-binary', 'application/octet-stream'],
    ];
    $mimeType = mime_content_type($fileTmpPath);

    if (!in_array($fileType, $allowedTypes)) {
        die("<script>alert('⚠️ Invalid file extension for $field. Only " . implode(', ', $allowedTypes) . " allowed.'); window.location.href='insert.php';</script>");
    }

    if ($strictMime && isset($validMimeTypes[$fileType]) && !in_array($mimeType, $validMimeTypes[$fileType])) {
        echo "<script>console.log('MIME mismatch for $field: Got $mimeType');</script>";
        die("<script>alert('⚠️ MIME type mismatch for $field. Expected: " . implode(', ', $validMimeTypes[$fileType]) . ", Got: $mimeType'); window.location.href='insert.php';</script>");
    }

    $fileName   = time() . "_" . basename($originalName);
    $targetPath = $uploadDir . $fileName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($fileTmpPath, $targetPath)) {
        return $publicPath . $fileName;
    } else {
        die("<script>alert('⚠️ Failed to upload file: $fileName'); window.location.href='insert.php';</script>");
    }
}

// ✅ Upload and validate files
$cover_image  = saveFile("coverImage", $uploadDir, $publicPath, ['jpg', 'jpeg', 'png']);
$card_image   = saveFile("cardImage", $uploadDir, $publicPath, ['jpg', 'jpeg', 'png']);
$model_file   = saveFile("modelUpload", $uploadDir, $publicPath, ['glb'], false); // Allow glb fallback

$rating_image = '';
if (!empty($_FILES['ratingImage']['name'])) {
    $rating_image = saveFile("ratingImage", $uploadDir, $publicPath, ['jpg', 'jpeg', 'png']);
}

// ✅ Insert into DB
try {
    $stmt = $conn->prepare("INSERT INTO places (
        place_name, location_desc, history_desc, architecture_desc, culture_desc,
        fun_facts, map_embed, cover_image, card_image, rating_image,
        model_file, place_link, place_keywords, email,
        country, category, rating
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssssssssssssssssi",
        $placeName, $locationDesc, $historyDesc, $architectureDesc,
        $cultureDesc, $funFacts, $mapEmbed, $cover_image,
        $card_image, $rating_image, $model_file, $placeLink,
        $placeKeywords, $email, $country, $category, $rating
    );

    $stmt->execute();

    unset($_SESSION['form_data']);

    echo "<script>
        setTimeout(function() {
            alert('✅ Place uploaded successfully!');
            window.location.href='agent.php';
        }, 2000);
    </script>";

} catch (mysqli_sql_exception $e) {
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        $_SESSION['error_message'] = "❌ This place name already exists.";
    } else {
        $_SESSION['error_message'] = "❌ Upload failed: " . $e->getMessage();
    }

    echo "<script>
        setTimeout(function() {
            window.location.href='insert.php';
        }, 2000);
    </script>";
}

$stmt->close();
$conn->close();
?>
