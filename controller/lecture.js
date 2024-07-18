const video = document.getElementById("video");
const controlBtn = document.getElementById("control-btn");
const currentTime = document.getElementById("current-time");
const track = document.getElementById("track");
const progress = document.getElementById("progress");
const questionDialog = document.getElementById("question-dialog");
const markDoneBtn = document.getElementById("mark-done");
const summaryBtn = document.getElementById("summary-btn");

/* let sampleTimestamps = [
  {
    sec: 4,
    done: false,
  },
  {
    sec: 7,
    done: false,
  },
  {
    sec: 11,
    done: false,
  },
]; */

let currentTimestamp = null;
let handleTimeUpdate = null;
let handleProgressUpdate = null;
let handleSummaryUpdate = null;

function refreshTimestamps(timestamps = []) {
  `
  # timestamps data structure
  # all assessments for the "video_id"

  timestamps = [
    {
      id: int,
      sec: int,
      submitted: boolean,
      score: int,
      grade: int,
      questions: [
        {
          id: int,
          questions_title: string,
          question_type: tinyint,
          question_score: int,
          correct_answer: string,
          choices: [{
            id: int,
            choice_text: string,
            question_id: int,
          }] | null,
          user_answer: string | null,
          user_score: int | null,
        },
      ],
    },
  ]
  `;

  // remove all timestamps except the first
  for (let i = 1; i < track.children.length - 1; i++) {
    track.removeChild(track.children[i]);
  }

  timestamps.forEach((timestamp) => {
    const timestampMark = document.createElement("button");
    timestampMark.classList.add("timestamp");
    timestampMark.setAttribute("data-time", timestamp.sec);

    if (timestamp.submitted) {
      timestampMark.classList.add("done");
    }

    const width = 5;
    const time = Math.round((timestamp.sec / video.duration) * 100);
    timestampMark.style.left = `calc(${time}% - ${width}px / 2)`;
    timestampMark.style.width = `${width}px`;

    timestampMark.addEventListener("click", () => {
      openQuestionDialog(timestamp);

      if (currentTimestamp && !currentTimestamp.submitted) {
        video.currentTime = timestamp.sec;
        progress.value = (timestamp.sec / video.duration) * 100;
      }
    });

    track.appendChild(timestampMark);
  });
}

function formatDuration(seconds) {
  const hours = Math.floor(seconds / 3600);
  const minutes = Math.floor((seconds % 3600) / 60);
  const remainingSeconds = seconds % 60;

  // Pad the minutes and seconds with leading zeros, if required
  const minutesDisplay = minutes < 10 ? `0${minutes}` : minutes;
  const secondsDisplay = remainingSeconds < 10 ? `0${remainingSeconds}` : remainingSeconds;

  // Include hours in the output only if it's greater than 0
  if (hours > 0) {
    const hoursDisplay = hours < 10 ? `0${hours}` : hours;
    return `${hoursDisplay}:${minutesDisplay}:${secondsDisplay}`;
  } else {
    return `${minutesDisplay}:${secondsDisplay}`;
  }
}

function toggleVideo() {
  (video.paused ? playVideo : pauseVideo)();
}

function pauseVideo() {
  const icon = controlBtn.querySelector("i");
  icon.classList.replace("bx-pause", "bx-play");
  video.pause();
}

function playVideo() {
  const icon = controlBtn.querySelector("i");
  icon.classList.replace("bx-play", "bx-pause");
  video.play();
}

function disableControls() {
  video.style.pointerEvents = "none";
  controlBtn.parentElement.style.pointerEvents = "none";
}

function enableControls() {
  video.style.pointerEvents = "auto";
  controlBtn.parentElement.style.pointerEvents = "auto";
}

function openQuestionDialog(timestamp, contentHTML) {
  pauseVideo();
  disableControls();
  questionDialog.setAttribute("data-open", true);

  if (timestamp) {
    currentTimestamp = timestamp;
  }

  const content = questionDialog.querySelector(".content");

  if (contentHTML) {
    content.innerHTML = contentHTML;
    return;
  }

  // content of the assessment dialog
  // prettier-ignore
  content.innerHTML = /*html*/ `
    <form class="dialog-form" method="post" onsubmit="submitAssessment(event)">
      ${timestamp.questions.map((question, index) => (/*html*/ `
        <div class="question">
          <div class="question__container">
            <h3 class="question__title">${question.question_title}</h3>
            <p class="question__score">
              ${question.user_score ?? "--"} / ${question.question_score}
            </p>
          </div>
          ${question.question_type === 1 && question.choices ? (/*html*/`
            <div class="question__options">
              ${question.choices 
                .map((choice) => (/*html*/ `
                  <label class="question__option">
                    <input
                      type="radio"
                      name="question_${index}"
                      value="${choice.choice_text}"
                      ${question.user_answer === choice.choice_text ? 'checked' : ''}
                      ${currentTimestamp.submitted ? 'disabled' : ''}
                      required
                    >
                    ${choice.choice_text}
                  </label>
                `))
                .join("")}
            </div>
          `) : (/* html */`
            <input
              class="question__input"
              type="text"
              name="question_${index}"
              placeholder="Enter your answer..."
              value="${question.user_answer ?? ""}"
              ${currentTimestamp.submitted ? 'disabled' : ''}
              required
            >
          `)}
        </div>
      `)).join("")}
      <div class="buttons">
        ${currentTimestamp.submitted ? (
          /*html*/`<button type="button" class="btn" onclick="closeQuestionDialog(false)">Close</button>`
        ) : (
          /*html*/`<button type="submit" class="btn">Mark as done</button>`
        )}
      </div>
    </form>
  `;
}

