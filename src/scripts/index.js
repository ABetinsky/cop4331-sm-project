const authModal = document.querySelector(".auth-modal");
const loginLink = document.querySelector(".login-link");
const registerLink = document.querySelector(".register-link");

const alertBox = document.querySelector(".alert-box");

registerLink.addEventListener("click", () => {
  authModal.classList.add("active");
});

loginLink.addEventListener("click", () => {
  authModal.classList.remove("active");
});

if (loginBtnModal)
  loginBtnModal: addEventListener("click", () => authModal.classList("show"));

setTimeout(() => alertBox.classList.add("show"), 50);

setTimeout(() => {
  alertBox.classList.remove('show');
  setTimeout(() => alertBox.remove(), 500)
}, 3000);

/* Signup functionality */
const signupForm = document.getElementById("signup-form");
signupForm.addEventListener('submit', async (event) => {
  event.preventDefault();

  const formData = new FormData(event.target);
  const url = "/api/user/signup.php";

  // Build payload from form data 
  const payload = {
    username: formData.get("username"),
    email: formData.get("email"),
    password: formData.get("password"),
    cpassword: formData.get("confirm-password"),
  }

  try {
    const request = await fetch(url, {
      method: "POST",
      body: JSON.stringify(payload)
    });

    if (request.ok) {
      console.log("Successfully created new account");
    }
  } catch (e) {
    console.error("Error creating an acconut:", e);
  }
})
