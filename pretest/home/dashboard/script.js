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


document.getElementById("menu-toggle").addEventListener("change", function() {
    document.querySelector(".menu-buttons").classList.toggle("active");
});

document.querySelector('.home-btn').addEventListener('click', function() {
    window.location.href = '../home.php'; // Redirect to logout.php
});

const menuButtons = document.querySelector(".menu-buttons");
const toggleMenuButton = document.querySelector("#toggle-menu"); // ปุ่มเปิด-ปิดเมนู

