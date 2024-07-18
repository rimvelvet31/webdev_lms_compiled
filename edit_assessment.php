<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = mysqli_connect('localhost', 'root', '', 'web_lms');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch assessment details
$assessment_id = 2; //isset($_GET['assessment_id']) ? intval($_GET['assessment_id']) : 0;

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
    <title>Edit Assessment: <?php echo htmlspecialchars($assessment['title']); ?></title>
    <link rel="stylesheet" href="styles/edit_assessment.css" />
</head>

<body>
    <form id="assessment_form" method="POST" action="update_assessment.php">
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

    <script>
        const questionList = document.querySelector(".question_list");
        const addQuestionButton = document.getElementById("add_question");

        function createQuestionDiv() {
            const questionDiv = document.createElement("div");
            questionDiv.classList.add("question_div");

            const questionIndex = document.querySelectorAll(".question_div").length;

            questionDiv.innerHTML = `
            <div class="remove_question_div">X</div>
            <div class="question_title">
                <label for="question_${questionIndex}">Question:</label>
                <textarea name="question_${questionIndex}_title" id="question_${questionIndex}" class="question_textarea" required></textarea>
            </div>
            <div class="question_points">
                <input type="number" name="question_${questionIndex}_points" class="question_points_input" placeholder="Pts." required>
            </div>
            <div class="question_type">
                <select name="question_${questionIndex}_type" class="question_type_select" required>
                    <option value="1">Multiple Choice</option>
                    <option value="2">Identification</option>
                </select>
            </div>
            <div class="answer_field"></div>
            <div class="add_option_div">
                <p>Add Another Option +</p>
            </div>
        `;

            const removeQuestionButton = questionDiv.querySelector(".remove_question_div");
            removeQuestionButton.addEventListener("click", removeQuestion);
            const addOptionButton = questionDiv.querySelector(".add_option_div");
            addOptionButton.addEventListener("click", addOption);

            return questionDiv;
        }

        function updateMultipleQuestions(questionDiv) {
            const questionDivName = questionDiv.querySelector(".question_textarea").id;
            const option_divs = questionDiv.querySelectorAll(".multiple-choice-option");
            option_divs.forEach((optionDiv, index) => {
                optionDiv.querySelectorAll("input").forEach((input, i) => {
                    if (input.type === "radio") {
                        input.name = `${questionDivName}_answer`;
                    }
                    if (input.type === "text") {
                        input.name = `${questionDivName}_option_${index}`;
                    }
                });
            });
        }

        function addOption() {
            const questionDiv = this.closest(".question_div");
            const answerField = questionDiv.querySelector(".answer_field");

            const radioDiv = document.createElement("div");
            radioDiv.classList.add("multiple-choice-option");

            const radioInput = document.createElement("input");
            radioInput.type = "radio";
            radioInput.name = `${questionDiv.querySelector(".question_textarea").id}_answer`;
            radioInput.required = true;

            const inputLabel = document.createElement("input");
            inputLabel.type = "text";
            inputLabel.name = `option_text`;
            inputLabel.placeholder = "Answer here....";
            inputLabel.classList.add("option_label");
            inputLabel.required = true;

            const exitButton = document.createElement("p");
            exitButton.textContent = "X";
            exitButton.classList.add("exit_button");

            inputLabel.addEventListener("input", function() {
                radioInput.value = this.value;
            });

            exitButton.addEventListener("click", function() {
                if (answerField.querySelectorAll(".multiple-choice-option").length === 1) {
                    alert("You must have at least one option");
                    return;
                }
                radioDiv.remove();
                updateMultipleQuestions(questionDiv);
            });

            radioDiv.appendChild(radioInput);
            radioDiv.appendChild(inputLabel);
            radioDiv.appendChild(exitButton);
            answerField.appendChild(radioDiv);

            updateMultipleQuestions(questionDiv);
        }

        function removeQuestion() {
            if (questionList.childElementCount === 1) {
                alert("You must have at least one question");
                return;
            }
            const questionDiv = this.closest(".question_div");
            questionDiv.remove();
            updateQuestionDivNames();
        }

        function updateAnswerField(questionDiv) {
            const questionTypeSelect = questionDiv.querySelector(".question_type_select");
            const answerField = questionDiv.querySelector(".answer_field");
            const addOptionButton = questionDiv.querySelector(".add_option_div");

            function handleChange() {
                const selectedType = questionTypeSelect.value;
                const multQExists = questionDiv.querySelectorAll(".multiple-choice-option");
                const textQExists = questionDiv.querySelector(".identification_div");

                if (selectedType === "1") {
                    if (multQExists.length === 0) {
                        addOption.call(addOptionButton);
                    }
                    if (textQExists) {
                        textQExists.style.display = "none";
                        textQExists.querySelector("textarea").required = false;
                    }
                    multQExists.forEach((option) => {
                        option.style.display = "flex";
                        option.querySelectorAll("input").forEach((input) => {
                            input.required = true;
                            input.disabled = false;
                        });
                    });
                    addOptionButton.style.display = "";
                } else if (selectedType === "2") {
                    if (!textQExists) {
                        const textAreaDiv = document.createElement("div");
                        textAreaDiv.classList.add("identification_div");

                        const textarea = document.createElement("textarea");
                        textarea.name = "identification";
                        textarea.placeholder = "Enter answer here....";
                        textarea.required = true;
                        textAreaDiv.appendChild(textarea);
                        answerField.appendChild(textAreaDiv);
                    }
                    multQExists.forEach((option) => {
                        option.style.display = "none";
                        option.querySelectorAll("input").forEach((input) => {
                            input.required = false;
                            input.disabled = true;
                        });
                    });
                    addOptionButton.style.display = "none";
                    if (textQExists) {
                        textQExists.style.display = "flex";
                        textQExists.querySelector("textarea").required = true;
                    }
                }
            }

            questionTypeSelect.addEventListener("change", handleChange);
            handleChange();
        }

        function addQuestion() {
            const newQuestionDiv = createQuestionDiv();
            questionList.appendChild(newQuestionDiv);
            updateAnswerField(newQuestionDiv);
            updateQuestionDivNames();
        }

        function updateQuestionDivNames() {
            const questionDivs = document.querySelectorAll(".question_div");
            questionDivs.forEach((questionDiv, index) => {
                const questionTextarea = questionDiv.querySelector(".question_textarea");
                const questionPointsInput = questionDiv.querySelector(".question_points_input");
                const questionTypeSelect = questionDiv.querySelector(".question_type_select");

                questionTextarea.name = `question_${index}_title`;
                questionTextarea.id = `question_${index}`;
                questionPointsInput.name = `question_${index}_points`;
                questionTypeSelect.name = `question_${index}_type`;

                updateMultipleQuestions(questionDiv);
            });
        }

        addQuestionButton.addEventListener("click", addQuestion);

        // Initialize existing questions
        document.querySelectorAll(".question_div").forEach((questionDiv) => {
            const addOptionButton = questionDiv.querySelector(".add_option_div");
            addOptionButton.addEventListener("click", addOption);

            const removeQuestionButton = questionDiv.querySelector(".remove_question_div");
            removeQuestionButton.addEventListener("click", removeQuestion);

            const exitButtons = questionDiv.querySelectorAll(".exit_button");
            exitButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const optionDiv = this.closest(".multiple-choice-option");
                    const answerField = optionDiv.closest(".answer_field");
                    if (answerField.querySelectorAll(".multiple-choice-option").length === 1) {
                        alert("You must have at least one option");
                        return;
                    }
                    optionDiv.remove();
                    updateMultipleQuestions(questionDiv);
                });
            });

            updateAnswerField(questionDiv);
        });

        updateQuestionDivNames();

        document.getElementById("assessment_form").addEventListener("submit", function(event) {
            event.preventDefault();

            // Disable inputs for hidden multiple-choice options
            document.querySelectorAll('.multiple-choice-option').forEach(option => {
                if (option.style.display === 'none') {
                    option.querySelectorAll('input').forEach(input => {
                        input.disabled = true;
                    });
                }
            });

            // Submit the form
            fetch("update_assessment.php", {
                    method: "POST",
                    body: new FormData(this)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        alert("Assessment updated successfully!");
                    } else {
                        alert("Error updating assessment: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred while updating the assessment.");
                });
        });
    </script>
</body>

</html>