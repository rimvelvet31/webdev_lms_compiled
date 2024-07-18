<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = mysqli_connect('localhost', 'root', '', 'webdev_lms');

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
    <link rel="stylesheet" href="styles/edit_assessment.css" />
</head>

<body>
    <form id="assessment_form" method="POST">
        <input type="hidden" name="assessment_id" value="<?php echo $assessment_id; ?>">

        <div id="question_list" class="question_list">
            <?php foreach ($questions as $index => $question) : ?>
                <div class="question_div">
                    <div class="remove_question_div">X</div>
                    <div class="question_title">
                        <label for="question_<?php echo $index; ?>">Question:</label>
                        <textarea name="question_<?php echo $index; ?>_title" id="question_<?php echo $index; ?>" class="question_textarea" required><?php echo htmlspecialchars($question['question_title']); ?></textarea>
                    </div>
                    <div class="question_points">
                        <input type="number" name="question_<?php echo $index; ?>_points" class="question_points_input" placeholder="Pts." required value="<?php echo $question['question_score']; ?>">
                    </div>
                    <div class="question_type">
                        <select name="question_<?php echo $index; ?>_type" class="question_type_select" required>
                            <option value="1" <?php echo $question['question_type'] == 1 ? 'selected' : ''; ?>>Multiple Choice</option>
                            <option value="2" <?php echo $question['question_type'] == 2 ? 'selected' : ''; ?>>Identification</option>
                        </select>
                    </div>
                    <div class="answer_field">
                        <?php if ($question['question_type'] == 1) : ?>
                            <?php foreach ($question['choices'] as $choice_index => $choice) : ?>
                                <div class="multiple-choice-option">
                                    <input type="radio" name="question_<?php echo $index; ?>_answer" value="<?php echo htmlspecialchars($choice['choice_text']); ?>" <?php echo $choice['choice_text'] == $question['correct_answer'] ? 'checked' : ''; ?>>
                                    <input type="text" name="question_<?php echo $index; ?>_option_<?php echo $choice_index; ?>" class="option_label" value="<?php echo htmlspecialchars($choice['choice_text']); ?>" required>
                                    <p class="exit_button">X</p>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="identification_div">
                                <textarea name="question_<?php echo $index; ?>_identification" placeholder="Enter answer here...." required><?php echo htmlspecialchars($question['correct_answer']); ?></textarea>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="add_option_div">
                        <p>Add Another Option +</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="button" id="add_question">Add Question</button>
        <button type="submit">Save Changes</button>
    </form>

    <script src="controller/edit_assessment.js"></script>
</body>

</html>