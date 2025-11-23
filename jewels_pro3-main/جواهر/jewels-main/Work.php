<?php
include 'config.php';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مصنع جواهر - أعمالنا</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/Work.css">
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
                       <li><a href="Home.php">الرئيسية</a></li>
                       <li><a href="Home.php">شركائنا</a></li>
                    <li><a href="Home.php">من نحن</a></li>
                    <li><a href="Home.php">خدماتنا</a></li>
                    <li><a href="Work.php">أعمالنا</a></li>
                    <li><a href="vision.php">رؤيتنا</a></li>
                    <li><a href="team.php">انضم لفريقنا</a></li>
                    <li class="nav-cta"><a href="Home.php" class="btn">تواصل معنا</a></li>
                    </ul>
                    
                 
                
                <div class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
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
<!--                                  نهاية الناف بار                                      -->


    <!-- Works Section -->
    <section class="works-section">
        <div class="section-header">
            <h2 class="section-title">أعمالنا</h2>
            <p class="section-subtitle">نقدم لكم مجموعة مختارة من مشاريعنا الناجحة التي تعكس خبرتنا وتميزنا في المجال</p>
        </div>
        
        <div class="works-grid">
            <?php
            // جلب الأعمال من قاعدة البيانات
            $works_result = mysqli_query($conn, "SELECT * FROM works ORDER BY id DESC");
            
            if(mysqli_num_rows($works_result) > 0) {
                while($work = mysqli_fetch_assoc($works_result)) {
                    // التحقق من وجود الصورة
                    $image_path = $work['image'];
                    if (!file_exists($image_path) || empty($image_path)) {
                        $image_path = "img/work-default.jpg";
                    }
                    ?>
                    <!-- العمل من قاعدة البيانات -->
                    <div class="work-card">
                        <div class="work-image">
                            <img src="<?php echo $image_path; ?>" alt="<?php echo $work['title']; ?>">
                        </div>
                        <div class="work-content">
                            <h3 class="work-title"><?php echo $work['title']; ?></h3>
                            <p class="work-description"><?php echo $work['descrip']; ?></p>
                            <div class="contact-btn-container">
                                <a href="tel:966573797877" class="contact-btn">
                                    تواصل معنا
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                // إذا لم توجد أعمال في قاعدة البيانات، عرض مثال واحد فقط
                ?>
                <!-- العمل الافتراضي -->
                <div class="work-card">
                    <div class="work-image">
                        <img src="img/1.jpg" alt="عمل افتراضي">
                    </div>
                    <div class="work-content">
                        <h3 class="work-title">مثال على العمل من قاعدة البيانات</h3>
                        <p class="work-description">هذا مثال لعمل سيتم عرضه من قاعدة البيانات. يمكنك إضافة أعمال جديدة من خلال لوحة التحكم.</p>
                        <div class="contact-btn-container">
                            <a href="tel:966573797877" class="contact-btn">
                                تواصل معنا
                            </a>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </section>
    
    <script src="js/index.js"></script>
</body>
</html>