const slider = document.getElementById("slider");
const cardsContainer = document.getElementById("cards-container");
const radioButtons = document.querySelectorAll(".radio-button");

let cardIndex = 0;

function showCard(index) {
  cardIndex = index;
  updateSlider();
}

function updateSlider() {
  const cardWidth = cardsContainer.children[0].offsetWidth;
  const newTransformValue = -cardIndex * cardWidth;
  cardsContainer.style.transform = `translateX(${newTransformValue}px)`;

  // Update the checked state of the corresponding radio button
  radioButtons[cardIndex].checked = true;
}

// Attach event listeners to radio buttons
radioButtons.forEach((radio, index) => {
  radio.addEventListener("change", () => {
    showCard(index);
  });
});

// Automatic card movement
function autoMove() {
  setInterval(() => {
    cardIndex = (cardIndex + 1) % cardsContainer.children.length;
    updateSlider();
  }, 6000); // Change 5000 to the desired interval in milliseconds (e.g., 5000 for 5 seconds)
}

// Call the autoMove function to start automatic movement
autoMove();

// Optional: Add event listeners for arrow keys or touch gestures for manual navigation
document.addEventListener("keydown", (event) => {
  if (event.key === "ArrowLeft") {
    showCard(
      (cardIndex - 1 + cardsContainer.children.length) %
        cardsContainer.children.length
    );
  } else if (event.key === "ArrowRight") {
    showCard((cardIndex + 1) % cardsContainer.children.length);
  }
});
