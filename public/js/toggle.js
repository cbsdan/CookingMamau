document.addEventListener('DOMContentLoaded', function () {
    const btnRegister = document.getElementById('btn-register');
    const btnLogin = document.getElementById('btn-login');
    const toggleIndicator = document.querySelector('.btn-toggle-indicator');

    function updateIndicator() {
        const btnWidth = btnRegister.offsetWidth;
        const btnContainerWidth = btnRegister.parentElement.offsetWidth;
        const indicatorWidth = toggleIndicator.offsetWidth;

        if (btnRegister.classList.contains('active')) {
            toggleIndicator.style.transform = 'translateX(0)';
        } else if (btnLogin.classList.contains('active')) {
            toggleIndicator.style.transform = `translateX(${btnWidth}px)`;
        }
    }

    btnRegister.addEventListener('click', function () {
        if (!this.classList.contains('active')) {
            this.classList.add('active');
            btnLogin.classList.remove('active');
            updateIndicator();
            setTimeout(() => {
                window.location.href = "/register";
            }, 300); // Delay to match the CSS transition duration
        }
    });

    btnLogin.addEventListener('click', function () {
        if (!this.classList.contains('active')) {
            this.classList.add('active');
            btnRegister.classList.remove('active');
            updateIndicator();
            setTimeout(() => {
                window.location.href = "/login";
            }, 300); // Delay to match the CSS transition duration
        }
    });

    // Initialize the indicator position
    updateIndicator();
});
