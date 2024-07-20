<?php
session_start();
include 'includes/dbh_inc.php';

// For testing: set as admin or student
// $_SESSION['isAdmin'] = false;
$_SESSION['isAdmin'] = true;

// Query to fetch video lectures
$sql = "SELECT id, video_title, description, video_path, user_id, course_id, date_added FROM interactive_video_video ORDER BY date_added ASC";
$stmt = $mysqli->prepare($sql);

// Execute query
$stmt->execute();

// Bind result variables
$stmt->bind_result($id, $video_title, $description, $video_path, $user_id, $course_id, $date_added);

// Fetch videos from db
$videos = [];
while ($stmt->fetch()) {
  $videos[] = [
    'id' => $id,
    'video_title' => $video_title,
    'description' => $description,
    'video_path' => $video_path,
    'user_id' => $user_id,
    "course_id" => $course_id,
    'date_added' => $date_added
  ];
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Video Lectures</title>

  <!-- Bootstrap -->
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

  <div class="content-body">
    <main class="body-container w60">
      <div class="flex_row cont">
        <h1>Video Lectures</h1>
        <div class="flex_row">
          <?php if ($_SESSION["isAdmin"]): ?>
            <button onclick="location.href='upload_video.php'">Upload Video</button>
          <?php endif; ?>
        </div>
      </div>

      <?php if (isset($_SESSION['upload_success'])): ?>
        <div class="alert alert-success fade-out">File successfully uploaded.</div>
        <?php unset($_SESSION['upload_success']); ?> <!-- Unset session after displaying -->
      <?php endif; ?>

      <!-- <div class="alert alert-danger fade-out">An error occurred.</div> -->


      <?php foreach ($videos as $video): ?>
        <div class="content-block">

          <div>
            <!-- Insert thumbnail here -->

            <div class="title-with-buttons index">
              <h1>
                <?php echo htmlspecialchars($video['video_title']); ?>
              </h1>
              <div class="title-buttons">
                <?php if (!$_SESSION["isAdmin"]): ?>
                  <button class="button-type-1" onclick="location.href='lecture.php?video=<?php echo $video['id']; ?>'"
                    class="btn btn-primary">Watch Lecture</button>
                <?php endif; ?>


                <?php if ($_SESSION["isAdmin"]): ?>
                  <button class="button-type-1"
                    onclick="location.href='video_assessments.php?video=<?php echo $video['id']; ?>'"
                    class="btn btn-primary">Add Assessment</button>
                  <button class="button-type-1" onclick="location.href='edit_video.php?video=<?php echo $video['id']; ?>'"
                    class="btn btn-primary">Edit Details</button>
                  <button class="button-type-1"
                    onclick="location.href='./includes/delete_video.php?video=<?php echo $video['id']; ?>'"
                    class="btn btn-danger"
                    onclick="return confirm('Are you sure you want to delete this video?');">Delete</button>
                <?php endif; ?>
              </div>
            </div>

            <div class="underline"></div>

            <div class="p_cont">
              <p class="desc"><?php echo htmlspecialchars($video['description']); ?></p>

              <p>
                <?php
                $date_added = new DateTime($video['date_added']);
                echo htmlspecialchars($date_added->format('F j, Y'));
                ?>
              </p>
            </div>



          </div>
        </div>
      <?php endforeach; ?>

    </main>
  </div>


  <footer>
    <!-- base/global footer? -->
  </footer>

  <script src="controller/index.js"></script>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>