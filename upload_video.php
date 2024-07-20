<?php
session_start();
include 'includes/dbh_inc.php';

$_SESSION['user_id'] = "202010839mn0";
$_SESSION['course_id'] = "BSCS";

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
        $course_id = $_SESSION['course_id']; // Assuming course ID is stored in the session

        // Prepare and bind parameters
        $stmt = $mysqli->prepare("INSERT INTO interactive_video_video (video_title, description, video_path, user_id, course_id, date_added) 
                                VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssss", $video_title, $description, $uploadFile, $user_id, $course_id);

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
        <h1 class="my-3">Upload Video Lecture</h1>
        <div class="underline"></div>

        <?php if (isset($message)): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="mb-3">
          <div class="form-group mb-3">
            <div class="field-block">
              <label for="video_title" class="form-label">Video Title</label>
              <input class="textbox" type="text" class="form-control" id="video_title" name="video_title" required>
            </div>
          </div>

          <div class="form-group mb-3">
            <div class="field-block">
              <label for="description" class="form-label">Description</label>
              <textarea class="textarea" class="form-control" id="description" name="description" rows="3"
                required></textarea>
            </div>
          </div>

          <div class="form-group mb-3">
            <div class="field-block">
              <label for="videoFile" class="form-label">Upload Video Lecture</label>
              <input style="display:none;" type="file" class="form-control" id="videoFile" name="videoFile"
                accept="video/mp4, video/avi, video/mov, video/wmv" required>
              <div id="pseudofile" class="pseudofile">
                <div class="choose-file">
                  Choose File
                </div>
                <div class="file-name">
                  <span id="file-name">No file chosen</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Video Preview -->
          <div id="vid-container" style="display: none;" class="mb-3">
            <video id="videoPreview" controls></video>
          </div>

          <div class="two-button-group">
            <a class="button-type-1 a" href="index.php" class="btn btn-secondary">Back to Home</a>
            <button class="button-type-1" type="submit" class="btn btn-primary">Upload</button>
          </div>
        </form>


      </div>
    </div>
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
  <script>
    document.getElementById('pseudofile').addEventListener("click", function () {
      document.getElementById('videoFile').click();
    })

    document.getElementById('videoFile').addEventListener("change", function () {
      if (this.files.length > 0) {
        document.getElementById('file-name').textContent = this.files[0].name;
      } else {
        document.getElementById('file-name').textContent = 'No file chosen';
      }
    })
  </script>
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