const questionList = document.querySelector(".question_list");
const addQuestionButton = document.getElementById("add_question");

function createQuestionDiv() {
  const questionDiv = document.createElement("div");
  questionDiv.classList.add("question_div");
  questionDiv.classList.add("content-block");

  const questionIndex = document.querySelectorAll(".question_div").length;

  questionDiv.innerHTML = `
      <div class="remove_question_div"></div>
      <div class="question_title">
          <label for="question_${questionIndex}"><h1>Question:</h1></label>
          <div class="underline question"></div>
          <textarea name="question_${questionIndex}_title" id="question_${questionIndex}" class="question_textarea textarea" required></textarea>
      </div>
      <div class="question_points">
          <input type="number" name="question_${questionIndex}_points" class="question_points_input textbox half float-right" placeholder="Pts." required>
      </div>
      <div class="question_type">
          <select name="question_${questionIndex}_type" class="question_type_select dropdown half float-left" required>
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
        input.classList.add("radio");
      }
      if (input.type === "text") {
        input.name = `${questionDivName}_option_${index}`;
        input.classList.add("textbox");
        input.classList.add("choices");
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
  radioInput.classList.add("radio");
  radioInput.name = `${questionDiv.querySelector(".question_textarea").id}_answer`;
  radioInput.required = true;

  const inputLabel = document.createElement("input");
  inputLabel.type = "text";
  inputLabel.name = `option_text`;
  inputLabel.classList.add("textbox");
  inputLabel.classList.add("choices");
  inputLabel.placeholder = "Answer here....";
  inputLabel.classList.add("option_label");
  inputLabel.required = true;

  const exitButton = document.createElement("p");
  exitButton.textContent = "";
  exitButton.classList.add("exit_button");

  inputLabel.addEventListener("input", function () {
    radioInput.value = this.value;
  });

  exitButton.addEventListener("click", function () {
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
        textarea.classList.add("textbox");
        textarea.classList.add("no-resize");
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
  exitButtons.forEach((button) => {
    button.addEventListener("click", function () {
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

document.getElementById("assessment_form").addEventListener("submit", function (event) {
  event.preventDefault();

  // Disable inputs for hidden multiple-choice options
  document.querySelectorAll(".multiple-choice-option").forEach((option) => {
    if (option.style.display === "none") {
      option.querySelectorAll("input").forEach((input) => {
        input.disabled = true;
      });
    }
  });

  // Submit the form
  fetch("./includes/update_assessment.php", {
    method: "POST",
    body: new FormData(this),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        alert("Assessment updated successfully!");

        const currentUrl = window.location.href;
        const url = new URL(currentUrl);
        const searchParams = new URLSearchParams(url.search);
        const video = searchParams.get("video");

        window.location.href = `video_assessments.php?video=${video}`;
      } else {
        alert("Error updating assessment: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("An error occurred while updating the assessment.");
    });
});

function backButton() {
  const currentUrl = window.location.href;
  const url = new URL(currentUrl);
  const searchParams = new URLSearchParams(url.search);
  const video = searchParams.get("video");
  console.log(video);
  window.location.href = `video_assessments.php?video=${video}`;
}

//navbar
document.getElementById("more-white").addEventListener("click", function () {
  document.getElementsByClassName("dropdown-menu-white")[0].classList.toggle("toggle-in");
});

//navbar
document.getElementById("more-red").addEventListener("click", function () {
  document.getElementsByClassName("dropdown-menu-red")[0].classList.toggle("toggle-in");
});

//profile
document.getElementById("profile").addEventListener("click", function () {
  document.getElementsByClassName("dropdown-menu-profile")[0].classList.toggle("toggle-in");
});

//drawer
document.getElementsByClassName("toggle-hamburger")[0].addEventListener("click", function () {
  document.getElementById("drawer").classList.toggle("enter-from-left");
});

document.getElementsByClassName("close-button")[0].addEventListener("click", function () {
  document.getElementById("drawer").classList.toggle("enter-from-left");
});
