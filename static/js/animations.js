document.addEventListener('DOMContentLoaded', () => {
    const postCards = document.querySelectorAll('.post-card');

    const fadeIn = (entry) => {
        entry.target.classList.add('fade-in');
        observer.unobserve(entry.target);
    };

    const options = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                fadeIn(entry);
            }
        });
    }, options);

    postCards.forEach(card => {
        observer.observe(card);
    });
});