/**
 * Навбар сжимается при скролле
 */
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.getElementById('mainNavbar');

    if (navbar) {
        const handleScroll = () => {
            if (window.scrollY > 50) {
                navbar.classList.add('shrink');
            } else {
                navbar.classList.remove('shrink');
            }
        };

        window.addEventListener('scroll', handleScroll);
        handleScroll(); // Вызов при загрузке
    }
});

/**
 * Анимация элементов при скролле
 */
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));

/**
 * Инициализация Swiper (если есть)
 */
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Swiper !== 'undefined') {
        const swiperElements = document.querySelectorAll('.swiper');
        swiperElements.forEach(el => {
            new Swiper(el, {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        });
    }
});

/**
 * Автоматическое скрытие уведомлений через 5 секунд
 */
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.click();
            }
        }, 5000);
    });
});
// Для работы выпадающих подменю на всех устройствах
document.addEventListener('DOMContentLoaded', function() {
    // Для десктопов - hover
    if (window.innerWidth > 992) {
        let submenus = document.querySelectorAll('.dropdown-submenu');
        submenus.forEach(function(submenu) {
            submenu.addEventListener('mouseenter', function(e) {
                let menu = this.querySelector('.dropdown-menu');
                if (menu) {
                    menu.style.display = 'block';
                }
            });
            submenu.addEventListener('mouseleave', function(e) {
                let menu = this.querySelector('.dropdown-menu');
                if (menu) {
                    menu.style.display = 'none';
                }
            });
        });
    }

    // Для мобильных - клик
    let submenuToggles = document.querySelectorAll('.dropdown-submenu > .dropdown-toggle');
    submenuToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            if (window.innerWidth <= 992) {
                e.preventDefault();
                e.stopPropagation();
                let parent = this.closest('.dropdown-submenu');
                let menu = parent.querySelector('.dropdown-menu');
                if (menu) {
                    let isVisible = menu.style.display === 'block';
                    document.querySelectorAll('.dropdown-submenu .dropdown-menu').forEach(function(m) {
                        m.style.display = 'none';
                    });
                    menu.style.display = isVisible ? 'none' : 'block';
                }
            }
        });
    });
});
