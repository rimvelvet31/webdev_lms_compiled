<?php
session_start();
include 'includes/dbh_inc.php';

// For testing: set as admin or student
$_SESSION['isAdmin'] = true;

// Query to fetch video lectures
$sql = "SELECT id, video_title, description, video_path, user_id, date_added FROM interactive_video_video ORDER BY date_added ASC";
$stmt = $mysqli->prepare($sql);

// Execute query
$stmt->execute();

// Bind result variables
$stmt->bind_result($id, $video_title, $description, $video_path, $user_id, $date_added);

// Fetch videos from db
$videos = [];
while ($stmt->fetch()) {
  $videos[] = [
    'id' => $id,
    'video_title' => $video_title,
    'description' => $description,
    'video_path' => $video_path,
    'user_id' => $user_id,
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
  <header>
    <!-- base/global logo & navbar? -->
  </header>

  <main class="container">
    <div class="d-flex justify-content-between my-3">
      <h2>Video Lectures</h2>
      <?php if ($_SESSION["isAdmin"]) : ?>
        <a href="upload_video.php" class="btn btn-primary">Upload Video</a>
      <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['upload_success'])) : ?>
      <div class="alert alert-success fade-out">File successfully uploaded.</div>
      <?php unset($_SESSION['upload_success']); ?> <!-- Unset session after displaying -->
    <?php endif; ?>

    <!-- <div class="alert alert-danger fade-out">An error occurred.</div> -->

    <div class="card-container">
      <?php foreach ($videos as $video) : ?>
        <div class="card mb-3" style="width: 100%;">
          <!-- Insert thumbnail here -->

          <div class="card-body">
            <h5 class="card-title">
              <?php echo htmlspecialchars($video['video_title']); ?>
            </h5>

            <p class="desc"><?php echo htmlspecialchars($video['description']); ?></p>

            <p>
              <?php
              $date_added = new DateTime($video['date_added']);
              echo htmlspecialchars($date_added->format('F j, Y'));
              ?>
            </p>

            <?php if (!$_SESSION["isAdmin"]) : ?>
              <a href="lecture.php?video=<?php echo $video['id']; ?>" class="btn btn-primary">Watch Lecture</a>
            <?php endif; ?>


            <?php if ($_SESSION["isAdmin"]) : ?>
              <a href="video_assessments.php?video=<?php echo $video['id']; ?>" class="btn btn-primary">Add Assessment</a>
              <a href="edit_video.php?video=<?php echo $video['id']; ?>" class="btn btn-primary">Edit Details</a>
              <a href="delete_video.php?video=<?php echo $video['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this video?');">Delete</a>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

  <footer>
    <!-- base/global footer? -->
  </footer>

  <script src="controller/index.js"></script>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>