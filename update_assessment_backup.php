<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = mysqli_connect('localhost', 'root', '', 'web_lms');

if (!$conn) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . mysqli_connect_error()]));
}

$assessment_id = intval($_POST['assessment_id']);

// Start a transaction
mysqli_begin_transaction($conn);

try {
    // Delete existing questions and choices
    mysqli_query($conn, "DELETE FROM interactive_video_choice WHERE question_id IN (SELECT id FROM interactive_video_question WHERE assessment_id = $assessment_id)");
    mysqli_query($conn, "DELETE FROM interactive_video_question WHERE assessment_id = $assessment_id");

    // Insert updated questions and choices
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'question_') === 0 && strpos($key, '_title') !== false) {
            $index = str_replace(['question_', '_title'], '', $key);
            
            $question_title = mysqli_real_escape_string($conn, $_POST["question_{$index}_title"]);
            $question_type = intval($_POST["question_{$index}_type"]);
            $question_score = intval($_POST["question_{$index}_points"]);
            $correct_answer = mysqli_real_escape_string($conn, $question_type == 1 ? $_POST["question_{$index}_answer"] : $_POST["question_{$index}_identification"]);

            $question_query = "INSERT INTO interactive_video_question (assessment_id, question_title, question_type, question_score, correct_answer) VALUES ($assessment_id, '$question_title', $question_type, $question_score, '$correct_answer')";
            mysqli_query($conn, $question_query);
            
            $question_id = mysqli_insert_id($conn);

            if ($question_type == 1) { // Multiple choice
                $option_index = 0;
                while (isset($_POST["question_{$index}_option_{$option_index}"])) {
                    $choice_text = mysqli_real_escape_string($conn, $_POST["question_{$index}_option_{$option_index}"]);
                    $choice_query = "INSERT INTO interactive_video_choice (choice_text, question_id) VALUES ('$choice_text', $question_id)";
                    mysqli_query($conn, $choice_query);
                    $option_index++;
                }
            }
        }
    }

    // Commit the transaction
    mysqli_commit($conn);
    echo json_encode(["status" => "success", "message" => "Assessment updated successfully"]);
} catch (Exception $e) {
    // Rollback the transaction in case of error
    mysqli_rollback($conn);
    echo json_encode(["status" => "error", "message" => "Error updating assessment: " . $e->getMessage()]);
}

mysqli_close($conn);