/*=============== SHOW HIDDEN - PASSWORD ===============*/
const showHiddenPass = (loginPass, loginEye) =>{
   const input = document.getElementById(loginPass),
         iconEye = document.getElementById(loginEye)

   iconEye.addEventListener('click', () =>{
      // Change password to text
      if(input.type === 'password'){
         // Switch to text
         input.type = 'text'

         // Icon change
         iconEye.classList.add('ri-eye-line')
         iconEye.classList.remove('ri-eye-off-line')
      } else{
         // Change to password
         input.type = 'password'

         // Icon change
         iconEye.classList.remove('ri-eye-line')
         iconEye.classList.add('ri-eye-off-line')
      }
   })
}

// ฟังก์ชันตรวจสอบรหัสผ่าน
function validatePassword() {
   const password = document.getElementById("register-pass").value;
   const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{6,}$/;

   if (!regex.test(password)) {
      alert("Password must be at least 6 characters long, with lowercase, uppercase, a number, and a special character.");
      return false;
   }
   return true;
}

function flip() {
   const loginContainer = document.querySelector('.login-container');
   loginContainer.classList.toggle('flipped');
}


showHiddenPass('login-pass','login-eye')