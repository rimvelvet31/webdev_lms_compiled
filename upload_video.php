<?php
session_start();
include 'includes/dbh_inc.php';

$_SESSION['user_id'] = 12345;

// Check if user is an admin
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
  header("Location: index.php");
  exit();
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_FILES['videoFile']) && $_FILES['videoFile']['error'] == 0) {
    $uploadDir = 'videos/';
    $fileName = basename($_FILES['videoFile']['name']);
    $uploadFile = $uploadDir . $fileName;

    // Check if directory exists, if not create it
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0755, true);
    }

    // Check file type
    $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
    $allowedTypes = ['mp4', 'avi', 'mov', 'wmv'];

    if (!in_array($fileType, $allowedTypes)) {
      echo "<div class='alert alert-danger'>Only video files (MP4, AVI, MOV, WMV) are allowed.</div>";
    } else {

      // Move the uploaded file to the target directory
      if (move_uploaded_file($_FILES['videoFile']['tmp_name'], $uploadFile)) {
        // File successfully uploaded, now insert into database
        $video_title = $_POST['video_title'];
        $description = $_POST['description'];

        $user_id = $_SESSION['user_id']; // Assuming user ID is stored in the session

        // Prepare and bind parameters
        $stmt = $mysqli->prepare("INSERT INTO interactive_video_video (video_title, description, video_path, user_id, date_added) 
                                VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssi", $video_title, $description, $uploadFile, $user_id);

        // Execute statement
        if ($stmt->execute()) {
          $_SESSION['upload_success'] = true;
          header("Location: index.php"); // Redirect to index.php after upload
          exit();
        } else {
          echo "<div class='alert alert-danger'>Error inserting data: " . $stmt->error . "</div>";
        }

        // Close statement
        $stmt->close();
      } else {
        echo "<div class='alert alert-danger'>Error uploading the file.</div>";
      }
    }
  } else {
    echo "<div class='alert alert-danger'>No file uploaded or an error occurred.</div>";
  }
}

// Close connection
$mysqli->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload Video</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
  <main class="container">
    <h2 class="my-3">Upload Video Lecture</h2>

    <?php if (isset($message)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="mb-3">
      <div class="mb-3">
        <label for="video_title" class="form-label">Video Title</label>
        <input type="text" class="form-control" id="video_title" name="video_title" required>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
      </div>

      <div class="mb-3">
        <label for="videoFile" class="form-label">Upload Video Lecture</label>
        <input type="file" class="form-control" id="videoFile" name="videoFile"
          accept="video/mp4, video/avi, video/mov, video/wmv" required>
      </div>

      <!-- Video Preview -->
      <div id="vid-container" style="display: none;" class="mb-3">
        <video id="videoPreview" width="800" controls></video>
      </div>

      <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    <a href="index.php" class="btn btn-secondary">Back to Home</a>
  </main>

  <script>
    document.getElementById('videoFile').addEventListener('change', function (event) {
      const file = event.target.files[0];
      if (file) {
        const videoPreview = document.getElementById('videoPreview');
        const fileURL = URL.createObjectURL(file);
        videoPreview.src = fileURL;
        document.getElementById("vid-container").style.display = 'block';
      }
    });
  </script>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>