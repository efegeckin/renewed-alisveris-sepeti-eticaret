document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
    button.addEventListener('click', function () {
        const input = document.querySelector(button.dataset.togglePassword);
        if (!input) return;
        input.type = input.type === 'password' ? 'text' : 'password';
        button.innerHTML = input.type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
    });
});
