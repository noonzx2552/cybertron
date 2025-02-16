const questions = [
    {
        question: "1)ข้อมูลที่เป็นส่วนตัวและไม่ควรเปิดเผยทางออนไลน์มากที่สุดคืออะไร",
        options: ["ชื่อเล่น", "หมายเลขโทรศัพท์", "วันเกิด", "รหัสผ่าน"],
        answer: "รหัสผ่าน"
    },
    {
        question: "2)ข้อใดต่อไปนี้เป็นตัวอย่างของมัลแวร์ (Malware)?",
        options: ["ซอฟต์แวร์ตัดต่อรูปภาพ", "โปรแกรมสแกนไวรัส", "แอดแวร์ (Adware)", "ระบบปฏิบัติการ"],
        answer: "แอดแวร์ (Adware)"
    },
    {
        question: "3)หากคุณได้รับอีเมลที่ขอข้อมูลส่วนตัวพร้อมลิงก์ให้กดเข้าไป คุณควรทำอย่างไร",
        options: ["กดลิงก์ทันที", "ลบอีเมลทิ้งทันที", "ตรวจสอบที่อยู่อีเมลผู้ส่งก่อนดำเนินการ", "ตอบกลับอีเมลเพื่อถามข้อมูลเพิ่มเติม"],
        answer: "ตรวจสอบที่อยู่อีเมลผู้ส่งก่อนดำเนินการ"
    },
    {
        question: "4)VPN (Virtual Private Network) มีประโยชน์อย่างไร?",
        options: ["ช่วยบล็อกเว็บไซต์", "ป้องกันการเข้าถึงเว็บไซต์ต้องห้าม", "เข้ารหัสข้อมูลเพื่อเพิ่มความปลอดภัย", "ใช้สำหรับโหลดไฟล์เท่านั้น"],
        answer: "เข้ารหัสข้อมูลเพื่อเพิ่มความปลอดภัย"
    },
    {
        question: "5)การใช้รหัสผ่านที่ดีควรมีลักษณะอย่างไร?",
        options: ["ใช้เฉพาะตัวเลขเท่านั้น", "ใช้ชื่อสัตว์เลี้ยงที่คุณชื่นชอบ", "ผสมตัวเลข ตัวอักษร และสัญลักษณ์", "ใช้รหัสผ่านเดียวกันกับทุกบัญชี"],
        answer: "ผสมตัวเลข ตัวอักษร และสัญลักษณ์"
    },
    {
        question: "6)ข้อใดคือวิธีที่ปลอดภัยที่สุดในการตรวจสอบว่าเว็บไซต์ที่คุณเข้าชมปลอดภัย?",
        options: ["เว็บไซต์มีสีสันสวยงาม", "มีไอคอนรูปแม่กุญแจในช่อง URL", "เว็บไซต์มีเนื้อหาที่ดูน่าเชื่อถือ", "เว็บไซต์โหลดเร็ว"],
        answer: "มีไอคอนรูปแม่กุญแจในช่อง URL"
    },
    {
        question: "7)สิ่งที่เรียกว่า Phishing คืออะไร?",
        options: ["การแฮกระบบคอมพิวเตอร์", "การล่อลวงข้อมูลส่วนตัวผ่านอีเมลหรือเว็บไซต์ปลอม", "การป้องกันไวรัส", "การใช้ไฟร์วอลล์ (Firewall)"],
        answer: "การล่อลวงข้อมูลส่วนตัวผ่านอีเมลหรือเว็บไซต์ปลอม"
    },
    {
        question: "8)ข้อใดคือหน้าที่หลักของซอฟต์แวร์ป้องกันไวรัส (Antivirus)?",
        options: ["ปรับปรุงความเร็วอินเทอร์เน็ต", "ค้นหาและกำจัดมัลแวร์", "จัดเก็บข้อมูลสำรอง", "ทำให้คอมพิวเตอร์ประหยัดพลังงาน"],
        answer: "ค้นหาและกำจัดมัลแวร์"
    },
    {
        question: "9)การตั้งค่าความเป็นส่วนตัว (Privacy Settings) ในโซเชียลมีเดียมีประโยชน์อย่างไร?",
        options: ["ทำให้โปรไฟล์ดูน่าสนใจขึ้น", "จำกัดการเข้าถึงข้อมูลส่วนตัวของผู้ใช้งาน", "ป้องกันการถูกแฮก 100%", "เพิ่มยอดผู้ติดตาม"],
        answer: "จำกัดการเข้าถึงข้อมูลส่วนตัวของผู้ใช้งาน"
    },
    {
        question: "10)ข้อใดเป็นพฤติกรรมที่ควรหลีกเลี่ยงเพื่อความปลอดภัยทางอินเทอร์เน็ต?",
        options: ["อัปเดตซอฟต์แวร์เป็นประจำ", "ดาวน์โหลดไฟล์จากเว็บไซต์ที่ไม่ปลอดภัย", "ใช้รหัสผ่านที่ซับซ้อน", "ตรวจสอบ URL ก่อนคลิกลิงก์"],
        answer: "ดาวน์โหลดไฟล์จากเว็บไซต์ที่ไม่ปลอดภัย"
    }
];

