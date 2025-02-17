let currentSection = 1;

function showSection(sectionNumber) {
    document.querySelectorAll('.content-section').forEach(section => {
        section.classList.remove('active');
    });
    document.getElementById(`section${sectionNumber}`).classList.add('active');
}

function nextSection() {
    if (currentSection < 2) {
        currentSection++;
        showSection(currentSection);
    } else if (currentSection === 2) {
        // ถ้าอยู่ในส่วนที่ 2 ให้ไปยังหน้า lab_unit.php
        window.location.href = 'lab_unit.php';
    }
}

function prevSection() {
    if (currentSection > 1) {
        currentSection--;
        showSection(currentSection);
    }
}

function handleBackButton() {
    if (currentContent === 'content1') {
        window.location.href = '../home.php'; // ไปหน้า home.php
    } else if (currentContent === 'content2') {
        showContent('content1'); // กลับไปส่วนแรก
    }
}