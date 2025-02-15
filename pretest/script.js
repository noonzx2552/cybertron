const questions = [
    {
        question: "ข้อมูลที่เป็นส่วนตัวและไม่ควรเปิดเผยทางออนไลน์มากที่สุดคืออะไร",
        options: ["ชื่อเล่น", "หมายเลขโทรศัพท์", "วันเกิด", "รหัสผ่าน"],
        answer: "รหัสผ่าน"
    },
    {
        question: "ข้อใดต่อไปนี้เป็นตัวอย่างของมัลแวร์ (Malware)?",
        options: ["ซอฟต์แวร์ตัดต่อรูปภาพ", "โปรแกรมสแกนไวรัส", "แอดแวร์ (Adware)", "ระบบปฏิบัติการ"],
        answer: "แอดแวร์ (Adware)"
    },
    {
        question: "หากคุณได้รับอีเมลที่ขอข้อมูลส่วนตัวพร้อมลิงก์ให้กดเข้าไป คุณควรทำอย่างไร",
        options: ["กดลิงก์ทันที", "ลบอีเมลทิ้งทันที", "ตรวจสอบที่อยู่อีเมลผู้ส่งก่อนดำเนินการ", "ตอบกลับอีเมลเพื่อถามข้อมูลเพิ่มเติม"],
        answer: "ตรวจสอบที่อยู่อีเมลผู้ส่งก่อนดำเนินการ"
    },
    {
        question: "VPN (Virtual Private Network) มีประโยชน์อย่างไร?",
        options: ["ช่วยบล็อกเว็บไซต์", "ป้องกันการเข้าถึงเว็บไซต์ต้องห้าม", "เข้ารหัสข้อมูลเพื่อเพิ่มความปลอดภัย", "ใช้สำหรับโหลดไฟล์เท่านั้น"],
        answer: "เข้ารหัสข้อมูลเพื่อเพิ่มความปลอดภัย"
    },
    {
        question: "การใช้รหัสผ่านที่ดีควรมีลักษณะอย่างไร?",
        options: ["ใช้เฉพาะตัวเลขเท่านั้น", "ใช้ชื่อสัตว์เลี้ยงที่คุณชื่นชอบ", "ผสมตัวเลข ตัวอักษร และสัญลักษณ์", "ใช้รหัสผ่านเดียวกันกับทุกบัญชี"],
        answer: "ผสมตัวเลข ตัวอักษร และสัญลักษณ์"
    },
    {
        question: "ข้อใดคือวิธีที่ปลอดภัยที่สุดในการตรวจสอบว่าเว็บไซต์ที่คุณเข้าชมปลอดภัย?",
        options: ["เว็บไซต์มีสีสันสวยงาม", "มีไอคอนรูปแม่กุญแจในช่อง URL", "เว็บไซต์มีเนื้อหาที่ดูน่าเชื่อถือ", "เว็บไซต์โหลดเร็ว"],
        answer: "มีไอคอนรูปแม่กุญแจในช่อง URL"
    },
    {
        question: "สิ่งที่เรียกว่า Phishing คืออะไร?",
        options: ["การแฮกระบบคอมพิวเตอร์", "การล่อลวงข้อมูลส่วนตัวผ่านอีเมลหรือเว็บไซต์ปลอม", "การป้องกันไวรัส", "การใช้ไฟร์วอลล์ (Firewall)"],
        answer: "การล่อลวงข้อมูลส่วนตัวผ่านอีเมลหรือเว็บไซต์ปลอม"
    },
    {
        question: "ข้อใดคือหน้าที่หลักของซอฟต์แวร์ป้องกันไวรัส (Antivirus)?",
        options: ["ปรับปรุงความเร็วอินเทอร์เน็ต", "ค้นหาและกำจัดมัลแวร์", "จัดเก็บข้อมูลสำรอง", "ทำให้คอมพิวเตอร์ประหยัดพลังงาน"],
        answer: "ค้นหาและกำจัดมัลแวร์"
    },
    {
        question: "การตั้งค่าความเป็นส่วนตัว (Privacy Settings) ในโซเชียลมีเดียมีประโยชน์อย่างไร?",
        options: ["ทำให้โปรไฟล์ดูน่าสนใจขึ้น", "จำกัดการเข้าถึงข้อมูลส่วนตัวของผู้ใช้งาน", "ป้องกันการถูกแฮก 100%", "เพิ่มยอดผู้ติดตาม"],
        answer: "จำกัดการเข้าถึงข้อมูลส่วนตัวของผู้ใช้งาน"
    },
    {
        question: "ข้อใดเป็นพฤติกรรมที่ควรหลีกเลี่ยงเพื่อความปลอดภัยทางอินเทอร์เน็ต?",
        options: ["อัปเดตซอฟต์แวร์เป็นประจำ", "ดาวน์โหลดไฟล์จากเว็บไซต์ที่ไม่ปลอดภัย", "ใช้รหัสผ่านที่ซับซ้อน", "ตรวจสอบ URL ก่อนคลิกลิงก์"],
        answer: "ดาวน์โหลดไฟล์จากเว็บไซต์ที่ไม่ปลอดภัย"
    }
];

