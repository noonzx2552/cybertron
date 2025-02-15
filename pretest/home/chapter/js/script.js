// Function to open popup
function openPopup(popupId) {
    const popup = document.getElementById(popupId);
    popup.style.display = "block";
}

// Function to close popup
function closePopup(popupId) {
    const popup = document.getElementById(popupId);
    popup.style.display = "none";
}

// Function to check the answer
function checkAnswer() {
    const userAnswer = document.getElementById('answer').value.trim().toLowerCase();
    const correctAnswer = "flag{your_correct_answer}"; // แทนที่ด้วยคำตอบที่ถูกต้อง

    if (userAnswer === correctAnswer) {
        openPopup('correct-popup');
    } else {
        openPopup('incorrect-popup');
    }
}

// Add event listener to the submit button
document.querySelector('.submit-button').addEventListener('click', checkAnswer);
