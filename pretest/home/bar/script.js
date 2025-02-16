// คำสั่งเลือก Elements จาก HTML
const settingsModal = document.getElementById('settings-modal');
const settingsButton = document.querySelector('.setting-btn'); // ปุ่ม Settings
const closeSettingsButton = document.getElementById('close-settings');
const bgmVolume = document.getElementById('bgm-volume');
const sfxVolume = document.getElementById('sfx-volume');
const muteCheckbox = document.getElementById('mute');

// โหลดองค์ประกอบเสียง
const bgmAudio = new Audio('../../../assets/audio/bgm.mp3'); // ใส่ไฟล์ BGM
const sfxAudio = new Audio('../audio/sfx.mp3'); // ใส่ไฟล์ SFX

// ตั้งค่าเริ่มต้นของเสียง
bgmAudio.loop = true; // เล่นซ้ำ
bgmAudio.volume = 1.0;
sfxAudio.volume = 1.0;

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
    const volume = parseFloat(e.target.value);
    bgmAudio.volume = volume;
    console.log('🔊 BGM Volume:', volume);
});

// ควบคุม SFX Volume
sfxVolume.addEventListener('input', (e) => {
    const volume = parseFloat(e.target.value);
    sfxAudio.volume = volume;
    console.log('🎵 SFX Volume:', volume);
});

// ควบคุม Mute (ปิดเสียงทั้งหมด)
muteCheckbox.addEventListener('change', (e) => {
    const isMuted = e.target.checked;
    bgmAudio.muted = isMuted;
    sfxAudio.muted = isMuted;
    console.log('🔇 Mute:', isMuted);
});

// เล่น BGM อัตโนมัติหลังจากการโต้ตอบของผู้ใช้
document.addEventListener('click', () => {
    if (bgmAudio.paused) {
        bgmAudio.play();
    }
}, { once: true });

// ฟังก์ชันเล่นเสียง SFX
function playSFX() {
    sfxAudio.currentTime = 0; // เริ่มเสียงใหม่ทุกครั้ง
    sfxAudio.play();
}

// ปุ่ม Logout
document.querySelector('.logout-btn').addEventListener('click', function() {
    window.location.href = '../../logout.php';
});

// ปุ่ม Home
document.querySelector('.home-btn').addEventListener('click', function() {
    window.location.href = '../home.php';
});

// เมนู Toggle
document.getElementById("menu-toggle").addEventListener("change", function() {
    document.querySelector(".menu-buttons").classList.toggle("active");
});

// ปุ่มเมนู Toggle
const menuButtons = document.querySelector(".menu-buttons");
const toggleMenuButton = document.querySelector("#toggle-menu");
