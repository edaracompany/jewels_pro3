<?php
include 'config.php';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مصنع جواهر - رائد صناعة المجوهرات</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
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
        <img src="img/25.jpeg" alt="Logo" class="preloader-logo">
        
    </div>
</div>

<style>
.preloader-logo {
    width: 200px;
    height: auto;
    display: block;
    margin: 0 auto 10px auto;
    animation: elegantFade 2s infinite ease-in-out;
}

@keyframes elegantFade {
    0% {
        transform: scale(1);
        opacity: 0.7;
    }
    50% {
        transform: scale(1.05);
        opacity: 1;
    }
    100% {
        transform: scale(1);
        opacity: 0.7;
    }
}
</style>


 
<!--                                   الناف بار                                      -->
    <header id="header">
        <div class="container">
            <div class="header-content">
          <a href="Home.php" class="logo">
    <img src="img/25.jpeg" alt="Logo" class="logo-fix">
</a>

<style>
.logo-fix {
    width: 160px; /* حجم أصغر للناف بار */
    height: auto; /* يحافظ على تناسب الصورة */
    display: block;
    margin: 0 auto; /* يخلي الصورة بمركز العنصر */
    margin-top: 5px; /* لو بدك شوي فوق، عدلي الرقم حسب الحاجة */
}
</style>

                
                <div class="nav-container">
                    <ul class="nav-links">
                       <li><a href="#home">الرئيسية</a></li>
                       <li><a href="#sliderTrack">شركائنا</a></li>
                    <li><a href="#about">من نحن</a></li>
                    <li><a href="#services">خدماتنا</a></li>
                    <li><a href="Work.php">أعمالنا</a></li>
                    <li><a href="vision.php">رؤيتنا</a></li>
                    <li><a href="team.php">انضم لفريقنا</a></li>
                    <li class="nav-cta"><a href="#contact" class="btn">تواصل معنا</a></li>
                    </ul>
                    
                 
                
                <div class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
        </div>
        
        <div class="mobile-menu" id="mobileMenu">
            <ul class="mobile-nav-links">
                <li><a href="#home"><i class="fas fa-home"></i> الرئيسية</a></li>
                <li><a href="#sliderTrack"><i class="fas fa-handshake"></i> شركائنا</a></li>
                <li><a href="#about"><i class="fas fa-users"></i> من نحن</a></li>
                <li><a href="#services"><i class="fas fa-concierge-bell"></i> خدماتنا</a></li>
                <li><a href="Work.php"><i class="fas fa-briefcase"></i> أعمالنا</a></li>
                <li><a href="vision.php"><i class="fas fa-eye"></i> رؤيتنا</a></li>
                <li><a href="team.php"><i class="fas fa-user-plus"></i> انضم لفريقنا</a></li>
            </ul>
            
            <div class="mobile-cta">
                <a href="#contact" class="btn"> تواصل معنا</a>
            </div>
        </div>
        
        <div class="menu-overlay" id="menuOverlay"></div>
    </header>
<!--                                  نهاية الناف بار                                      -->


    <!-- الرئيسية -->
    <section class="hero section-highlight" id="home">

           <!-- السلايدر الخلفية -->
<div class="hero-slider">
    <div class="slide active" style="background-image: url('img/27.jpeg');"></div>
    <div class="slide" style="background-image: url('img/28.jpeg');"></div>
    <div class="slide" style="background-image: url('img/29.jpeg');"></div>
    <div class="slide" style="background-image: url('img/30.jpeg');"></div>
</div>
        <div class="hero-bg"></div>
        
        <div class="hero-content">
            <h1>مصنع جواهر</h1>
            <p>"جواهر... حيث تلتقي براعة الحرفي بدقة التقنية"</p>
            <div class="hero-buttons">
                <a href="Work.php" class="btn">
                    <i class="fas fa-gem"></i>
                    اكتشف اعمالنا
                </a>
                <a href="Work.php" class="btn btn-outline">
                    <i class="fas fa-play-circle"></i>
                    شاهد الفيديو
                </a>
            </div>
        </div>
    </section>
<script>
        // JavaScript for the background slider
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.slide');
            let currentSlide = 0;
            
            function nextSlide() {
                slides[currentSlide].classList.remove('active');
                currentSlide = (currentSlide + 1) % slides.length;
                slides[currentSlide].classList.add('active');
            }
            
            // Change slide every 5 seconds
            setInterval(nextSlide, 5000);
        });
    </script>