let currentQuestionIndex = 0;
let score = 0;
let isShowingAnswer = false;

function prevQuestion() {
    if (currentQuestionIndex > 0) {
        currentQuestionIndex--;
        loadQuestion();
    } else {
        alert("นี่คือคำถามแรกแล้ว ไม่สามารถย้อนกลับได้");
    }
}

function loadQuestion() {
    const questionContainer = document.getElementById("question-container");
    const currentQuestion = questions[currentQuestionIndex];

    questionContainer.innerHTML = `
        <h2 style="color:rgb(255, 255, 255);">${currentQuestion.question}</h2> 
        ${currentQuestion.options.map(option => `
            <div class="option" onclick="selectOption('${option}')">${option}</div>
        `).join('')}
    `;

    const selectedOption = currentQuestion.selectedOption;
    if (selectedOption) {
        const options = document.querySelectorAll(".option");
        options.forEach(option => {
            if (option.textContent === selectedOption) {
                option.classList.add("selected");
            }
        });
    }

    const correctOption = document.querySelector(".option.correct");
    if (correctOption) {
        correctOption.classList.remove("correct");
    }
    const incorrectOption = document.querySelector(".option.incorrect");
    if (incorrectOption) {
        incorrectOption.classList.remove("incorrect");
    }

    isShowingAnswer = false;
}

function selectOption(selectedOption) {
    const options = document.querySelectorAll(".option");
    options.forEach(option => {
        option.classList.remove("selected");
        if (option.textContent === selectedOption) {
            option.classList.add("selected");
        }
    });

    questions[currentQuestionIndex].selectedOption = selectedOption;
}

function nextQuestion() {
    const selectedOption = document.querySelector(".option.selected");

    if (!selectedOption) {
        alert("กรุณาเลือกคำตอบก่อน");
        return;
    }

    if (!isShowingAnswer) {
        showAnswer();
        isShowingAnswer = true;
        return;
    }

    const currentQuestion = questions[currentQuestionIndex];
    if (selectedOption.textContent === currentQuestion.answer) {
        score++;
    }

    currentQuestionIndex++;
    if (currentQuestionIndex < questions.length) {
        loadQuestion();
        isShowingAnswer = false;
    } else {
        showResult();
    }
}

function showResult() {
    document.querySelector(".quiz-container").style.display = "none";
    document.querySelector(".result-container").style.display = "block";
    document.getElementById("score").textContent = `คุณได้คะแนน ${score} จาก ${questions.length} คะแนน`;

    sendScoreToServer(score);
}

function restartQuiz() {
    currentQuestionIndex = 0;
    score = 0;

    questions.forEach(question => {
        question.selectedOption = null;
    });

    document.querySelector(".quiz-container").style.display = "block";
    document.querySelector(".result-container").style.display = "none";

    loadQuestion();
}

window.onload = loadQuestion;

function showAnswer() {
    const options = document.querySelectorAll(".option");
    const currentQuestion = questions[currentQuestionIndex];
    const selectedOption = document.querySelector(".option.selected");

    options.forEach(option => {
        if (option.textContent === currentQuestion.answer) {
            option.classList.add("correct");
        }
        if (option.textContent === selectedOption.textContent && option.textContent !== currentQuestion.answer) {
            option.classList.add("incorrect");
        }
    });
}

// ฟังก์ชันสำหรับแสดงเวลาปัจจุบัน
/*function updateClock() {
    const clockElement = document.getElementById('clock');
    const now = new Date();
    const hours = now.getHours().toString().padStart(2, '0'); // ชั่วโมง
    const minutes = now.getMinutes().toString().padStart(2, '0'); // นาที
    const seconds = now.getSeconds().toString().padStart(2, '0'); // วินาที
    const timeString = `${hours}:${minutes}:${seconds}`; // รวมเป็นรูปแบบ HH:MM:SS
    clockElement.textContent = timeString; // แสดงเวลา
}

// อัปเดตเวลาในทุกๆ วินาที
setInterval(updateClock, 1000);
*/

let targetText = "CYBERTRON"; // ข้อความเป้าหมาย
let currentText = [];         // อาเรย์เก็บตัวอักษรที่แสดง
let targetIndex = 0;          // ตัวชี้ตำแหน่งใน targetText
let animationSpeed = 77;      // ความเร็วในการแสดงข้อความ (มิลลิวินาที)

