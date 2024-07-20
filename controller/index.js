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
