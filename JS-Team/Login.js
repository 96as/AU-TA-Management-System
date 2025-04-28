document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("form").addEventListener("submit", function (e) {
      const username = document.querySelector('input[name="username"]').value.trim();
      const password = document.querySelector('input[name="password"]').value.trim();
  
      if (!username && !password) {
        e.preventDefault();
        alert("Please enter both your username and password.");
      } else if (!username) {
        e.preventDefault();
        alert("Please enter your username.");
      } else if (!username.endsWith("@alfaisal.edu")) {
        e.preventDefault();
        alert("Your username must end username with @alfaisal.edu.");
      } else if (!password) {
        e.preventDefault();
        alert("Please enter your password.");
      }
    });
  });
  
