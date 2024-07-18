// Hide alerts after 3 seconds
document.addEventListener("DOMContentLoaded", function () {
  var alerts = document.querySelectorAll(".alert");

  alerts.forEach(function (alert) {
    setTimeout(function () {
      alert.style.display = "none";
    }, 3000);
  });
});

// Truncate description to 20 words
document.addEventListener("DOMContentLoaded", function () {
  var descriptions = document.querySelectorAll(".card-body .desc");
  descriptions.forEach(function (desc) {
    var text = desc.innerText;
    var words = text.split(" ");
    if (words.length > 20) {
      desc.innerText = words.slice(0, 20).join(" ") + "...";
    }
  });
});
