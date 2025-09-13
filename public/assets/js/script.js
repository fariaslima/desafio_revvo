const modal = document.getElementById('first-login-modal');
if (modal) {
    modal.style.display = 'flex';

    document.getElementById('close-modal').addEventListener('click', () => {
        modal.style.display = 'none';

        fetch('/mark-modal-shown', { method: 'POST' });
    });
}
