// الكود الكامل للجافاسكريبت
class Dashboard {
    constructor() {
        this.currentSection = 'dashboard-section';
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadDashboard();
    }

    bindEvents() {
        // أحداث التنقل
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            link.addEventListener('click', (e) => this.handleNavigation(e));
        });

        // أحداث الأزرار
        document.getElementById('logoutBtn').addEventListener('click', () => this.logout());
        document.getElementById('globalSearch').addEventListener('input', (e) => this.handleSearch(e));

        // إضافة المستمعين للأزرار الديناميكية
        this.bindDynamicButtons();
    }

    async handleNavigation(e) {
        e.preventDefault();
        const targetSection = e.target.closest('a').getAttribute('data-section');
        
        if (targetSection === this.currentSection) return;

        // تحديث القائمة النشطة
        document.querySelectorAll('.sidebar-menu a').forEach(item => item.classList.remove('active'));
        e.target.closest('a').classList.add('active');

        // تحميل المحتوى
        await this.loadSection(targetSection);
        this.currentSection = targetSection;
    }

    async loadSection(section) {
        try {
            const response = await this.apiCall('get_' + section.replace('-section', ''));
            this.renderSection(section, response.data);
        } catch (error) {
            this.showNotification('خطأ في تحميل البيانات', 'danger');
        }
    }

    renderSection(section, data) {
        const content = document.querySelector('.content');
        // بناء واجهة القسم بناءً على البيانات
        // ... (الكود الكامل لبناء الواجهات)
    }

    async apiCall(action, data = {}) {
        const formData = new FormData();
        formData.append('action', action);
        
        for (const key in data) {
            if (data[key] instanceof File) {
                formData.append(key, data[key]);
            } else {
                formData.append(key, data[key]);
            }
        }

        const response = await fetch('api.php', {
            method: 'POST',
            body: formData
        });

        return await response.json();
    }

    showNotification(message, type = 'info') {
        const notification = document.getElementById('notification');
        notification.textContent = message;
        notification.className = `notification ${type}`;
        notification.classList.remove('hidden');

        setTimeout(() => {
            notification.classList.add('hidden');
        }, 3000);
    }

    logout() {
        if (confirm('هل تريد تسجيل الخروج؟')) {
            window.location.href = 'logout.php';
        }
    }

    // دوال إضافية للتعامل مع البيانات...
}

// تهيئة التطبيق عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', () => {
    new Dashboard();
});

// تأثيرات إضافية للوحة التحكم
class DashboardAnimations {
    constructor() {
        this.init();
    }

    init() {
        this.addFloatingEffects();
        this.addScrollAnimations();
        this.addHoverEffects();
    }

    addFloatingEffects() {
        // إضافة تأثير الطفو للبطاقات
        const cards = document.querySelectorAll('.stat-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    }

    addScrollAnimations() {
        // تأثيرات التمرير
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.section').forEach(section => {
            observer.observe(section);
        });
    }

    addHoverEffects() {
        // تأثيرات التمرير للجداول
        const tableRows = document.querySelectorAll('table tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(-10px)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
    }
}

// تهيئة التأثيرات عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', () => {
    new DashboardAnimations();
});