<!-- شركائنا -->
<div class="slider-container">
    <div class="slider-track" id="sliderTrack">
        <?php
        // فحص قاعدة البيانات
        $partners_result = mysqli_query($conn, "SELECT * FROM partnerships");
        $partners_count = mysqli_num_rows($partners_result);
        
        echo "<!-- عدد الشركاء في قاعدة البيانات: " . $partners_count . " -->";
        
        if($partners_count > 0) {
            $displayed_partners = array(); // لتجنب التكرار
            
            while($partner = mysqli_fetch_assoc($partners_result)) {
                // التحقق من عدم تكرار الشريك
                $partner_id = $partner['id'];
                if(in_array($partner_id, $displayed_partners)) {
                    echo "<!-- تحذير: الشريك مكرر - ID: " . $partner_id . " -->";
                    continue; // تخطي إذا كان مكرراً
                }
                
                $displayed_partners[] = $partner_id;
                
                echo "<!-- معالجة شريك: " . $partner['title'] . " (ID: " . $partner_id . ") -->";
                
                // التحقق من وجود الصورة
                $image_path = $partner['image'];
                if (!file_exists($image_path) || empty($image_path)) {
                    $image_path = "img/default-partner.jpg";
                    echo "<!-- استخدام الصورة الافتراضية -->";
                }
                
                echo '<div class="box"><img src="'.$image_path.'" alt="'.$partner['title'].'"></div>';
            }
            
            echo "<!-- عدد الشركاء المعروضين: " . count($displayed_partners) . " -->";
            
        } else {
            echo "<!-- لا توجد شراكات، عرض شركاء افتراضيين -->";
            echo '<div class="box"><img src="img/default-partner.jpg" alt="شريك افتراضي"></div>';
            echo '<div class="box"><img src="img/default-partner.jpg" alt="شريك افتراضي"></div>';
            echo '<div class="box"><img src="img/default-partner.jpg" alt="شريك افتراضي"></div>';
        }
        ?>
    </div>
