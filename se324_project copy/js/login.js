document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const usernameInput = document.querySelector('input[name="username"]');
    const passwordInput = document.querySelector('input[name="password"]');

    form.addEventListener("submit", function (e) {
        const username = usernameInput.value.trim();
        const password = passwordInput.value.trim();
        let hasError = false;

        // Clear previous error messages
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(msg => msg.remove());

        // Validate username
        if (!username) {
            showError("Please enter your username.");
            hasError = true;
        } else if (!username.endsWith("@alfaisal.edu")) {
            showError("Your username must end with @alfaisal.edu.");
            hasError = true;
        }

        // Validate password
        if (!password) {
            showError("Please enter your password.");
            hasError = true;
        }

        if (hasError) {
            e.preventDefault();
        }
    });

    function showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        form.insertBefore(errorDiv, form.firstChild);
    }
});
  