let currentQuestionIndex = 0;
let score = 0;

function prevQuestion() {
    if (currentQuestionIndex > 0) {
        currentQuestionIndex--; // ลด index ของคำถามปัจจุบัน
        loadQuestion(); // โหลดคำถามก่อนหน้า
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

    // ตรวจสอบว่าผู้ใช้เคยเลือกคำตอบในคำถามนี้หรือไม่
    const selectedOption = currentQuestion.selectedOption;
    if (selectedOption) {
        const options = document.querySelectorAll(".option");
        options.forEach(option => {
            if (option.textContent === selectedOption) {
                option.classList.add("selected");
            }
        });
    }
}

function selectOption(selectedOption) {
    const options = document.querySelectorAll(".option");
    options.forEach(option => {
        option.classList.remove("selected");
        if (option.textContent === selectedOption) {
            option.classList.add("selected");
        }
    });

    // บันทึกคำตอบที่ผู้ใช้เลือกไว้ในคำถามปัจจุบัน
    questions[currentQuestionIndex].selectedOption = selectedOption;
}

function nextQuestion() {
    const selectedOption = document.querySelector(".option.selected");
    if (!selectedOption) {
        alert("กรุณาเลือกคำตอบก่อน");
        return;
    }

    const currentQuestion = questions[currentQuestionIndex];
    if (selectedOption.textContent === currentQuestion.answer) {
        score++;
    }

    currentQuestionIndex++;
    if (currentQuestionIndex < questions.length) {
        loadQuestion();
    } else {
        showResult();
    }
}

function showResult() {
    document.querySelector(".quiz-container").style.display = "none";
    document.querySelector(".result-container").style.display = "block";
    document.getElementById("score").textContent = `คุณได้คะแนน ${score} จาก ${questions.length} คะแนน`;

    // ✅ ส่งคะแนนไปยัง PHP
    sendScoreToServer(score);
}
function sendScoreToServer(score) {
    console.log("ส่งคะแนนไปที่เซิร์ฟเวอร์:", score); // เช็คว่าฟังก์ชันทำงานไหม

    fetch('score.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ score: score })
    })
    .then(response => response.json())
    .then(data => {
        console.log("เซิร์ฟเวอร์ตอบกลับ:", data);
        if (data.status === "success") {
            alert("บันทึกคะแนนสำเร็จ: " + data.score);
        } else {
            alert("เกิดข้อผิดพลาด: " + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}




function restartQuiz() {
    currentQuestionIndex = 0;
    score = 0;

    // รีเซ็ตการเลือกคำตอบในทุกคำถาม
    questions.forEach(question => {
        question.selectedOption = null; // ลบคำตอบที่เคยเลือกไว้
    });

    // ซ่อนผลลัพธ์และแสดงคำถามใหม่
    document.querySelector(".quiz-container").style.display = "block";
    document.querySelector(".result-container").style.display = "none";

    // โหลดคำถามแรกใหม่
    loadQuestion();
}

// โหลดคำถามแรกเมื่อหน้าเว็บโหลดเสร็จ
window.onload = loadQuestion;


// ฟังก์ชันสำหรับแสดงเวลาปัจจุบัน
function updateClock() {
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
