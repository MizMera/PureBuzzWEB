document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling to all links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Form validation for the join us form
    const joinForm = document.querySelector('form');
    if (joinForm) {
        joinForm.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const experience = document.getElementById('experience').value.trim();
            const whyJoin = document.getElementById('why_join').value.trim();

            if (name === '' || email === '' || phone === '' || experience === '' || whyJoin === '') {
                e.preventDefault();
                alert('Please fill out all fields before submitting.');
            }
        });
    }
});

