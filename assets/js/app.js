document.addEventListener('DOMContentLoaded', () => {
    const mailItems = document.querySelectorAll('.mail-item');
    mailItems.forEach((item) => {
        item.addEventListener('click', () => {
            mailItems.forEach((el) => el.classList.remove('selected'));
            item.classList.add('selected');
        });
    });
});
