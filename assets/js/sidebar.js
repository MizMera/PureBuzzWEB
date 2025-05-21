let lastScrollTop = 0;
const sidebar = document.querySelector('.sidebar');
const mainPanel = document.querySelector('.main-panel');
const scrollThreshold = 100;

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
    
    if (currentScroll > scrollThreshold) {
        if (currentScroll > lastScrollTop) {
            sidebar.classList.add('hidden');
            mainPanel.classList.add('expanded');
        } else {
            sidebar.classList.remove('hidden');
            mainPanel.classList.remove('expanded');
        }
    } else {
        sidebar.classList.remove('hidden');
        mainPanel.classList.remove('expanded');
    }
    
    lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
});

sidebar.addEventListener('mouseenter', () => {
    sidebar.classList.remove('hidden');
    mainPanel.classList.remove('expanded');
});

sidebar.addEventListener('mouseleave', () => {
    if (window.pageYOffset > scrollThreshold) {
        sidebar.classList.add('hidden');
        mainPanel.classList.add('expanded');
    }
});