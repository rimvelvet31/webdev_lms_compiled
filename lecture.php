<?php
include 'includes/dbh_inc.php';

// Ensure a video parameter is provided
if (!isset($_GET['video'])) {
  die('No video specified.');
}

// Get the video ID from the query parameter and sanitize it
$video_id = $_GET['video'];

// Query to fetch video details based on video_ID
$sql = "SELECT * FROM interactive_video_video WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $video_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if video exists
if ($result->num_rows == 0) {
  die('Video not found.');
}

// Fetch video details
$video = $result->fetch_assoc();
$video_title = htmlspecialchars($video['video_title']);
$description = htmlspecialchars($video['description']);
$video_path = htmlspecialchars($video['video_path']);
$video_extension = pathinfo($video_path, PATHINFO_EXTENSION);

// Close statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="styles/lecture.css" />
  <!-- <link rel="stylesheet" href="styles/progress.css" /> -->
  <!-- box icons -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <title><?php echo $video_title; ?></title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
  <section>
    <h1><?php echo $video_title; ?></h1>

    <div class="video-player">
      <video src="<?php echo $video_path; ?>" type="video/<?php echo $video_extension; ?>" id="video" class="screen">
      </video>

      <div class="controls">
        <button id="control-btn">
          <i class="bx bx-play bx-md"></i>
        </button>
        <div id="track">
          <input type="range" id="progress" class="progress" min="0" max="100" step="0.01" value="0" />
        </div>
        <button id="summary-btn">
          <i class="bx bx-list-ul bx-sm"></i>
        </button>
        <div class="time"><span id="current-time">00:00</span> / <span id="duration">00:00</span></div>
      </div>

      <div id="question-dialog" data-open="false">
        <div class="overlay"></div>
        <div class="content"></div>
      </div>
    </div>
  </section>

  <section>
    <h2 style="font-weight: bold">Description:</h2>
    <p><?php echo $description; ?></p>
  </section>

  <script src="controller/lecture.js"></script>
</body>

</html>