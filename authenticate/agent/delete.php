<?php
session_start();

// Check if agent is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../signin.php");
    exit();
}

$agentEmail = $_SESSION['email'];

// Validate input
if (!isset($_GET['place'])) {
    echo "<script>alert('❌ Invalid request: No place specified.'); window.location.href = 'agent.php';</script>";
    exit();
}

$placeName = $_GET['place'];

// DB Connection
$conn = new mysqli("localhost", "root", "", "xplorer_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if place exists and is owned by the current agent
$checkStmt = $conn->prepare("SELECT * FROM places WHERE place_name = ? AND email = ?");
$checkStmt->bind_param("ss", $placeName, $agentEmail);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('❌ You do not have permission to delete this place or it does not exist.'); window.location.href = 'agent.php';</script>";
    $checkStmt->close();
    $conn->close();
    exit();
}
$checkStmt->close();

// Delete the place
$deleteStmt = $conn->prepare("DELETE FROM places WHERE place_name = ? AND email = ?");
$deleteStmt->bind_param("ss", $placeName, $agentEmail);

if ($deleteStmt->execute()) {
    echo "<script>alert('✅ Place deleted successfully!'); window.location.href = 'agent.php';</script>";
} else {
    echo "<script>alert('❌ Error deleting place: " . $deleteStmt->error . "'); window.location.href = 'agent.php';</script>";
}

$deleteStmt->close();
$conn->close();
?>
