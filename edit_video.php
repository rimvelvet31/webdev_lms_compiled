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
  <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">-->
  <link href="styles/styles.css" rel="stylesheet">
</head>

<body>

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

  <main class="container content-body">
    <div class="body-container w60">
      <div class="content-block">
        <h1 class="my-3">Edit Video</h1>
        <div class="underline"></div>
        <form method="POST" class="mb-3">
          <div class="mb-3 form-group">
            <div class="field-block">
              <label for="video_title" class="form-label">Video Title</label>
              <input class="textbox" type="text" class="form-control" id="video_title" name="video_title"
                value="<?php echo htmlspecialchars($video_title); ?>" required>
            </div>
          </div>
          <div class="mb-3 form-group">
            <div class="field-block">
              <label for="description" class="form-label">Description</label>
              <textarea class="textarea" class="form-control" id="description" name="description" rows="3"
                required><?php echo htmlspecialchars($description); ?></textarea>
            </div>
          </div>
          <div class="two-button-group">
            <a class="button-type-1 a" href="index.php" class="btn btn-secondary">Cancel</a>
            <button class="button-type-1" type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    //navbar
    document.getElementById("more-white").addEventListener("click", function () {
      document.getElementsByClassName("dropdown-menu-white")[0].classList.toggle("toggle-in");
    })

    //navbar
    document.getElementById("more-red").addEventListener("click", function () {
      document.getElementsByClassName("dropdown-menu-red")[0].classList.toggle("toggle-in");
    })

    //profile
    document.getElementById("profile").addEventListener("click", function () {
      document.getElementsByClassName("dropdown-menu-profile")[0].classList.toggle("toggle-in");
    })

    //drawer
    document.getElementsByClassName("toggle-hamburger")[0].addEventListener("click", function () {
      document.getElementById("drawer").classList.toggle("enter-from-left");
    })

    document.getElementsByClassName("close-button")[0].addEventListener("click", function () {
      document.getElementById("drawer").classList.toggle("enter-from-left");
    })
  </script>
</body>


</html>