function closeQuestionDialog(doPlay = true) {
  if (doPlay && !video.ended) playVideo();
  enableControls();
  questionDialog.setAttribute("data-open", false);
}

function submitAssessment(event) {
  event.preventDefault();

  const form = event.target;
  const formData = new FormData(form);
  const userAnswers = Array.from(formData.values());
  const userScores = currentTimestamp.questions.map((question, index) => {
    return question.correct_answer.toLowerCase() === userAnswers[index].toLowerCase() ? question.question_score : 0;
  });

  const userAnswersData = currentTimestamp.questions.map((question, index) => {
    return {
      questionId: question.id,
      userAnswer: userAnswers[index],
      userScore: userScores[index],
    };
  });

  const totalPoints = currentTimestamp.questions.reduce((acc, question) => acc + question.question_score, 0);
  const score = userScores.reduce((acc, score) => acc + score, 0);
  const grade = ((score / totalPoints) * 100).toFixed(2);

  fetch("./includes/lecture_model_inc.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      assessmentId: currentTimestamp.id,
      score,
      grade,
      userAnswers: userAnswersData,
      submitted: 1,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
      fetchTimestamps();
      closeQuestionDialog();
    })
    .catch((err) => {
      console.error("Error parsing JSON:", err);
    });
}

// need to do this in order to remove the event listener with parameters
function customTimeUpdateListener(timestamps) {
  const wrappedFn = () => {
    for (const timestamp of timestamps) {
      if (!timestamp.submitted && video.currentTime >= timestamp.sec) {
        openQuestionDialog(timestamp);
        return;
      }
    }

    currentTime.innerHTML = formatDuration(Math.round(video.currentTime));
    progress.value = (video.currentTime / video.duration) * 100;
  };
  video.addEventListener("timeupdate", wrappedFn);
  return wrappedFn;
}

function customProgressUpdateListener(timestamps) {
  const wrappedFn = (e) => {
    const progressVal = e.target.value;

    for (const timestamp of timestamps) {
      const progressPercentage = progressVal / 100;
      const timePercentage = timestamp.sec / video.duration;
      if (!timestamp.submitted && progressPercentage >= timePercentage) {
        progress.value = timePercentage * 100;
        video.currentTime = timestamp.sec;
        return;
      }
    }

    const time = (progressVal / 100) * video.duration;
    video.currentTime = time;
  };
  progress.addEventListener("input", wrappedFn);
  return wrappedFn;
}

function updateVideo(timestamps) {
  // remove existing listeners first
  video.removeEventListener("timeupdate", handleTimeUpdate);
  progress.removeEventListener("input", handleProgressUpdate);

  // update the listeners
  handleTimeUpdate = customTimeUpdateListener(timestamps);
  handleProgressUpdate = customProgressUpdateListener(timestamps);
}

function customSummaryClickListener(timestamps) {
  const calculateQuestionsTotal = (questions) => {
    return questions.reduce((acc, question) => acc + question.question_score, 0);
  };

  const wrappedFn = () => {
    const totalUserScore = timestamps.reduce((acc, timestamp) => acc + timestamp.score, 0);
    const totalScore = timestamps.reduce((acc, timestamp) => acc + calculateQuestionsTotal(timestamp.questions), 0);
    const totalGrade = ((totalUserScore / totalScore) * 100).toFixed(2);

    // prettier-ignore
    const contentHTML = /*html*/ `
      <div class="summary">
        <h2 class="summary__title">Summary</h2>
        <div class="summary__list">
          ${timestamps.map((timestamp, index) => (/* html */`
            <div class="summary__item">
              <h3 class="summary__time">Assessment ${index + 1}</h3>
              <p class="summary__status">
                ${!timestamp.submitted ? "Not yet submitted" : (
                  `${timestamp.score} / ${calculateQuestionsTotal(timestamp.questions)} (${timestamp.grade}%)`
                )}
              </p>
            </div>
          `)).join("")}
        </div>
        <hr class="summary__separator" />
        <p class="summary__total">
          Total Score:
          <span class="summary__total--grade">${totalUserScore} (${totalGrade})</span></p>
        <div class="buttons">
          <button class="btn" onclick="closeQuestionDialog(false)">Close</button>
        </div>
      </div>
    `;

    openQuestionDialog(null, contentHTML);
  };
  summaryBtn.addEventListener("click", wrappedFn);
  return wrappedFn;
}

function updateSummary(timestamps) {
  summaryBtn.removeEventListener("click", handleSummaryUpdate);
  handleSummaryUpdate = customSummaryClickListener(timestamps);
}

function fetchTimestamps() {
  // Step 1: Get the current URL
  const currentUrl = window.location.href;

  // Step 2: Create a URL object
  const url = new URL(currentUrl);

  // Step 3: Create a URLSearchParams object from the URL's search string
  const searchParams = new URLSearchParams(url.search);

  // Step 4: Get the value of a specific URL parameter, e.g., 'id'
  const paramValue = searchParams.get("video"); // Replace 'id' with the name of the parameter you want to fetch

  fetch(`./includes/lecture_model_inc.php?video=${paramValue}`)
    .then((response) => response.json())
    .then((data) => {
      refreshTimestamps(data);
      updateVideo(data);
      updateSummary(data);
    })
    .catch((err) => {
      console.error("Error parsing JSON:", err);
    });
}

window.addEventListener("load", () => {
  const duration = document.getElementById("duration");
  duration.innerHTML = formatDuration(Math.round(video.duration));
  fetchTimestamps();
});

video.addEventListener("ended", () => {
  const icon = controlBtn.querySelector("i");
  icon.classList.replace("bx-pause", "bx-play");
});

video.addEventListener("click", toggleVideo);

controlBtn.addEventListener("click", toggleVideo);
