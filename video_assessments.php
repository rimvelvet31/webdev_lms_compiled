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

// Fetch assessments associated with the video
$stmt = $mysqli->prepare("SELECT id, _timestamp FROM interactive_video_assessment WHERE video_id = ?");
$stmt->bind_param("i", $video_id);
$stmt->execute();
$stmt->bind_result($assessment_id, $timestamp);
$assessments = [];
while ($stmt->fetch()) {
  $assessments[] = [
    'id' => $assessment_id,
    'timestamp' => $timestamp
  ];
}
$stmt->close();
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

  <div class="mt-3">

    <?php if (!empty($assessments)) : ?>
      <h2>Assessments</h2>
    <?php endif; ?>

    <?php foreach ($assessments as $assessment) : ?>
      <div class="card" style="width: 800">
        <div class="card-body">
          <h5 class="card-title">
            Assessment at <?php echo $assessment['timestamp'] ?>
          </h5>
          <a href="edit_assessment.php?video=<?php echo $video_id ?>&assessment_id=<?php echo $assessment['id'] ?>" class="btn btn-primary">Edit</a>
          <a href="delete_assessment.php?video=<?php echo $video_id ?>&assessment_id=<?php echo $assessment['id'] ?>" class=" btn btn-danger" onclick="return confirm('Are you sure you want to delete this assessment?');">
            Delete
          </a>
        </div>
      </div>
    <?php endforeach; ?>

    <script src="controller/video_assessments.js"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>