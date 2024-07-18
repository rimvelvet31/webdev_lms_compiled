<?php
session_start();
include 'includes/dbh_inc.php';

// Check if user is an admin
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
  header("Location: index.php");
  exit();
}

if (isset($_GET['video'])) {
  $video_id = $_GET['video'];

  // Fetch video path
  $stmt = $mysqli->prepare("SELECT video_path FROM interactive_video_video WHERE id = ?");
  $stmt->bind_param("i", $video_id);
  $stmt->execute();
  $stmt->bind_result($videoPath);
  $stmt->fetch();
  $stmt->close();

  // Delete video from database
  $stmt = $mysqli->prepare("DELETE FROM interactive_video_video WHERE id = ?");
  $stmt->bind_param("i", $video_id);

  if ($stmt->execute()) {
    // Delete video file from server
    if (file_exists($videoPath)) {
      unlink($videoPath);
    }
    echo "<div class='alert alert-success'>Video deleted successfully.</div>";
    header("Location: index.php");
    exit();
  } else {
    echo "<div class='alert alert-danger'>Error deleting video: " . $stmt->error . "</div>";
  }

  $stmt->close();
} else {
  echo "<div class='alert alert-danger'>Invalid video ID.</div>";
}
