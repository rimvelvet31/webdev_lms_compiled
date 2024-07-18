document.getElementById("videoPlayer").addEventListener("timeupdate", function (event) {
  updateTimestampButton(event.target.currentTime);
});

document.getElementById("videoPlayer").addEventListener("click", function (event) {
  const video = event.target;
  const duration = video.duration;
  const progressBar = event.target;

  const rect = progressBar.getBoundingClientRect();
  const clickX = event.clientX - rect.left;

  const clickPositionPercentage = clickX / rect.width;
  const clickTime = duration * clickPositionPercentage;

  video.currentTime = clickTime;

  updateTimestampButton(clickTime);
});

function updateTimestampButton(currentTime) {
  currentTime = Math.floor(currentTime);

  const hours = Math.floor(currentTime / 3600);
  const minutes = Math.floor((currentTime % 3600) / 60);
  const seconds = Math.floor(currentTime % 60);

  let timestamp;
  if (hours > 0) {
    timestamp = `${hours}:${minutes < 10 ? "0" : ""}${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;
  } else {
    timestamp = `${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;
  }

  document.getElementById("assessmentButton").textContent = `Create Assessment at ${timestamp}`;

  const assessmentButton = document.getElementById("assessmentButton");
  assessmentButton.textContent = `Create Assessment at ${timestamp}`;

  const currentUrl = window.location.href;
  const url = new URL(currentUrl);
  const searchParams = new URLSearchParams(url.search);
  const video = searchParams.get("video");

  assessmentButton.href = `create_assessment.html?video=${video}&timestamp=${currentTime}`;
}
