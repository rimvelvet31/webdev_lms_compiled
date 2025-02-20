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
  <!-- box icons -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <title><?php echo $video_title; ?></title>

  <!-- Bootstrap -->
  <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">-->
  <link href="styles/styles.css" rel="stylesheet">
</head>

<body class="max-height no-scroll">
  <!-- Header section containing the navigation bars -->
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
      <a style="text-decoration: none; color: black;">Assessment</a>
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
        <li class="toggle-nav-red current"><a href="index.php">Interactive Video</a></li>
        <li class="more-nav-red">
          <a id="more-red" class="more_red">More</a>
          <ul class="dropdown-menu-red">
            <li><a href="#">Lectures</a></li>
            <li><a href="#">Activities</a></li>
            <li><a href="index.php">Interactive Video</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </header>

  <div class="content-body">
    <div class="body-container w85 video">
      <section class="content-block video">
        <div class="title-with-buttons">
          <h1><?php echo $video_title; ?></h1>
          <div class="title-buttons">
            <button class="button-type-1 create_assessment" onclick="location.href='index.php'">Go Back Home</button>
          </div>
        </div>

        <div class="underline bottom-margin"></div>

        <div class="video-player">
          <video src="<?php echo $video_path; ?>" type="video/<?php echo $video_extension; ?>" id="video"
            class="screen">
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

      <section class="content-block assessments lecture">
        <h1 style="font-weight: bold">Description:</h1>
        <div class="underline bottom-margin"></div>
        <div class="scrollable">
          <p><?php echo $description; ?></p>
        </div>
      </section>
    </div>
  </div>

  <script src="controller/lecture.js"></script>
</body>

</html>