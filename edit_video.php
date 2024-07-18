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

  // Fetch selected video details
  $stmt = $mysqli->prepare("SELECT video_title, description FROM interactive_video_video WHERE id = ?");
  $stmt->bind_param("i", $video_id);
  $stmt->execute();
  $stmt->bind_result($video_title, $description);
  $stmt->fetch();
  $stmt->close();

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newVideoTitle = $_POST['video_title'];
    $newDescription = $_POST['description'];

    // Update video details
    $stmt = $mysqli->prepare("UPDATE interactive_video_video SET video_title = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $newVideoTitle, $newDescription, $video_id);

    if ($stmt->execute()) {
      echo "<div class='alert alert-success'>Video details updated successfully.</div>";
      header("Location: index.php");
      exit();
    } else {
      echo "<div class='alert alert-danger'>Error updating video details: " . $stmt->error . "</div>";
    }

    $stmt->close();
  }
} else {
  echo "<div class='alert alert-danger'>Invalid video ID.</div>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Video</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
  <main class="container">
    <h2 class="my-3">Edit Video</h2>
    <form method="POST" class="mb-3">
      <div class="mb-3">
        <label for="video_title" class="form-label">Video Title</label>
        <input type="text" class="form-control" id="video_title" name="video_title" value="<?php echo htmlspecialchars($video_title); ?>" required>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($description); ?></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Save Changes</button>
      <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>