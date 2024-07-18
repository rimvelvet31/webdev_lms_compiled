<?php
session_start();
include 'includes/dbh_inc.php';

// Check if user is an admin
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
  header("Location: index.php");
  exit();
}

// Fetch video information
if (isset($_GET['video'])) {
  $video_id = $_GET['video'];
  $stmt = $mysqli->prepare("SELECT id, video_title, video_path FROM interactive_video_video WHERE id = ?");
  $stmt->bind_param("i", $video_id);
  $stmt->execute();
  $stmt->bind_result($video_id, $video_title, $video_path);
  $stmt->fetch();
  $stmt->close();
} else {
  echo "<div class='alert alert-danger'>Invalid video ID.</div>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $video_title ?></title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="container">
  <h1><?php echo $video_title ?></h1>

  <div class="mb-3">
    <video id="videoPlayer" src="<?php echo $video_path ?>" width="800" controls></video>
  </div>

  <a href="create_assessment.html" id="assessmentButton" class="btn btn-primary">Create Assessment at...</a>

  <script>
    document.getElementById('videoPlayer').addEventListener('timeupdate', function(event) {
      updateTimestampButton(event.target.currentTime);
    });

    document.getElementById('videoPlayer').addEventListener('click', function(event) {
      const video = event.target;
      const duration = video.duration;
      const progressBar = event.target;

      const rect = progressBar.getBoundingClientRect();
      const clickX = event.clientX - rect.left;

      const clickPositionPercentage = clickX / rect.width;
      const clickTime = duration * clickPositionPercentage;

      video.currentTime = clickTime;

      updateTimestampButton(clickTime);
    });

    function updateTimestampButton(currentTime) {
      currentTime = Math.floor(currentTime);

      const hours = Math.floor(currentTime / 3600);
      const minutes = Math.floor((currentTime % 3600) / 60);
      const seconds = Math.floor(currentTime % 60);

      let timestamp;
      if (hours > 0) {
        timestamp = `${hours}:${minutes < 10 ? '0' : ''}${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
      } else {
        timestamp = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
      }

      document.getElementById('assessmentButton').textContent = `Create Assessment at ${timestamp}`;

      const assessmentButton = document.getElementById('assessmentButton');
      assessmentButton.textContent = `Create Assessment at ${timestamp}`;
      assessmentButton.href = `create_assessment.html?video=<?php echo $video_id; ?>&timestamp=${currentTime}`;
    }
  </script>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>