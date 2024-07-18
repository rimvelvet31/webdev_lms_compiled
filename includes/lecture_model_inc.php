<?php
require_once "dbh_inc.php";
require_once "execute_query_inc.php";

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $query = "SELECT * FROM interactive_video_assessment WHERE video_id = ?";
        $result = executeQuery($mysqli, $query, "i", [$_GET["video"]]);

        $assessments = [];
        while ($row = $result["result"]?->fetch_assoc()) {
            // Convert the time value to seconds
            [$hours, $minutes, $seconds] = explode(':', $row["_timestamp"]);
            $totalSeconds = (int) $hours * 3600 + (int) $minutes * 60 + (int) $seconds;
            $assessments[] = [
                "id" => $row["id"],
                "submitted" => $row["submitted"],
                "sec" => $totalSeconds,
                "score" => $row["score"],
                "grade" => $row["grade"],
            ];
        }

        // iterate all questions of each assessments then add them to the array
        foreach ($assessments as $i => $assessment) {
            $query = "SELECT * FROM interactive_video_question WHERE assessment_id = ?";
            $result = executeQuery($mysqli, $query, "i", [$assessment["id"]]);

            $questions = [];
            while ($row = $result["result"]?->fetch_assoc()) {
                $questions[] = $row;
            }

            foreach ($questions as $j => $question) {
                // If the question is a multiple choice question, retrieve the choices
                $questions[$j]["choices"] = null;
                if ($question["question_type"] == 1) {
                    $query = "SELECT * FROM interactive_video_choice WHERE question_id = ?";
                    $result = executeQuery($mysqli, $query, "i", [$question["id"]]);

                    $choices = [];
                    while ($row = $result["result"]?->fetch_assoc()) {
                        $choices[] = $row;
                    }

                    $questions[$j]["choices"] = $choices;
                }

                // store the user answer and score
                $query = "SELECT user_score, user_answer FROM interactive_video_answer WHERE question_id = ?";
                $result = executeQuery($mysqli, $query, "i", [$question["id"]]);
                $result = $result["result"]?->fetch_assoc();
                $questions[$j]["user_score"] = $result["user_score"] ?? null;
                $questions[$j]["user_answer"] = $result["user_answer"] ?? null;
            }

            $assessments[$i]["questions"] = $questions;
        }

        echo json_encode($assessments);
        break;

    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);

        // save the score and grade of the assessment
        $query = "UPDATE interactive_video_assessment SET score = ?, grade = ?, submitted = ? WHERE id = ?";
        $result = executeQuery($mysqli, $query, "isii", [$data["score"], $data["grade"], $data["submitted"], $data["assessmentId"]]);

        $placeholders = [];
        $values = [];

        // store the values and placeholders for the user answers
        foreach ($data["userAnswers"] as $userAnswer) {
            $placeholders[] = "(?, ?, ?)";
            array_push($values, $userAnswer["questionId"], $userAnswer["userAnswer"], $userAnswer["userScore"]);
        }

        // Only proceed if there are user answers
        if (!empty($placeholders)) {
            $query = "INSERT INTO interactive_video_answer (question_id, user_answer, user_score) VALUES " . implode(', ', $placeholders);
            error_log($query);
            $result = executeQuery($mysqli, $query, str_repeat("isi", count($data["userAnswers"])), $values);
        }

        echo json_encode(["success" => true, "message" => "Assessment has been recorded."]);
        break;
}

$mysqli->close();
