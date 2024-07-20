<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = mysqli_connect('localhost', 'root', '', 'pup_lms');

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Get video ID from the URL
$video_id = isset($_GET['video']) ? intval($_GET['video']) : 0;

// Fetch assessment details
$assessment_id = isset($_GET['assessment_id']) ? intval($_GET['assessment_id']) : 0;

if ($assessment_id === 0) {
  die("Invalid assessment ID");
}

$assessment_query = "SELECT * FROM interactive_video_assessment WHERE id = ?";
$assessment_stmt = mysqli_prepare($conn, $assessment_query);
mysqli_stmt_bind_param($assessment_stmt, "i", $assessment_id);
mysqli_stmt_execute($assessment_stmt);
$assessment_result = mysqli_stmt_get_result($assessment_stmt);
$assessment = mysqli_fetch_assoc($assessment_result);

if (!$assessment) {
  die("Assessment not found");
}

// Fetch questions for the assessment
$question_query = "SELECT * FROM interactive_video_question WHERE assessment_id = ?";
$question_stmt = mysqli_prepare($conn, $question_query);
mysqli_stmt_bind_param($question_stmt, "i", $assessment_id);
mysqli_stmt_execute($question_stmt);
$question_result = mysqli_stmt_get_result($question_stmt);

$questions = [];
while ($row = mysqli_fetch_assoc($question_result)) {
  $question = $row;

  // Fetch choices for multiple choice questions
  if ($row['question_type'] == 1) {
    $choice_query = "SELECT * FROM interactive_video_choice WHERE question_id = ?";
    $choice_stmt = mysqli_prepare($conn, $choice_query);
    mysqli_stmt_bind_param($choice_stmt, "i", $row['id']);
    mysqli_stmt_execute($choice_stmt);
    $choice_result = mysqli_stmt_get_result($choice_stmt);

    $question['choices'] = [];
    while ($choice = mysqli_fetch_assoc($choice_result)) {
      $question['choices'][] = $choice;
    }
  }

  $questions[] = $question;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Assessment: <?php echo htmlspecialchars($assessment['id']); ?></title>
  <!--<link rel="stylesheet" href="styles/edit_assessment.css" />-->
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
    <div class="body-container w60">
      <form id="assessment_form" method="POST">
        <input type="hidden" name="assessment_id" value="<?php echo $assessment_id; ?>">

        <div id="question_list" class="question_list">
          <?php foreach ($questions as $index => $question): ?>
            <div class="question_div content-block">
              <div class="remove_question_div"></div>
              <div class="question_title">
                <label for="question_<?php echo $index; ?>">
                  <h1>Question:</h1>
                </label>
                <div class="underline question"></div>
                <textarea name="question_<?php echo $index; ?>_title" id="question_<?php echo $index; ?>"
                  class="question_textarea textarea"
                  required><?php echo htmlspecialchars($question['question_title']); ?></textarea>
              </div>
              <div class="question_points">
                <input type="number" name="question_<?php echo $index; ?>_points"
                  class="question_points_input  textbox half float-right" placeholder="Pts." required
                  value="<?php echo $question['question_score']; ?>">
              </div>
              <div class="question_type">
                <select name="question_<?php echo $index; ?>_type" class="question_type_select dropdown half float-left"
                  required>
                  <option value="1" <?php echo $question['question_type'] == 1 ? 'selected' : ''; ?>>Multiple Choice
                  </option>
                  <option value="2" <?php echo $question['question_type'] == 2 ? 'selected' : ''; ?>>Identification
                  </option>
                </select>
              </div>
              <div class="answer_field">
                <?php if ($question['question_type'] == 1): ?>
                  <?php foreach ($question['choices'] as $choice_index => $choice): ?>
                    <div class="multiple-choice-option">
                      <input type="radio" class="radio" name="question_<?php echo $index; ?>_answer"
                        value="<?php echo htmlspecialchars($choice['choice_text']); ?>" <?php echo $choice['choice_text'] == $question['correct_answer'] ? 'checked' : ''; ?>>
                      <input type="text" class="textbox choices"
                        name="question_<?php echo $index; ?>_option_<?php echo $choice_index; ?>" class="option_label"
                        value="<?php echo htmlspecialchars($choice['choice_text']); ?>" required>
                      <p class="exit_button"></p>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <div class="identification_div">
                    <textarea class="textbox no-resize" name="question_<?php echo $index; ?>_identification"
                      placeholder="Enter answer here...."
                      required><?php echo htmlspecialchars($question['correct_answer']); ?></textarea>
                  </div>
                <?php endif; ?>
              </div>
              <div class="add_option_div">
                <p>Add Another Option +</p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="two-button-group">
          <button class="button-type-1 font-16" onclick="backButton()" type="button" id="back">Back</button>
          <div class="button-bundle with-margin">
            <button class="button-type-1 font-16" type="button" id="add_question">Add Question</button>
            <input class="button-type-1 font-16" type="submit" value="Save Changes" />
          </div>
        </div>
        <!--<button type="button" id="add_question">Add Question</button>
        <button type="submit">Save Changes</button>-->
      </form>
    </div>
  </div>

  <script src="controller/edit_assessment.js"></script>
</body>

</html>