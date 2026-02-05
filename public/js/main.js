function showToast(type, message) {
  const container = document.getElementById('toast-container');
  
  // Create toast element
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  
  // Determine icon based on type
  const icon = type === 'success' ? '✓' : '✕';
  const title = type.charAt(0).toUpperCase() + type.slice(1);

  toast.innerHTML = `
    <div class="toast-sidebar ${type}"></div>
    <div class="toast-icon">${icon}</div>
    <div class="toast-content">
      <span class="toast-title">${title}</span>
      <span class="toast-msg">${message}</span>
    </div>
    <div class="toast-close" onclick="this.parentElement.remove()">Close</div>
  `;

  container.appendChild(toast);

  // Auto-remove after 4 seconds
  setTimeout(() => {
    if (toast.parentElement) {
      toast.style.opacity = '0';
      setTimeout(() => toast.remove(), 300);
    }
  }, 4000);
}



const passwordInput = document.getElementById("password");
const confirmPasswordInput = document.getElementById("confirm_password");
const errorMessage = document.getElementById("passwordMatchError");
const passwordMatchIcon = document.getElementById("passwordMatchIcon");



confirmPasswordInput.addEventListener('keyup', function () {
    if (passwordInput.value !== confirmPasswordInput.value) {
        errorMessage.textContent = "Mots de passe ne correspondent pas!";
        passwordMatchIcon.style.display = "inline";
    } else {
        errorMessage.textContent = "";
        passwordMatchIcon.style.display = "none";
    }
});