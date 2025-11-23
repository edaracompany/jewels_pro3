<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رؤيتنا - مصنع جواهر للرخام</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/vision.css">
</head>
<body>

     <!-- Floating Ring - NEW -->
    <div class="floating-ring" id="floatingRing">
        <div class="ring-container">
            <div class="ring-circle"></div>
            <div class="ring-diamond"></div>
            <div class="ring-light"></div>
        </div>
    </div>

    <!-- Preloader -->
    <div id="preloader">
        <div class="preloader-content">
            <div class="preloader-gem"></div>
            <h3>مصنع جواهر</h3>
            <p>جاري التحميل...</p>
        </div>
    </div>
    <!-- Header -->
    <header id="header">
        <div class="container">
            <div class="header-content">
                <a href="Home.php" class="logo">
                    <i class="fas fa-gem"></i>
                    <span class="logo-text">جواهر</span>
                </a>
                
                <div class="nav-container">
                    <ul class="nav-links">
                       <li><a href="Home.php">الرئيسية</a></li>
                       <li><a href="Home.php">شركائنا</a></li>
                    <li><a href="Home.php">من نحن</a></li>
                    <li><a href="Home.php">خدماتنا</a></li>
                    <li><a href="Work.php">أعمالنا</a></li>
                    <li><a href="vision.php">رؤيتنا</a></li>
                    <li><a href="team.php">انضم لفريقنا</a></li>
                    </ul>
                    
                    <div class="nav-cta">
                        <a href="Home.php" class="btn">تواصل معنا</a>
                    </div>
                
                    <div class="menu-toggle" id="menuToggle">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mobile-menu" id="mobileMenu">
            <ul class="mobile-nav-links">
                <li><a href="Home.php"><i class="fas fa-home"></i> الرئيسية</a></li>
                <li><a href="Home.php"><i class="fas fa-handshake"></i> شركائنا</a></li>
                <li><a href="Home.php"><i class="fas fa-users"></i> من نحن</a></li>
                <li><a href="Home.php"><i class="fas fa-concierge-bell"></i> خدماتنا</a></li>
                <li><a href="Work.php"><i class="fas fa-briefcase"></i> أعمالنا</a></li>
                <li><a href="vision.php"><i class="fas fa-eye"></i> رؤيتنا</a></li>
                <li><a href="team.php"><i class="fas fa-user-plus"></i> انضم لفريقنا</a></li>
            </ul>
            
            <div class="mobile-cta">
                <a href="Home.php" class="btn"> تواصل معنا</a>
            </div>
        </div>
        
        <div class="menu-overlay" id="menuOverlay"></div>
    </header>
    <!-- قسم الرؤية -->
    <section class="vision-section" id="vision">
        <div class="vision-bg"></div>
        <div class="vision-pattern"></div>
        
        <div class="container">
            <div class="section-header">
                <h1 class="section-title">رؤيتنا</h1>
                <p class="section-subtitle">خدماتنا في جواهر… دقة تُصنع بفخامة</p>
            </div>
            
            <div class="vision-content">
                <div class="vision-main">
                    <div class="vision-card">
                        <h2 class="vision-title">في شركة جواهر نؤمن أن الجودة تبدأ من التفاصيل.</h2>
                        <p class="vision-description">
                            نقدّم مجموعة متكاملة من خدمات الرخام تشمل استخراج الرخام، القص والتشكيل، التلميع، والتركيب، 
                            وذلك بأعلى معايير الدقة والاحترافية.
                        </p>
                        
                        <div class="vision-highlight">
                            <p>نحوّل الرخام الخام إلى تحف فنية من خلال خدمات متكاملة</p>
                        </div>
                        
                        <div class="vision-text-content">
                            <h3>رؤيتنا المستقبلية</h3>
                            <p>
                                نسعى لأن نكون الرواد في صناعة الرخام على مستوى المنطقة، من خلال الابتكار المستمر 
                                وتبني أحدث التقنيات في مجال استخراج وتصنيع الرخام.
                            </p>
                            <p>
                                نطمح إلى توسيع نطاق أعمالنا ليشمل أسواقاً جديدة، مع الحفاظ على جودة منتجاتنا 
                                وتميز تصميماتنا التي تجمع بين الأصالة والحداثة.
                            </p>
                            <div class="highlight">
                                "جواهر... حيث تلتقي براعة الحرفي بدقة التقنية"
                            </div>
                            <p>
                                نعمل على بناء شراكات استراتيجية مع أفضل موردين العالم، ونسعى لاكتشاف أنواع جديدة 
                                من الرخام تتميز بجودتها الفائقة وتصاميمها الفريدة.
                            </p>
                        </div>
                    </div>
                </div>
                
               </div>
    </section>

    <script>
        // تأثيرات تفاعلية للصور المصغرة
        document.addEventListener('DOMContentLoaded', function() {
            const thumbnails = document.querySelectorAll('.thumbnail');
            const mainImage = document.querySelector('.main-image img');
            const mainOverlay = document.querySelector('.image-overlay');
            
            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', function() {
                    const imgSrc = this.querySelector('img').src;
                    mainImage.src = imgSrc;
                    
                    // تأثير تغيير الصورة
                    mainImage.style.opacity = '0';
                    setTimeout(() => {
                        mainImage.style.opacity = '1';
                    }, 300);
                });
            });
        });
    </script>
    <script>
        
        // JavaScript for header scroll effect and mobile menu
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.getElementById('header');
            const menuToggle = document.getElementById('menuToggle');
            const mobileMenu = document.getElementById('mobileMenu');
            const menuOverlay = document.getElementById('menuOverlay');
            const preloader = document.getElementById('preloader');
            
            // Header scroll effect
            window.addEventListener('scroll', function() {
                if (window.scrollY > 100) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });
            
           
            // Hide preloader when page is loaded
            window.addEventListener('load', function() {
                setTimeout(function() {
                    preloader.style.opacity = '0';
                    preloader.style.visibility = 'hidden';
                }, 1000);
            });
        });
        
    </script>
    <script src="js/index.js"></script>
</body>
</html>