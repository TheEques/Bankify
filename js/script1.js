// You can add JavaScript code here
document.addEventListener("DOMContentLoaded", function () {
  var infoStrip = document.getElementById("info-strip");
  var navbar = document.getElementById("navbar");

  infoStrip.addEventListener("animationiteration", function () {
    // Set the background color of the navbar to match the info strip after each iteration
    var computedStyles = window.getComputedStyle(infoStrip);
    navbar.style.backgroundColor = computedStyles.backgroundColor;
  });
});
