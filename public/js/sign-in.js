// document.getElementById("loginForm").addEventListener("submit", function(e) {
//   e.preventDefault();

//   const email = document.getElementById("email").value;
//   const password = document.getElementById("password").value;

//   const users = [
//     { email: "officer1@mcu.edu", password: "123", role: "officer" },
//     { email: "officer2@mcu.edu", password: "123", role: "officer" },
//     { email: "officer3@mcu.edu", password: "123", role: "officer" },
//     { email: "advisor@mcu.edu", password: "123", role: "advisor" },
//     { email: "admin@mcu.edu", password: "123", role: "admin-it" },
//     { email: "chair@mcu.edu", password: "123", role: "program-chair" }
//   ];

//   const user = users.find(u => u.email === email && u.password === password);

//   if (user) {
//     localStorage.setItem("userRole", user.role);
//     window.location.href = "dashboard.html";
//   } else {
//     alert("Invalid credentials.");
//   }
// });