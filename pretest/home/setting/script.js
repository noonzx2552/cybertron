
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
    window.location.href = '../../logout.php'; // Redirect to logout.php
});

document.querySelector('.dash-btn').addEventListener('click', function() {
    window.location.href = 'dashboard/dashboard.php'; // Redirect to logout.php
});

document.getElementById("menu-toggle").addEventListener("change", function() {
    document.querySelector(".menu-buttons").classList.toggle("active");
});

document.querySelector('.home-btn').addEventListener('click', function() {
    window.location.href = 'home.php'; // Redirect to logout.php
});

const menuButtons = document.querySelector(".menu-buttons");
const toggleMenuButton = document.querySelector("#toggle-menu"); // ปุ่มเปิด-ปิดเมนู

