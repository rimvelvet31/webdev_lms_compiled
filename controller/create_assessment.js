const questionList = document.querySelector(".question_list");
const addQuestionButton = document.getElementById("add_question");
let doAlert = true;

function createQuestionDiv() {
  const questionDiv = document.createElement("div");

  questionDiv.classList.add("question_div");
  questionDiv.innerHTML = `
      <div class="remove_question_div">X</div>
      <div class="question_title">
          <label for="question">Question:</label>
          <textarea name="question" id="question" class="question_textarea" required></textarea>
      </div>
      <div class="question_points">
          <input type="number" name="question_points" class="question_points_input" placeholder="Pts." required>
      </div>
      <div class="question_type">
          <select name="question_type" class="question_type_select" required>
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
  radioDiv.style.display = "flex";

  const radioInput = document.createElement("input");
  radioInput.type = "radio";
  radioInput.name = `options`;
  radioInput.id = `options`;
  radioInput.value = `options`;
  radioInput.disabled = false;
  radioInput.required = true;

  const inputLabel = document.createElement("input");
  inputLabel.type = "text";
  inputLabel.name = `option_text`;
  inputLabel.placeholder = "Answer here....";
  inputLabel.classList.add("option_label");
  inputLabel.disabled = false;
  inputLabel.required = true;
  inputLabel.addEventListener("input", function () {
    radioInput.value = this.value;
  });

  const exitButton = document.createElement("p");
  exitButton.textContent = "X";
  exitButton.style.color = "red";
  exitButton.classList.add("exit_button");
  exitButton.addEventListener("click", function () {
    if (answerField.querySelectorAll(".multiple-choice-option").length === 1) {
      alert("You must have at least one option");
      return;
    }
    radioDiv.remove();
  });

  const option_rename = new MutationObserver((mutations) => {
    mutations.forEach((mutation, i) => {
      if (mutation.type === "childList") {
        updateMultipleQuestions(questionDiv);
      }
    });
  });

  radioDiv.appendChild(radioInput);
  radioDiv.appendChild(inputLabel);
  radioDiv.appendChild(exitButton);
  answerField.appendChild(radioDiv);
  option_rename.observe(answerField, { childList: true });
}

function removeQuestion() {
  if (questionList.childElementCount === 1) {
    alert("You must have at least one question");
    return;
  }
  const questionDiv = this.closest(".question_div");
  questionDiv.remove();
}

function updateAnswerField(questionDiv) {
  const questionTypeSelect = questionDiv.querySelector(".question_type_select");
  const answerField = questionDiv.querySelector(".answer_field");
  const option_rename = new MutationObserver((mutations) => {
    mutations.forEach((mutation, i) => {
      if (mutation.type === "childList") {
        updateMultipleQuestions(questionDiv);
      }
    });
  });

  function handleChange() {
    const selectedType = questionTypeSelect.value;
    const multQExists = questionDiv.querySelectorAll(".multiple-choice-option");
    const textQExists = questionDiv.querySelector(".identification_div");
    const addOptionButton = questionDiv.querySelector(".add_option_div");

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
          input.classList.remove("dont-require");
          input.disabled = false;
        });
      });
      addOptionButton.style.display = "";
    } else if (selectedType === "2") {
      if (!textQExists) {
        const textAreaDiv = document.createElement("div");
        textAreaDiv.classList.add("identification_div");
        textAreaDiv.style.display = "flex";

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
          input.classList.add("dont-require");
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
  option_rename.observe(answerField, { childList: true });
}

function addQuestion() {
  const newQuestionDiv = createQuestionDiv();
  questionList.appendChild(newQuestionDiv);
  updateAnswerField(newQuestionDiv);
}

addQuestionButton.addEventListener("click", addQuestion);

const initialQuestionDiv = document.querySelector(".question_div");
const initialAddOptionButton = initialQuestionDiv.querySelector(".add_option_div");
initialAddOptionButton.addEventListener("click", addOption);

const initialRemoveQuestionButton = initialQuestionDiv.querySelector(".remove_question_div");
initialRemoveQuestionButton.addEventListener("click", removeQuestion);

updateAnswerField(initialQuestionDiv);

function updateQuestonDivNames(questionDivs) {
  questionDivs.forEach((questionDiv, index) => {
    const questionTextarea = questionDiv.querySelector(".question_textarea");
    const questionPointsInput = questionDiv.querySelector(".question_points_input");
    const questionTypeSelect = questionDiv.querySelector(".question_type_select");
    questionTextarea.name = `question_${index}_title`;
    questionTextarea.id = `question_${index}`;
    questionPointsInput.name = `question_${index}_points`;
    questionTypeSelect.name = `question_${index}_type`;
  });
}

const question_rename = new MutationObserver((mutations) => {
  mutations.forEach((mutation, i) => {
    if (mutation.type === "childList") {
      const questionDivs = document.querySelectorAll(".question_div");
      updateQuestonDivNames(questionDivs);
    }
  });
});

question_rename.observe(questionList, { childList: true });
updateQuestonDivNames(document.querySelectorAll(".question_div"));
updateMultipleQuestions(initialQuestionDiv);

window.addEventListener("beforeunload", function (e) {
  if (doAlert) {
    e.preventDefault();
    alert("You have unsaved changes");
  }
});

document.getElementById("assessment_form").addEventListener("submit", function (event) {
  document.querySelectorAll(".dont-require").forEach((input) => {
    input.disabled = true;
  });

  event.preventDefault();
  let data = new FormData(this);
  for (let [key, value] of data.entries()) {
    console.log(`${key}: ${value}`);
  }

  const currentUrl = window.location.href;
  const url = new URL(currentUrl);
  const searchParams = new URLSearchParams(url.search);
  const video = searchParams.get("video");
  const timestamp = searchParams.get("timestamp");

  fetch(`./includes/create_assessment.php?video=${video}&timestamp=${timestamp}`, {
    method: "POST",
    body: new FormData(this),
  })
    .then((response) => response.text())
    .then((data) => {
      console.log(data);
      doAlert = false;
      window.location.href = `video_assessments.php?video=${video}`;
    })
    .catch((err) => {
      console.error("Error parsing JSON:", err);
      console.error("Response was:", text);
    });
});
