        // Preloader
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            setTimeout(() => {
                preloader.style.opacity = '0';
                preloader.style.visibility = 'hidden';
            }, 1500);
        });

      

      // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
// ظهور أيقونة الواتساب عند التمرير لأسفل
window.addEventListener('scroll', function() {
    const whatsappFloat = document.querySelector('.whatsapp-float');
    const scrollPosition = window.scrollY;
    
    // تظهر الأيقونة بعد التمرير 300px
    if (scrollPosition > 300) {
        whatsappFloat.classList.add('show');
    } else {
        whatsappFloat.classList.remove('show');
    }
});

// أو بدلاً من التمرير، تظهر بعد 3 ثواني من تحميل الصفحة
// setTimeout(function() {
//     document.querySelector('.whatsapp-float').classList.add('show');
// }, 3000);
        // Mobile menu functionality
        const menuToggle = document.getElementById('menuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuOverlay = document.getElementById('menuOverlay');
        const body = document.body;

        function toggleMenu() {
            mobileMenu.classList.toggle('active');
            menuOverlay.classList.toggle('active');
            body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
            
            // Change menu icon
            const icon = menuToggle.querySelector('i');
            if (mobileMenu.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        }

        menuToggle.addEventListener('click', toggleMenu);
        menuOverlay.addEventListener('click', toggleMenu);

        // Close menu when clicking on a link
        const mobileNavItems = document.querySelectorAll('.mobile-nav-links a, .mobile-cta a');
        mobileNavItems.forEach(item => {
            item.addEventListener('click', toggleMenu);
        });

        // Prevent scrolling when menu is open
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                toggleMenu();
            }
        });
       
        // Scroll Animations
        const fadeElements = document.querySelectorAll('.fade-in');
        
        const fadeInOnScroll = () => {
            fadeElements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;
                
                if (elementTop < window.innerHeight - elementVisible) {
                    element.classList.add('visible');
                }
            });
        };
        
        window.addEventListener('scroll', fadeInOnScroll);
        window.addEventListener('load', fadeInOnScroll);

        // 3D Card Effect
        const cards3D = document.querySelectorAll('.image-3d, .product-card, .feature-card');
        
        cards3D.forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const cardRect = card.getBoundingClientRect();
                const x = e.clientX - cardRect.left;
                const y = e.clientY - cardRect.top;
                
                const centerX = cardRect.width / 2;
                const centerY = cardRect.height / 2;
                
                const angleY = (x - centerX) / 20;
                const angleX = (centerY - y) / 20;
                
                card.style.transform = `perspective(1000px) rotateY(${angleY}deg) rotateX(${angleX}deg) scale3d(1.02, 1.02, 1.02)`;
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateY(0) rotateX(0) scale3d(1, 1, 1)';
            });
        });

        // 3D Jewelry Interaction
        const floatingJewels = document.querySelectorAll('.floating-jewel');
        
        document.addEventListener('mousemove', (e) => {
            if (window.innerWidth > 992) {
                const x = e.clientX / window.innerWidth;
                const y = e.clientY / window.innerHeight;
                
                floatingJewels.forEach((jewel, index) => {
                    const delay = index * 0.1;
                    jewel.style.transform = `translateY(-50%) rotateY(${x * 30}deg) rotateX(${-y * 30}deg) scale(${1 + (y * 0.1)})`;
                    jewel.style.transition = `transform 0.5s cubic-bezier(0.23, 1, 0.32, 1) ${delay}s`;
                });
            }
        });

        // Scroll to Top Button
        const scrollToTopBtn = document.querySelector('.scroll-to-top');
        
        window.addEventListener('scroll', () => {
            if (window.scrollY > 500) {
                scrollToTopBtn.classList.add('active');
            } else {
                scrollToTopBtn.classList.remove('active');
            }
        });
        
        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });

        
        // Product Rating Animation
        const productRatings = document.querySelectorAll('.product-rating');
        productRatings.forEach(rating => {
            const stars = rating.querySelectorAll('.fas.fa-star, .fas.fa-star-half-alt, .far.fa-star');
            stars.forEach(star => {
                star.style.animation = 'sparkle 2s infinite alternate';
                star.style.animationDelay = `${Math.random() * 2}s`;
            });
        });

        // Floating Ring Effect - NEW
        const floatingRing = document.getElementById('floatingRing');
        
        // Show ring on mouse move
        document.addEventListener('mousemove', (e) => {
            floatingRing.style.left = `${e.clientX - 40}px`;
            floatingRing.style.top = `${e.clientY - 40}px`;
            floatingRing.classList.add('active');
        });
        
        // Hide ring when not moving
        let mouseMoveTimer;
        document.addEventListener('mousemove', () => {
            clearTimeout(mouseMoveTimer);
            floatingRing.classList.add('active');
            
            mouseMoveTimer = setTimeout(() => {
                floatingRing.classList.remove('active');
            }, 3000);
        });
        
        // Section Highlight on Scroll - NEW
        const sections = document.querySelectorAll('.section-highlight');
        
        const highlightOnScroll = () => {
            sections.forEach(section => {
                const sectionTop = section.getBoundingClientRect().top;
                const sectionHeight = section.getBoundingClientRect().height;
                
                if (sectionTop < window.innerHeight - 100 && sectionTop > -sectionHeight + 100) {
                    section.classList.add('active');
                } else {
                    section.classList.remove('active');
                }
            });
        };
        const track = document.getElementById('sliderTrack');

// نسخ جميع الصور تلقائياً لتكرار الحركة بدون فجوة
const boxes = Array.from(track.children);
boxes.forEach(box => {
  const clone = box.cloneNode(true);
  track.appendChild(clone);
});

let position = 0;
let speed = 1; // سرعة الحركة

function animate() {
  position += speed; // حركة لليمين
  if (position >= track.scrollWidth / 2) {
    position = 0; // إعادة الحركة عند نهاية النسخة الأصلية
  }
  track.style.transform = `translateX(${position}px)`;
  requestAnimationFrame(animate);
}

animate();

        window.addEventListener('scroll', highlightOnScroll);
        window.addEventListener('load', highlightOnScroll);
   