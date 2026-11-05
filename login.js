document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('login-form') || document.getElementById('register-form');
    if (!form) return;

    const message = document.getElementById('message');
    const button = form.querySelector('button[type="submit"]');
    const endpoint = form.id === 'login-form' ? 'login.php' : 'registrs.php';

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        button.disabled = true;
        message.textContent = '';
        message.className = 'zinojums';

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                body: new FormData(form),
            });
            const data = await response.json();

            message.textContent = data.message;
            message.classList.add(data.ok ? 'ok' : 'err');

            if (data.ok && data.redirect) {
                setTimeout(() => { window.location.href = data.redirect; }, 800);
            }
        } catch (error) {
            message.textContent = 'Nevar sazināties ar serveri.';
            message.classList.add('err');
        } finally {
            button.disabled = false;
        }
    });
});