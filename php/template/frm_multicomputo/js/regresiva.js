document.addEventListener('DOMContentLoaded', (event) => {
    const endDate = new Date('2024-12-31T23:59:59').getTime();

    const countdown = () => {
        const now = new Date().getTime();
        const timeRemaining = endDate - now;

        if (timeRemaining < 0) {
            clearInterval(timerInterval);
            document.querySelector('.countdown').innerHTML = "¡Tiempo completado!";
            return;
        }

        const days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

        document.getElementById('days').innerText = days;
        document.getElementById('hours').innerText = hours;
        document.getElementById('minutes').innerText = minutes;
        document.getElementById('seconds').innerText = seconds;
    };

    const timerInterval = setInterval(countdown, 1000);
    countdown(); // Initial call to set the countdown immediately
});
