function checkAnswer() {
    var answer = document.getElementById("answer").value;

    fetch("check_answer.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "answer=" + encodeURIComponent(answer)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "correct") {
            document.getElementById("correct-popup").style.display = "block";
        } else {
            document.getElementById("incorrect-popup").style.display = "block";
        }
    })
    .catch(error => console.error("Error:", error));
}

function closePopup(id) {
    document.getElementById(id).style.display = "none";
}