function animateText() {
    if (targetIndex < targetText.length) {
        let currentTarget = targetText.charAt(targetIndex); // ตัวอักษรเป้าหมาย
        let currentLetter = getNextLetter(currentText[targetIndex] || ''); // หาอักษรถัดไปจาก A-Z
        
        // ถ้าตัวอักษรที่เรากำลังแสดงตรงกับตัวใน targetText
        if (currentLetter === currentTarget) {
            currentText[targetIndex] = currentLetter;
            targetIndex++; // ไปยังตัวอักษรถัดไปใน targetText
        } else {
            currentText[targetIndex] = currentLetter;
        }
    }

    // แสดงข้อความที่ได้จาก currentText พร้อมเคอร์เซอร์กระพริบ
    document.getElementById("animation-text").innerHTML = currentText.join('') + '<span class="typing-cursor"></span>';
}

// ฟังก์ชันหาอักษรถัดไป (A-Z)
function getNextLetter(current) {
    let charCode = current ? current.charCodeAt(0) : 64; // ถ้ายังไม่มีค่าให้เริ่มที่ 64 ('@' -> 'A')
    return String.fromCharCode(charCode + 1);
}

// เรียก animateText ทุกๆ "animationSpeed" มิลลิวินาที
setInterval(animateText, animationSpeed);

console.log("isShowingAnswer:", isShowingAnswer);
console.log("currentQuestionIndex:", currentQuestionIndex);

// เพิ่มการควบคุม Settings
const settingsModal = document.getElementById('settings-modal');
const settingsButton = document.querySelector('.setting-btn'); // เลือกปุ่มที่มี class "setting-btn"
const closeSettingsButton = document.getElementById('close-settings');
const bgmVolume = document.getElementById('bgm-volume');
const sfxVolume = document.getElementById('sfx-volume');
const muteCheckbox = document.getElementById('mute');

// เปิดหน้า Settings
settingsButton.addEventListener('click', () => {
    settingsModal.style.display = 'flex';
});

// ปิดหน้า Settings
closeSettingsButton.addEventListener('click', () => {
    settingsModal.style.display = 'none';
});


// ควบคุม BGM Volume
bgmVolume.addEventListener('input', (e) => {
    const volume = e.target.value;
    // ใส่โค้ดควบคุม BGM Volume ตรงนี้
    console.log('BGM Volume:', volume);
});

// ควบคุม SFX Volume
sfxVolume.addEventListener('input', (e) => {
    const volume = e.target.value;
    // ใส่โค้ดควบคุม SFX Volume ตรงนี้
    console.log('SFX Volume:', volume);
});

// ควบคุม Mute
muteCheckbox.addEventListener('change', (e) => {
    const isMuted = e.target.checked;
    // ใส่โค้ดควบคุม Mute ตรงนี้
    console.log('Mute:', isMuted);
});


const bgmAudio = document.getElementById('bgm');
const sfxAudio = document.getElementById('sfx');

// ควบคุม BGM Volume
bgmVolume.addEventListener('input', (e) => {
    const volume = e.target.value;
    bgmAudio.volume = volume;
});

// ควบคุม SFX Volume
sfxVolume.addEventListener('input', (e) => {
    const volume = e.target.value;
    sfxAudio.volume = volume;
});

// ควบคุม Mute
muteCheckbox.addEventListener('change', (e) => {
    const isMuted = e.target.checked;
    bgmAudio.muted = isMuted;
    sfxAudio.muted = isMuted;
});

document.addEventListener('DOMContentLoaded', () => {
    const bgmAudio = document.getElementById('bgm');
    bgmAudio.muted = false; // ยกเลิก muted
    bgmAudio.play(); // พยายามเล่นเสียงอีกครั้ง
});

document.addEventListener('click', () => {
    const bgmAudio = document.getElementById('bgm');
    if (bgmAudio.paused) {
        bgmAudio.muted = false;
        bgmAudio.play();
    }
}, { once: true }); // ให้ทำงานแค่ครั้งเดียว

  
document.querySelector('.logout-btn').addEventListener('click', function() {
    alert('LOGOUT button clicked!');
    // เพิ่มโค้ดสำหรับการทำงานของปุ่ม LOGOUT ที่นี่
  });

document.getElementById("menu-toggle").addEventListener("change", function() {
    document.querySelector(".menu-buttons").classList.toggle("active");
});

const menuButtons = document.querySelector(".menu-buttons");
const toggleMenuButton = document.querySelector("#toggle-menu"); // ปุ่มเปิด-ปิดเมนู


