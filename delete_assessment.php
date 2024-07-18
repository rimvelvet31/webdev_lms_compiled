<?php
session_start();
include 'includes/dbh_inc.php';

// Check if user is an admin
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
  header("Location: index.php");
  exit();
}

// Get video ID from the URL
$video_id = isset($_GET['video']) ? intval($_GET['video']) : 0;

// Fetch assessment details
$assessment_id = isset($_GET['assessment_id']) ? intval($_GET['assessment_id']) : 0;

// Delete assessment from database
$stmt = $mysqli->prepare("DELETE FROM interactive_video_assessment WHERE id = ?");
$stmt->bind_param("i", $assessment_id);

if ($stmt->execute()) {
  echo "<div class='alert alert-success'>Assessment deleted successfully.</div>";
  header("Location: video_assessments.php?video=$video_id");
  exit();
} else {
  echo "<div class='alert alert-danger'>Error deleting assessment: " . $stmt->error . "</div>";
}

$stmt->close();
