<?php
include 'config.php';

// التحقق من وجود العمود cv_file وإضافته إذا لم يكن موجوداً
$check_column = mysqli_query($conn, "SHOW COLUMNS FROM job LIKE 'cv_file'");
if (mysqli_num_rows($check_column) == 0) {
    $alter_result = mysqli_query($conn, "ALTER TABLE job ADD COLUMN cv_file VARCHAR(255) AFTER message");
    if (!$alter_result) {
        die("❌ فشل في إضافة العمود المطلوب: " . mysqli_error($conn));
    }
}

// التأكد من وجود مجلد الرفع
if (!file_exists('uploads/cv')) {
    mkdir('uploads/cv', 0777, true);
}

// معالجة إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['fullName']);
    $gmail = mysqli_real_escape_string($conn, $_POST['email']);
    $phon = mysqli_real_escape_string($conn, $_POST['phone']);
    $jop = mysqli_real_escape_string($conn, $_POST['position']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    // معالجة رفع الملف
    $file_path = '';
    $upload_success = true;
    
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        $allowed_types = ['pdf', 'doc', 'docx'];
        $file_extension = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
        
        if (in_array($file_extension, $allowed_types)) {
            // إنشاء اسم فريد للملف
            $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
            $upload_path = 'uploads/cv/' . $new_filename;
            
            if (move_uploaded_file($_FILES['cv']['tmp_name'], $upload_path)) {
                $file_path = $upload_path;
            } else {
                $error_message = "❌ فشل في رفع الملف. يرجى المحاولة مرة أخرى.";
                $upload_success = false;
            }
        } else {
            $error_message = "❌ نوع الملف غير مسموح به. يرجى رفع ملف PDF, DOC, أو DOCX فقط.";
            $upload_success = false;
        }
    } else {
        $error_message = "❌ يرجى اختيار ملف السيرة الذاتية.";
        $upload_success = false;
    }
    
    // إذا لم يكن هناك خطأ في رفع الملف، أدخل البيانات
    if ($upload_success && !isset($error_message)) {
        // إدخال البيانات في قاعدة البيانات
        $sql = "INSERT INTO job (name, gmail, phon, jop, message, cv_file) 
                VALUES ('$name', '$gmail', '$phon', '$jop', '$message', '$file_path')";
        
        if (mysqli_query($conn, $sql)) {
            $success_message = "✅ تم تقديم طلبك بنجاح! سنتواصل معك قريباً.";
        } else {
            $error_message = "❌ حدث خطأ في تقديم الطلب: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مصنع جواهر - انضم لفريقنا</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/team.css">
    <style>
        .notification {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .loading {
            display: none;
            text-align: center;
            padding: 10px;
            color: #007bff;
        }

        /* تنسيقات إضافية لتحسين الشكل */
        .file-upload {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
            margin: 10px 0;
        }

        .file-upload-label {
            display: block;
            padding: 20px;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload-label:hover {
            background: #e9ecef;
            border-color: #007bff;
        }

        .file-upload-label i {
            font-size: 2rem;
            color: #6c757d;
            margin-bottom: 10px;
            display: block;
        }

        .file-upload input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-name {
            margin-top: 10px;
            padding: 8px 12px;
            background: #e9ecef;
            border-radius: 4px;
            font-size: 0.9rem;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border 0.3s ease;
        }

        .form-control:focus {
            border-color: #007bff;
            outline: none;
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .submit-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s ease;
            width: 100%;
        }

        .submit-btn:hover {
            background: #0056b3;
        }

        .submit-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
    </style>
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

    <!-- Team Section -->
    <section class="team-section" id="team">
        <div class="container">
            <div class="section-header">
                <h1>انضم لفريق جواهر</h1>
                <p>نبحث عن مواهب استثنائية لتنضم لفريقنا</p>
            </div>
            
            <!-- عرض الرسائل -->
            <?php if (isset($success_message)): ?>
                <div class="notification success">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="notification error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <div class="join-form-container">
                <form class="join-form" id="joinForm" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="fullName">الاسم الكامل *</label>
                        <input type="text" id="fullName" name="fullName" class="form-control" required placeholder="أدخل اسمك الكامل">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">البريد الإلكتروني *</label>
                        <input type="email" id="email" name="email" class="form-control" required placeholder="example@email.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">رقم الهاتف *</label>
                        <input type="tel" id="phone" name="phone" class="form-control" required placeholder="05XXXXXXXX">
                    </div>
                    
                    <div class="form-group">
                        <label for="position">الوظيفة المتقدم لها</label>
                        <input type="text" id="position" name="position" class="form-control" placeholder="المسمى الوظيفي">
                    </div>
                    
                    <div class="form-group">
                        <label for="message">رسالة التقديم</label>
                        <textarea id="message" name="message" class="form-control" rows="4" placeholder="أخبرنا عن نفسك وخبراتك..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>رفع السيرة الذاتية (CV) *</label>
                        <div class="file-upload">
                            <label for="cv" class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>انقر لرفع ملف السيرة الذاتية (PDF, DOC, DOCX)</span>
                            </label>
                            <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required>
                        </div>
                        <div class="file-name" id="fileName">لم يتم اختيار ملف</div>
                    </div>
                    
                    <div class="loading" id="loading">
                        <i class="fas fa-spinner fa-spin"></i> جاري إرسال طلبك...
                    </div>
                    
                    <button type="submit" class="submit-btn" id="submitBtn">
                        <i class="fas fa-paper-plane"></i>
                        تقديم الطلب
                    </button>
                    
                    <p class="form-note">سيتم مراجعة طلبك والاتصال بك في حال تم اختيارك للمقابلة</p>
                </form>
            </div>
        </div>
    </section>

    <script>
        // Preloader
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            setTimeout(() => {
                preloader.style.opacity = '0';
                preloader.style.visibility = 'hidden';
            }, 1500);
        });

        // Floating Ring Animation
        document.addEventListener('mousemove', (e) => {
            const ring = document.getElementById('floatingRing');
            ring.style.left = e.clientX + 'px';
            ring.style.top = e.clientY + 'px';
            ring.classList.add('active');
        });

        // Mobile Menu Toggle
        const menuToggle = document.getElementById('menuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuOverlay = document.getElementById('menuOverlay');

        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
            menuOverlay.classList.toggle('active');
            document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
        });

        menuOverlay.addEventListener('click', () => {
            mobileMenu.classList.remove('active');
            menuOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });

        // Header Scroll Effect
        window.addEventListener('scroll', () => {
            const header = document.getElementById('header');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Form Submission
        document.getElementById('joinForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const loading = document.getElementById('loading');
            
            // Basic form validation
            const fullName = document.getElementById('fullName').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const cv = document.getElementById('cv').files[0];
            
            if (!fullName || !email || !phone || !cv) {
                alert('يرجى ملء جميع الحقول الإلزامية (*)');
                return;
            }
            
            // التحقق من صيغة البريد الإلكتروني
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('يرجى إدخال بريد إلكتروني صحيح');
                return;
            }
            
            // التحقق من صيغة رقم الهاتف (سعودي)
            const phoneRegex = /^(05)[0-9]{8}$/;
            if (!phoneRegex.test(phone)) {
                alert('يرجى إدخال رقم هاتف سعودي صحيح (مثال: 0512345678)');
                return;
            }
            
            // التحقق من حجم الملف (10MB كحد أقصى)
            if (cv.size > 10 * 1024 * 1024) {
                alert('حجم الملف يجب أن يكون أقل من 10MB');
                return;
            }
            
            // إظهار رسالة التحميل
            submitBtn.disabled = true;
            loading.style.display = 'block';
        });

        // File upload display
        document.getElementById('cv').addEventListener('change', function(e) {
            const fileName = this.files[0] ? this.files[0].name : 'لم يتم اختيار ملف';
            document.getElementById('fileName').textContent = fileName;
            
            // التحقق من نوع الملف
            const allowedTypes = ['pdf', 'doc', 'docx'];
            const fileExtension = this.files[0].name.split('.').pop().toLowerCase();
            
            if (!allowedTypes.includes(fileExtension)) {
                alert('يرجى رفع ملف بصيغة PDF أو DOC أو DOCX فقط');
                this.value = '';
                document.getElementById('fileName').textContent = 'لم يتم اختيار ملف';
            }
        });
    </script>
</body>
</html>