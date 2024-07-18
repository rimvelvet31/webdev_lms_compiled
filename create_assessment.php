<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = mysqli_connect('localhost', 'root', '', 'webdev_lms');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Prepare the SQL statement for questions
$question_query = "INSERT INTO interactive_video_question (assessment_id, question_title, question_type, question_score, correct_answer) VALUES (?, ?, ?, ?, ?)";
$question_stmt = mysqli_prepare($conn, $question_query);

if (!$question_stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($question_stmt, "isiis", $assessment_id, $question_title, $question_type, $points, $correct_answer);

// Prepare the SQL statement for choices (multiple choice questions)
$choice_query = "INSERT INTO interactive_video_choice (choice_text, question_id) VALUES (?, ?)";
$choice_stmt = mysqli_prepare($conn, $choice_query);

foreach ($_POST as $key => $value) {
    if (strpos($key, 'question_') === 0 && strpos($key, '_title') !== false) {
        $index = str_replace(['question_', '_title'], '', $key);

        // Retrieve question details
        $assessment_id = $_POST['assessment_id'];
        $question_title = $_POST['question_' . $index . '_title'];
        $question_type = $_POST['question_' . $index . '_type'];
        $points = $_POST['question_' . $index . '_points'];
        $correct_answer = $question_type == 1 ? $_POST['question_' . $index . '_answer'] : $_POST['identification'];


        // Insert the question
        if (!mysqli_stmt_execute($question_stmt)) {
            die("Execute failed: " . mysqli_stmt_error($question_stmt));
        }

        $question_id = mysqli_insert_id($conn);

        // Handle choices/answers based on question type
        if ($question_type == 1) { // Multiple choice
            $option_count = 0;
            while (isset($_POST['question_' . $index . '_option_' . $option_count])) {
                $choice_text = $_POST['question_' . $index . '_option_' . $option_count];

                mysqli_stmt_bind_param($choice_stmt, "si", $choice_text, $question_id);
                if (!mysqli_stmt_execute($choice_stmt)) {
                    die("Execute failed: " . mysqli_stmt_error($choice_stmt));
                }

                $option_count++;
            }
        }
    }
}

mysqli_stmt_close($question_stmt);
mysqli_stmt_close($choice_stmt);
mysqli_close($conn);

echo json_encode(["status" => "success", "message" => "Questions and choices/answers inserted successfully."]);
