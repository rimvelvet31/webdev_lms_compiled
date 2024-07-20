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
  <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">-->

  <link href="styles/styles.css" rel="stylesheet">
</head>

<body>
  <div id="drawer">
    <div class="close-button">
      <img src="assets/close.png" />
      <div>
        <span>PUP eMabini</span>
      </div>
    </div>
    <div>
      <a>Home</a>
    </div>
    <div>
      <a>Dashboard</a>
    </div>
    <div>
      <a>My Courses</a>
    </div>
    <div>
      <a style="text-decoration: none; color: black;" href="../PROFESSOR/index.php">Assessment</a>
    </div>
  </div>
  <header>
    <div class="top-bar">
      <div class="toggle-hamburger">
        <img src="assets/hamburger.png" alt="PUP">
      </div>
      <div class="logo">
        <img src="assets/logo.png" alt="PUP">
      </div>
      <nav class="main-nav">
        <ul>
          <li><a href="#">Home</a></li>
          <li><a href="#">Dashboard</a></li>
          <li class="toggle-nav"><a href="#">My courses</a></li>
          <li class="toggle-nav"><a href="../PROFESSOR/index.php">Assessment</a></li>
          <li class="more-nav-white">
            <a id="more-white" class="more">More</a>
            <ul class="dropdown-menu-white">
              <li><a href="#">My Courses</a></li>
              <li><a href="../PROFESSOR/index.php">Assessment</a></li>
            </ul>
          </li>
        </ul>
      </nav>
      <nav class="profile-nav">
        <ul>
          <li><a href="#"><img src="assets/bell.png" /></a></li>
          <li><a href="#"><img src="assets/chat.png" /></a></li>
          <li>
            <a id="profile" href="#"><img src="assets/profile-picture.png" /></a>
            <ul class="dropdown-menu-profile">
              <li><a href="#">Accessibility</a></li>
              <div class="underline"></div>
              <li><a href="#">Profile</a></li>
              <li><a href="#">Grades</a></li>
              <li><a href="#">Calendar</a></li>
              <li><a href="#">Messages</a></li>
              <li><a href="#">Private files</a></li>
              <li><a href="#">Reports</a></li>
              <div class="underline"></div>
              <li><a href="#">Preferences</a></li>
              <div class="underline"></div>
              <li><a href="#">Log out</a></li>
            </ul>
          </li>
        </ul>
      </nav>
    </div>
    <div class="bottom-bar">
      <ul>
        <li><a href="#">Course</a></li>
        <li class="toggle-nav-red"><a href="#">Lectures</a></li>
        <li class="toggle-nav-red"><a href="#">Activities</a></li>
        <li class="toggle-nav-red"><a href="#">Interactive Video</a></li>
        <li class="more-nav-red">
          <a id="more-red" class="more_red">More</a>
          <ul class="dropdown-menu-red">
            <li><a href="#">Lectures</a></li>
            <li><a href="#">Activities</a></li>
            <li><a href="#">Interactive Video</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </header>
  <div class="content-body">
    <div class="body-container">
      <div class="content-block">
        <h1><?php echo $video_title ?></h1>
        <div class="underline"></div>
        <div class="video-container">
          <video id="videoPlayer" src="<?php echo $video_path ?>" controls></video>
        </div>

        <a class="button-type-1" href="create_assessment.html" id="assessmentButton">Create Assessment at...</a>
      </div>

      <div>
        <?php if (!empty($assessments)): ?>
          <h2>Assessments</h2>
        <?php endif; ?>

        <?php foreach ($assessments as $assessment): ?>
          <div class="card" style="width: 800">
            <div class="card-body">
              <h5 class="card-title">
                Assessment at <?php echo $assessment['timestamp'] ?>
              </h5>
              <a href="edit_assessment.php?video=<?php echo $video_id ?>&assessment_id=<?php echo $assessment['id'] ?>"
                class="btn btn-primary">Edit</a>
              <a href="./includes/delete_assessment.php?video=<?php echo $video_id ?>&assessment_id=<?php echo $assessment['id'] ?>"
                class=" btn btn-danger" onclick="return confirm('Are you sure you want to delete this assessment?');">
                Delete
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <a class="btn btn-primary" href="index.php">Go Back Home</a>
    </div>
  </div>




  <script src="controller/video_assessments.js"></script>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>