</div>
    <!-- من نحن -->
    <section class="about section-highlight" id="about">
        <div class="container">
            <div class="section-title fade-in">
                <h2>من نحن</h2>
                <p>في شركة جواهر، نتميز بخبرة طويلة واحترافية في عالم المعادن</p>
            </div>
            <div class="about-content">
                <div class="about-text fade-in">
                    <?php
                    $about_result = mysqli_query($conn, "SELECT * FROM about LIMIT 1");
                    if($about_result && mysqli_num_rows($about_result) > 0) {
                        $about = mysqli_fetch_assoc($about_result);
                        echo "
                        <h3>{$about['title']}</h3>
                        <p>{$about['descrip']}</p>
                        ";
                    } else {
                        echo "
                        <h3>مصنع جواهر</h3>
                        <p>شركة رائدة في مجال تصنيع المجوهرات والمعادن الثمينة منذ عام 1985.</p>
                        ";
                    }
                    ?>
                    <a href="Work.php" class="btn mt-0">
                        <i class="fas fa-book-open"></i>
                        تعرف على المزيد
                    </a>
                </div>
                <div class="about-image fade-in stagger-delay-4">
                    <?php
                    $about_result = mysqli_query($conn, "SELECT * FROM about LIMIT 1");
                    if($about_result && mysqli_num_rows($about_result) > 0) {
                        $about = mysqli_fetch_assoc($about_result);
                        $image_path = $about['image'];
                        if (!file_exists($image_path) || empty($image_path)) {
                            $image_path = "img/default-about.jpg";
                        }
                        echo "
                        <div class='image-container'>
                            <img src='{$image_path}' alt='ورشة العمل لدينا' class='workshop-image'>
                        </div>
                        ";
                    } else {
                        echo "
                        <div class='image-container'>
                            <img src='img/default-about.jpg' alt='ورشة العمل لدينا' class='workshop-image'>
                        </div>
                        ";
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- خدماتنا -->
    <section class="features section-highlight" id="services">
        <div class="container">
            <div class="section-title fade-in">
                <h2>خدماتنا المتخصصة</h2>
                <p>نقدم مجموعة متكاملة من خدمات تصنيع المجوهرات باستخدام أحدث التقنيات</p>
            </div>
            
            <div class="services-grid">
                <?php
                $services_result = mysqli_query($conn, "SELECT * FROM services");
                if(mysqli_num_rows($services_result) > 0) {
                    while($service = mysqli_fetch_assoc($services_result)) {
                        $image_path = $service['image'];
                        if (!file_exists($image_path) || empty($image_path)) {
                            $image_path = "img/service-default.jpg";
                        }
                        echo "
                        <div class='service-card fade-in stagger-delay-1'>
                            <div class='service-icon'>
                                <img src='{$image_path}' alt='{$service['title']}' class='service-image'>
                            </div>
                            <h3>{$service['title']}</h3>
                            <p>{$service['descrip']}</p>
                        </div>
                        ";
                    }
                } else {
                    echo "
                    <div class='service-card fade-in stagger-delay-1'>
                        <div class='service-icon'>
                            <img src='img/service-default.jpg' alt='خدمة افتراضية' class='service-image'>
                        </div>
                        <h3>القص بالليزر والبلازما</h3>
                        <p>القص بالليزر والبلازما هما تقنيتان متطورتان تستخدمان في قطع المواد المعدنية وغير المعدنية بدقة عالية وسرعة فائقة.</p>
                    </div>
                    ";
                }
                ?>
            </div>
        </div>
    </section>

    <!-- عملية التصنيع -->
    <section class="cnc-process section-highlight" id="process">
        <div class="container">
            <div class="section-title fade-in">
                <h2>عملية التصنيع المتطورة</h2>
                <p>اكتشف رحلتنا في تحويل الأفكار إلى قطع فنية مذهلة باستخدام أحدث التقنيات</p>
            </div>
            
            <div class="process-steps">
                <?php
                $manufacturing_result = mysqli_query($conn, "SELECT * FROM manufacturing");
                if(mysqli_num_rows($manufacturing_result) > 0) {
                    while($step = mysqli_fetch_assoc($manufacturing_result)) {
                        $image_path = $step['image'];
                        if (!file_exists($image_path) || empty($image_path)) {
                            $image_path = "img/manufacturing-default.jpg";
                        }
                        echo "
                        <div class='process-step fade-in stagger-delay-1'>
                            <div class='service-icon'>
                                <img src='{$image_path}' alt='{$step['title']}' class='service-image'>
                            </div>
                            <h3>{$step['title']}</h3>
                            <p>{$step['descrip']}</p>
                        </div>
                        ";
                    }
                } else {
                    echo "
                    <div class='process-step fade-in stagger-delay-1'>
                        <div class='service-icon'>
                            <img src='img/manufacturing-default.jpg' alt='التصميم الرقمي' class='service-image'>
                        </div>
                        <h3>التصميم الرقمي</h3>
                        <p>نبدأ برسم التصاميم الأولية باستخدام أحدث برامج التصميم ثلاثية الأبعاد، مع مراعاة أحدث صيحات الموضة ومتطلبات العملاء.</p>
                    </div>
                    ";
                }
                ?>
            </div>

            <div class="process-highlight fade-in">
                <h3>مميزات تقنياتنا المتطورة</h3>
                <ul>
                    <li>دقة قص تصل إلى 0.01 ملم باستخدام رواتر متطورة</li>
                    <li>سرعة إنتاجية عالية مع الحفاظ على أعلى معايير الجودة</li>
                    <li>استخدام أحدث تقنيات التصنيع الرقمي</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- أراء العملاء -->
    <section class="testimonials section-highlight" id="testimonials">
        <div class="container">
            <div class="section-title fade-in">
                <h2>آراء العملاء</h2>
                <p>ما يقوله عملاؤنا عن تجربتهم مع منتجاتنا وخدماتنا</p>
            </div>
            <div class="testimonials-grid">
                <?php
                $testimonials_result = mysqli_query($conn, "SELECT * FROM customer");
                if(mysqli_num_rows($testimonials_result) > 0) {
                    while($testimonial = mysqli_fetch_assoc($testimonials_result)) {
                        echo "
                        <div class='testimonial-card fade-in stagger-delay-1'>
                            <div class='testimonial-content'>
                                <p class='testimonial-text'>&quot;{$testimonial['opinions']}&quot;</p>
                            </div>
                            <div class='testimonial-author'>
                                <div class='author-avatar'></div>
                                <div class='author-info'>
                                    <h4>{$testimonial['name']}</h4>
                                </div>
                            </div>
                        </div>
                        ";
                    }
                } else {
                    echo "
                    <div class='testimonial-card fade-in stagger-delay-1'>
                        <div class='testimonial-content'>
                            <p class='testimonial-text'>&quot;اشتريت خاتم خطوبة من مصنع جواهر وكان اختياراً رائعاً. الجودة استثنائية والتصميم يفوق التوقعات. فريق العمل محترف جداً ويقدم استشارات قيمة.&quot;</p>
                        </div>
                        <div class='testimonial-author'>
                            <div class='author-avatar'></div>
                            <div class='author-info'>
                                <h4>نورة أحمد</h4>
                            </div>
                        </div>
                    </div>
                    ";
                }
                ?>
            </div>
        </div>
    </section>

    <!-- التواصل -->
    <section class="cta section-highlight" id="contact">
        <div class="container">
            <div class="cta-content fade-in">
                <h2>جاهزون لصنع قطعتك الفريدة؟</h2>
                <p>اتصل بنا اليوم واحصل على استشارة مجانية من خبرائنا لتصميم قطعة المجوهرات التي تحلم بها</p>
                <div class="hero-buttons">
                    <a href="tel:+966573797877" class="btn">
                        <i class="fas fa-phone"></i>
                        اتصل بنا الآن
                    </a>
                    <a href="https://wa.me/966573797877" class="btn btn-outline">
                        <i class="fab fa-whatsapp"></i>
                        راسلنا على واتساب
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>مصنع جواهر</h3>
                    <div class="footer-about">
                        <p>رائد صناعة المجوهرات والطلاء منذ عام 1985. نقدم أجود المنتجات بأفضل التصميمات العصرية والكلاسيكية، مع الحفاظ على أعلى معايير الجودة والتميز.</p>
                         <div class="social-links">
    <a href="https://www.instagram.com/jawaher_metals?igsh=MWtudmR0ZmlsZWd4dQ%3D%3D&utm_source=qr" target="_blank"><i class="fab fa-instagram"></i></a>
    <a href="https://www.linkedin.com/in/jawaher-382143381?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app" target="_blank"><i class="fab fa-linkedin-in"></i></a>
    <a href="https://x.com/jawaherr90?s=21" target="_blank"><i class="fab fa-twitter"></i></a>
    <a href="https://www.tiktok.com/@jawaher5188?_r=1&_d=emage542dcddbj&sec_uid=MS4wLjABAAAAFcZtu-RSNyJjUN6CJE-Sm4RBzQI4M-SH9RRM00kJ6mFBDgjglSq6thI0E5Q5EoDM&share_author_id=7548848412835169287&sharer_language=ar&source=h5_m&u_code=emageihj4m0hdd&item_author_type=1&utm_source=copy&tt_from=copy&enable_checksum=1&utm_medium=ios&share_link_id=97787B10-02F2-4C9D-AD6E-3D8B0A7E919F&user_id=7548848412835169287&sec_user_id=MS4wLjABAAAAFcZtu-RSNyJjUN6CJE-Sm4RBzQI4M-SH9RRM00kJ6mFBDgjglSq6thI0E5Q5EoDM&social_share_type=5&ug_btm=b8727,b0&utm_campaign=client_share&share_app_id=1233" target="_blank"><i class="fab fa-tiktok"></i></a>
</div>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>روابط سريعة</h3>
                    <ul class="footer-links">
                         <li><a href="#home">الرئيسية</a></li>
                         <li><a href="#sliderTrack">شركائنا</a></li>
                         <li><a href="#about">من نحن</a></li>
                         <li><a href="#services">خدماتنا</a></li>
                         <li><a href="Work.php">أعمالنا</a></li>
                         <li><a href="vision.php">رؤيتنا</a></li>
                         <li><a href="team.php">انضم لفريقنا</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>اتصل بنا</h3>
                    <ul class="footer-links">
                        <li>العنوان: المملكة العربية السعودية, الخبر</li>
                        <li>الهاتف: +966573797877</li>
                        <li>الموبايل: +966573797877</li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>جميع الحقوق محفوظة &copy; 2017 مصنع جواهر. تم التصميم والتطوير بدقة وحرفية.</p>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <div class="scroll-to-top">
        <i class="fas fa-chevron-up"></i>
    </div>

    <!-- واتساب فلوتنج بوتون -->
    <div class="whatsapp-float">
        <a href="https://wa.me/966573797877" target="_blank" class="whatsapp-link">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>
    
    <script src="js/index.js"></script>
</body>
</html>