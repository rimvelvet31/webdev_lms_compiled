<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define the file path and name for the JSON file
$json_file = 'questions.json'; // Change this to your desired filename and location

$data = []; // Array to hold question data
$questions = []; // Array to hold question details
foreach ($_POST as $key => $value) {
  if (strpos($key, 'question_') === 0 && strpos($key, '_title') !== false) {
    $index = str_replace(['question_', '_title'], '', $key);

    // Retrieve question details
    $question_title = $_POST['question_' . $index . '_title'];
    $question_type = $_POST['question_' . $index . '_type'];
    $points = $_POST['question_' . $index . '_points'];
    $correct_answer = $question_type == 1 ? $_POST['question_' . $index . '_answer'] : $_POST['identification'];
    
    $question = [
      "assessment_id" => $_POST['assessment_id'],
      "question_title" => $question_title,
      "question_type" => $question_type,
      "points" => $points,
      "correct_answer" => $correct_answer,
    ];

    // Handle choices/answers based on question type
    if ($question_type == 1) { // Multiple choice
      $choices = [];
      $option_count = 0;
      while (isset($_POST['question_' . $index . '_option_' . $option_count])) {
        $choice_text = $_POST['question_' . $index . '_option_' . $option_count];
        $choices[] = $choice_text;
        $option_count++;
      }
      $question["choices"] = $choices;
    }

    $questions[] = $question;
  }
}
$data[$_GET['timestamp']] = $questions;
// Encode the data array into JSON format with pretty printing for readability
$json_data = json_encode($data, JSON_PRETTY_PRINT);

// Write the JSON data to the file
if (file_put_contents($json_file, $json_data) !== false) {
  echo json_encode(["status" => "success", "message" => "Questions and choices/answers saved to JSON file successfully."]);
} else {
  echo json_encode(["status" => "error", "message" => "Failed to save questions to JSON file."]);
}