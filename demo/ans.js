    document.querySelector('.submit-button').addEventListener('click', function() {
        const input = document.querySelector('.styled-input').value;
        const contentSection = document.querySelector('.content-section');

        // ตัวอย่างการตรวจสอบคำตอบ
        if (input === "flag{correct_answer}") {
            contentSection.classList.add('correct');
            setTimeout(() => {
                contentSection.classList.remove('correct');
            }, 1000);
            alert("Correct Answer!");
        } else {
            contentSection.classList.add('wrong');
            setTimeout(() => {
                contentSection.classList.remove('wrong');
            }, 1000);
            alert("Wrong Answer!");
        }
    });
