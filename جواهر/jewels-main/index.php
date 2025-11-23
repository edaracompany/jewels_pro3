<?php
session_start();

// الاتصال بقاعدة البيانات
$host = "localhost";
$username = "root"; 
$password = "";
$dbname = "jawaherest";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}
$conn->set_charset("utf8");

// التحقق من تسجيل الدخول
//if (!isset($_SESSION['user_id'])) {
//    header('Location: login.php');
//    exit;
//}

// إنشاء جميع الجداول المطلوبة
$tables = [
    "admin_users" => "CREATE TABLE IF NOT EXISTS admin_users (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "site_settings" => "CREATE TABLE IF NOT EXISTS site_settings (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        site_title VARCHAR(255) NOT NULL,
        site_description TEXT NOT NULL,
        contact_email VARCHAR(100) NOT NULL,
        contact_phone VARCHAR(20) NOT NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    
    "about" => "CREATE TABLE IF NOT EXISTS about (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        descrip TEXT NOT NULL,
        image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "partnerships" => "CREATE TABLE IF NOT EXISTS partnerships (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "services" => "CREATE TABLE IF NOT EXISTS services (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        descrip TEXT NOT NULL,
        image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "manufacturing" => "CREATE TABLE IF NOT EXISTS manufacturing (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        descrip TEXT NOT NULL,
        image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "tech" => "CREATE TABLE IF NOT EXISTS tech (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        descrip TEXT NOT NULL,
        image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "customer" => "CREATE TABLE IF NOT EXISTS customer (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        opinions TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "works" => "CREATE TABLE IF NOT EXISTS works (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        descrip TEXT NOT NULL,
        image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "job" => "CREATE TABLE IF NOT EXISTS job (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        gmail VARCHAR(255) NOT NULL,
        phon VARCHAR(20) NOT NULL,
        jop VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        cv_file VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
];

foreach ($tables as $table => $sql) {
    if (!$conn->query($sql)) {
        die("خطأ في إنشاء الجدول: " . $conn->error);
    }
}

// التحقق من وجود المستخدم الافتراضي
$check_user = $conn->query("SELECT * FROM admin_users WHERE username = 'هندي'");
if ($check_user->num_rows == 0) {
    // إنشاء مستخدم افتراضي إذا لم يوجد
    $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
    $insert_user = $conn->query("INSERT INTO admin_users (username, password, name, email) VALUES ('هندي', '$hashed_password', 'مدير النظام', 'admin@jawaher.com')");
    
    if ($insert_user) {
        // تعيين جلسة المستخدم إذا لم تكن موجودة
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['user_name'] = 'مدير النظام';
        }
    }
}

// التحقق من وجود إعدادات الموقع
$check_settings = $conn->query("SELECT * FROM site_settings LIMIT 1");
if ($check_settings->num_rows == 0) {
    $conn->query("INSERT INTO site_settings (site_title, site_description, contact_email, contact_phone) VALUES ('جواهر', 'شركة رائدة في مجال التصنيع', 'info@jawaher.com', '+966500000000')");
}

// معالجة رفع الصور - الإصدار المصحح
function handleImageUpload($file, $target_dir = "uploads/") {
    // التحقق من وجود الملف
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    // التحقق من أن الملف صورة
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return false;
    }
    
    // إنشاء المجلد إذا لم يكن موجوداً
    if (!file_exists($target_dir)) {
        if (!mkdir($target_dir, 0777, true)) {
            return false;
        }
    }
    
    // إنشاء اسم فريد للملف
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $allowed_extensions = ["jpg", "jpeg", "png", "gif", "webp"];
    
    // التحقق من نوع الملف
    if (!in_array($file_extension, $allowed_extensions)) {
        return false;
    }
    
    // التحقق من حجم الملف (5MB كحد أقصى)
    if ($file["size"] > 5 * 1024 * 1024) {
        return false;
    }
    
    // إنشاء اسم فريد
    $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // محاولة رفع الملف
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    }
    
    return false;
}

// معالجة رفع ملفات السيرة الذاتية
function handleCVUpload($file, $target_dir = "uploads/cv/") {
    // التحقق من وجود الملف
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    // إنشاء المجلد إذا لم يكن موجوداً
    if (!file_exists($target_dir)) {
        if (!mkdir($target_dir, 0777, true)) {
            return false;
        }
    }
    
    // إنشاء اسم فريد للملف
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $allowed_extensions = ["pdf", "doc", "docx"];
    
    // التحقق من نوع الملف
    if (!in_array($file_extension, $allowed_extensions)) {
        return false;
    }
    
    // التحقق من حجم الملف (10MB كحد أقصى)
    if ($file["size"] > 10 * 1024 * 1024) {
        return false;
    }
    
    // إنشاء اسم فريد
    $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // محاولة رفع الملف
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    }
    
    return false;
}

// معالجة Forms عند الإرسال
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch($action) {
        case 'save_about':
            $title = $conn->real_escape_string($_POST['title'] ?? '');
            $descrip = $conn->real_escape_string($_POST['descrip'] ?? '');
            $image_path = $about_data['image'] ?? ''; // الاحتفاظ بالصورة الحالية
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_result = handleImageUpload($_FILES['image']);
                if ($upload_result) {
                    $image_path = $upload_result;
                }
            }
            
            $check = $conn->query("SELECT id FROM about LIMIT 1");
            if ($check->num_rows > 0) {
                $conn->query("UPDATE about SET title='$title', descrip='$descrip', image='$image_path'");
            } else {
                $conn->query("INSERT INTO about (title, descrip, image) VALUES ('$title', '$descrip', '$image_path')");
            }
            $_SESSION['success'] = "✅ تم حفظ بيانات 'من نحن' بنجاح";
            break;
            
        case 'add_partnership':
            $title = $conn->real_escape_string($_POST['title'] ?? '');
            $image_path = 'default.jpg';
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_result = handleImageUpload($_FILES['image']);
                if ($upload_result) {
                    $image_path = $upload_result;
                }
            }
            
            $conn->query("INSERT INTO partnerships (title, image) VALUES ('$title', '$image_path')");
            $_SESSION['success'] = "✅ تم إضافة الشريك بنجاح";
            break;
            
        case 'delete_partnership':
            $id = intval($_POST['id'] ?? 0);
            $conn->query("DELETE FROM partnerships WHERE id = $id");
            $_SESSION['success'] = "✅ تم حذف الشريك بنجاح";
            break;
            
        case 'add_service':
            $title = $conn->real_escape_string($_POST['title'] ?? '');
            $descrip = $conn->real_escape_string($_POST['descrip'] ?? '');
            $image_path = 'default.jpg';
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_result = handleImageUpload($_FILES['image']);
                if ($upload_result) {
                    $image_path = $upload_result;
                }
            }
            
            $conn->query("INSERT INTO services (title, descrip, image) VALUES ('$title', '$descrip', '$image_path')");
            $_SESSION['success'] = "✅ تم إضافة الخدمة بنجاح";
            break;
            
        case 'delete_service':
            $id = intval($_POST['id'] ?? 0);
            $conn->query("DELETE FROM services WHERE id = $id");
            $_SESSION['success'] = "✅ تم حذف الخدمة بنجاح";
            break;
            
        case 'add_manufacturing':
            $title = $conn->real_escape_string($_POST['title'] ?? '');
            $descrip = $conn->real_escape_string($_POST['descrip'] ?? '');
            $image_path = 'default.jpg';
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_result = handleImageUpload($_FILES['image']);
                if ($upload_result) {
                    $image_path = $upload_result;
                }
            }
            
            $conn->query("INSERT INTO manufacturing (title, descrip, image) VALUES ('$title', '$descrip', '$image_path')");
            $_SESSION['success'] = "✅ تم إضافة عملية التصنيع بنجاح";
            break;
            
        case 'delete_manufacturing':
            $id = intval($_POST['id'] ?? 0);
            $conn->query("DELETE FROM manufacturing WHERE id = $id");
            $_SESSION['success'] = "✅ تم حذف عملية التصنيع بنجاح";
            break;
            
        case 'add_technology':
            $title = $conn->real_escape_string($_POST['title'] ?? '');
            $descrip = $conn->real_escape_string($_POST['descrip'] ?? '');
            $image_path = 'default.jpg';
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_result = handleImageUpload($_FILES['image']);
                if ($upload_result) {
                    $image_path = $upload_result;
                }
            }
            
            $conn->query("INSERT INTO tech (title, descrip, image) VALUES ('$title', '$descrip', '$image_path')");
            $_SESSION['success'] = "✅ تم إضافة التقنية بنجاح";
            break;
            
        case 'delete_technology':
            $id = intval($_POST['id'] ?? 0);
            $conn->query("DELETE FROM tech WHERE id = $id");
            $_SESSION['success'] = "✅ تم حذف التقنية بنجاح";
            break;
            
        case 'add_testimonial':
            $name = $conn->real_escape_string($_POST['name'] ?? '');
            $opinions = $conn->real_escape_string($_POST['opinions'] ?? '');
            $conn->query("INSERT INTO customer (name, opinions) VALUES ('$name', '$opinions')");
            $_SESSION['success'] = "✅ تم إضافة الرأي بنجاح";
            break;
            
        case 'delete_testimonial':
            $id = intval($_POST['id'] ?? 0);
            $conn->query("DELETE FROM customer WHERE id = $id");
            $_SESSION['success'] = "✅ تم حذف الرأي بنجاح";
            break;
            
        case 'add_project':
            $title = $conn->real_escape_string($_POST['title'] ?? '');
            $descrip = $conn->real_escape_string($_POST['descrip'] ?? '');
            $image_path = 'default.jpg';
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_result = handleImageUpload($_FILES['image']);
                if ($upload_result) {
                    $image_path = $upload_result;
                }
            }
            
            $conn->query("INSERT INTO works (title, descrip, image) VALUES ('$title', '$descrip', '$image_path')");
            $_SESSION['success'] = "✅ تم إضافة العمل بنجاح";
            break;
            
        case 'delete_project':
            $id = intval($_POST['id'] ?? 0);
            $conn->query("DELETE FROM works WHERE id = $id");
            $_SESSION['success'] = "✅ تم حذف العمل بنجاح";
            break;
            
        case 'delete_job':
            $id = intval($_POST['id'] ?? 0);
            // حذف ملف السيرة الذاتية من السيرفر أولاً
            $job_result = $conn->query("SELECT cv_file FROM job WHERE id = $id");
            if ($job_result->num_rows > 0) {
                $job = $job_result->fetch_assoc();
                if (!empty($job['cv_file']) && file_exists($job['cv_file'])) {
                    unlink($job['cv_file']);
                }
            }
            $conn->query("DELETE FROM job WHERE id = $id");
            $_SESSION['success'] = "✅ تم حذف طلب التوظيف بنجاح";
            break;
            
        case 'update_profile':
            $name = $conn->real_escape_string($_POST['name'] ?? '');
            $email = $conn->real_escape_string($_POST['email'] ?? '');
            $user_id = $_SESSION['user_id'] ?? 1;
            
            $conn->query("UPDATE admin_users SET name='$name', email='$email' WHERE id=$user_id");
            $_SESSION['user_name'] = $name;
            $_SESSION['success'] = "✅ تم تحديث الملف الشخصي بنجاح";
            break;
            
        case 'change_password':
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $user_id = $_SESSION['user_id'] ?? 1;
            
            // الحصول على كلمة المرور الحالية من قاعدة البيانات
            $user_result = $conn->query("SELECT * FROM admin_users WHERE id = $user_id");
            if ($user_result->num_rows > 0) {
                $user = $user_result->fetch_assoc();
                
                // التحقق من كلمة المرور الحالية
                if (password_verify($current_password, $user['password'])) {
                    if ($new_password === $confirm_password) {
                        if (strlen($new_password) >= 6) {
                            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                            $update_result = $conn->query("UPDATE admin_users SET password = '$new_hashed_password' WHERE id = $user_id");
                            
                            if ($update_result) {
                                $_SESSION['success'] = "✅ تم تغيير كلمة المرور بنجاح";
                            } else {
                                $_SESSION['error'] = "❌ حدث خطأ في تحديث كلمة المرور";
                            }
                        } else {
                            $_SESSION['error'] = "❌ كلمة المرور الجديدة يجب أن تكون 6 أحرف على الأقل";
                        }
                    } else {
                        $_SESSION['error'] = "❌ كلمات المرور الجديدة غير متطابقة";
                    }
                } else {
                    $_SESSION['error'] = "❌ كلمة المرور الحالية غير صحيحة";
                }
            } else {
                $_SESSION['error'] = "❌ المستخدم غير موجود";
            }
            break;
            
        case 'update_settings':
            $site_title = $conn->real_escape_string($_POST['site_title'] ?? '');
            $site_description = $conn->real_escape_string($_POST['site_description'] ?? '');
            $contact_email = $conn->real_escape_string($_POST['contact_email'] ?? '');
            $contact_phone = $conn->real_escape_string($_POST['contact_phone'] ?? '');
            
            // حفظ الإعدادات في قاعدة البيانات
            $check_settings = $conn->query("SELECT id FROM site_settings LIMIT 1");
            if ($check_settings->num_rows > 0) {
                $conn->query("UPDATE site_settings SET site_title='$site_title', site_description='$site_description', contact_email='$contact_email', contact_phone='$contact_phone'");
            } else {
                $conn->query("INSERT INTO site_settings (site_title, site_description, contact_email, contact_phone) VALUES ('$site_title', '$site_description', '$contact_email', '$contact_phone')");
            }
            
            $_SESSION['success'] = "✅ تم حفظ الإعدادات بنجاح";
            break;
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// جلب البيانات من قاعدة البيانات
$about_data = $conn->query("SELECT * FROM about LIMIT 1")->fetch_assoc();
$partnerships = $conn->query("SELECT * FROM partnerships ORDER BY id DESC");
$services = $conn->query("SELECT * FROM services ORDER BY id DESC");
$manufacturing = $conn->query("SELECT * FROM manufacturing ORDER BY id DESC");
$technologies = $conn->query("SELECT * FROM tech ORDER BY id DESC");
$testimonials = $conn->query("SELECT * FROM customer ORDER BY id DESC");
$projects = $conn->query("SELECT * FROM works ORDER BY id DESC");
$jobs = $conn->query("SELECT * FROM job ORDER BY id DESC");

// جلب بيانات المستخدم
$user_id = $_SESSION['user_id'] ?? 1;
$user_result = $conn->query("SELECT * FROM admin_users WHERE id = " . $user_id);
if ($user_result && $user_result->num_rows > 0) {
    $user_data = $user_result->fetch_assoc();
} else {
    // بيانات افتراضية إذا لم يتم العثور على المستخدم
    $user_data = [
        'name' => 'مدير النظام',
        'email' => 'admin@jawaher.com'
    ];
}

// جلب الإعدادات
$settings_result = $conn->query("SELECT * FROM site_settings LIMIT 1");
if ($settings_result && $settings_result->num_rows > 0) {
    $site_settings = $settings_result->fetch_assoc();
} else {
    // استخدام إعدادات افتراضية إذا لم توجد في قاعدة البيانات
    $site_settings = [
        'site_title' => 'جواهر',
        'site_description' => 'شركة رائدة في مجال التصنيع',
        'contact_email' => 'info@jawaher.com',
        'contact_phone' => '+966500000000'
    ];
}

// الإحصائيات
$testimonials_count = $conn->query("SELECT COUNT(*) FROM customer")->fetch_row()[0];
$projects_count = $conn->query("SELECT COUNT(*) FROM works")->fetch_row()[0];
$jobs_count = $conn->query("SELECT COUNT(*) FROM job")->fetch_row()[0];
$partnerships_count = $conn->query("SELECT COUNT(*) FROM partnerships")->fetch_row()[0];
$services_count = $conn->query("SELECT COUNT(*) FROM services")->fetch_row()[0];
$technologies_count = $conn->query("SELECT COUNT(*) FROM tech")->fetch_row()[0];
$manufacturing_count = $conn->query("SELECT COUNT(*) FROM manufacturing")->fetch_row()[0];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جواهر - إدارة الموقع</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c5aa0;
            --secondary: #d4af37;
            --light: #f8f9fa;
            --dark: #2c3e50;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
            --gradient: linear-gradient(135deg, #2c5aa0 0%, #667eea 100%);
            --shadow: 0 5px 15px rgba(0,0,0,0.1);
            --radius: 10px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f5f7fa;
            color: var(--dark);
            direction: rtl;
            min-height: 100vh;
        }

        /* شاشة التحميل */
        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.3s ease;
        }

        .loader {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255,255,255,0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 15px;
        }

        .loading-text {
            color: white;
            font-size: 1.1rem;
        }

        /* الحاوية الرئيسية */
        .container {
            display: flex;
            min-height: 100vh;
        }

        /* الشريط الجانبي */
        .sidebar {
            width: 260px;
            background: white;
            box-shadow: 3px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            height: 100vh;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .sidebar-header {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid #eee;
            background: var(--gradient);
            color: white;
        }

        .sidebar-header h2 {
            font-size: 1.4rem;
            margin-bottom: 5px;
        }

        .sidebar-header p {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .sidebar-menu {
            padding: 15px 0;
            height: calc(100vh - 120px);
            overflow-y: auto;
        }

        .sidebar-menu ul {
            list-style: none;
        }

        .sidebar-menu li {
            margin-bottom: 2px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.3s ease;
            border-right: 3px solid transparent;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #f8f9fa;
            border-right-color: var(--secondary);
            color: var(--primary);
        }

        .sidebar-menu i {
            margin-left: 12px;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        /* المحتوى الرئيسي */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-right: 260px;
            transition: margin 0.3s ease;
        }

        .main-content.expanded {
            margin-right: 0;
        }

        /* الشريط العلوي */
        .top-nav {
            background: white;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .nav-toggle {
            background: var(--primary);
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            border-radius: 20px;
            padding: 8px 15px;
            width: 300px;
            position: relative;
        }

        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            margin-right: 8px;
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 10px;
            box-shadow: var(--shadow);
            max-height: 300px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
        }

        .search-result-item {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background 0.3s;
        }

        .search-result-item:hover {
            background: #f8f9fa;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        /* المحتوى */
        .content {
            padding: 25px;
            flex: 1;
        }

        /* الأقسام */
        .section {
            background: white;
            border-radius: var(--radius);
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: var(--shadow);
        }

        .section.hidden {
            display: none;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .section-header h3 {
            color: var(--primary);
            font-size: 1.3rem;
        }

        /* البطاقات الإحصائية */
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: var(--gradient);
            color: white;
            border-radius: var(--radius);
            padding: 25px 20px;
            text-align: center;
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card i {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .stat-card h3 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        /* الأزرار */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-warning {
            background: var(--warning);
            color: var(--dark);
        }

        .btn-info {
            background: var(--info);
            color: white;
        }

        /* النماذج */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--primary);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: border 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        /* رفع الملفات */
        .file-upload {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
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

        .file-upload-label {
            display: block;
            padding: 15px;
            background: #f8f9fa;
            border: 2px dashed #ddd;
            border-radius: 6px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload-label:hover {
            border-color: var(--primary);
            background: #e9ecef;
        }

        .image-preview {
            max-width: 200px;
            max-height: 150px;
            border-radius: 6px;
            margin-top: 10px;
            display: none;
        }

        /* الجداول */
        .table-container {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #f8f9fa;
            padding: 15px;
            text-align: right;
            font-weight: 600;
            border-bottom: 1px solid #eee;
        }

        table td {
            padding: 12px 15px;
            border-bottom: 1px solid #f8f9fa;
        }

        table tr:hover {
            background: #f8f9fa;
        }

        .table-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }

        /* أزرار الإجراءات */
        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            transform: scale(1.05);
        }

        /* الإشعارات */
        .notification {
            background: var(--success);
            color: white;
            padding: 15px 20px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            animation: slideIn 0.5s ease;
        }

        .notification.error {
            background: var(--danger);
        }

        .notification.info {
            background: var(--info);
        }

        /* أقسام النماذج */
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            border: 1px dashed #ddd;
        }

        /* الأنيميشن */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease;
        }

        /* التجاوب */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .main-content {
                margin-right: 0;
            }
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            .stats-cards {
                grid-template-columns: 1fr;
            }
            .search-box {
                width: 200px;
            }
        }
    </style>
</head>
<body>
    <!-- شاشة التحميل -->
    <div id="loading-screen">
        <div class="loader"></div>
        <div class="loading-text">جاري تحميل لوحة التحكم...</div>
    </div>

    <!-- لوحة التحكم -->
    <div class="container">
        <!-- الشريط الجانبي -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-gem"></i> جواهر</h2>
                <p>إدارة الموقع الإلكتروني</p>
            </div>
            <div class="sidebar-menu">
                <ul>
                    <li><a href="#" class="active" onclick="showSection('dashboard-section')"><i class="fas fa-tachometer-alt"></i> لوحة التحكم</a></li>
                    <li><a href="#" onclick="showSection('about-section')"><i class="fas fa-info-circle"></i> من نحن</a></li>
                    <li><a href="#" onclick="showSection('partnerships-section')"><i class="fas fa-handshake"></i> الشراكات</a></li>
                    <li><a href="#" onclick="showSection('services-section')"><i class="fas fa-concierge-bell"></i> خدماتنا</a></li>
                    <li><a href="#" onclick="showSection('manufacturing-section')"><i class="fas fa-industry"></i> التصنيع</a></li>

                    <li><a href="#" onclick="showSection('testimonials-section')"><i class="fas fa-comments"></i> آراء العملاء</a></li>
                    <li><a href="#" onclick="showSection('projects-section')"><i class="fas fa-briefcase"></i> أعمالنا</a></li>
                    <li><a href="#" onclick="showSection('works-section')"><i class="fas fa-tasks"></i> الأعمال</a></li>
                    <li><a href="#" onclick="showSection('careers-section')"><i class="fas fa-user-tie"></i> التوظيف</a></li>
                    <li><a href="#" onclick="showSection('settings-section')"><i class="fas fa-cog"></i> الإعدادات</a></li>
                    <li><a href="#" onclick="logout()"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a></li>
                </ul>
            </div>
        </div>
        
        <!-- المحتوى الرئيسي -->
        <div class="main-content" id="mainContent">
            <!-- شريط التنقل العلوي -->
            <div class="top-nav">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <button class="nav-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="ابحث في اللوحة..." id="globalSearch" onkeyup="performSearch()">
                        <div class="search-results" id="searchResults"></div>
                    </div>
                </div>
                <div class="user-info">
                    <div>
                        <h4><?php echo $_SESSION['user_name'] ?? 'مدير النظام'; ?></h4>
                        <p>Admin</p>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user_name'] ?? 'مدير النظام'); ?>&background=2c5aa0&color=fff" alt="صورة المستخدم">
                </div>
            </div>
            
            <!-- محتوى الصفحة -->
            <div class="content">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="notification">
                        <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="notification error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <!-- قسم لوحة التحكم -->
                <div id="dashboard-section" class="section">
                    <div class="section-header">
                        <h3><i class="fas fa-tachometer-alt"></i> لوحة التحكم</h3>
                        <div>
                            <button class="btn btn-primary" onclick="refreshStats()">
                                <i class="fas fa-sync-alt"></i> تحديث
                            </button>
                            <button class="btn btn-success" onclick="exportData()">
                                <i class="fas fa-download"></i> تصدير البيانات
                            </button>
                            <button class="btn btn-info" onclick="showSystemInfo()">
                                <i class="fas fa-info-circle"></i> معلومات النظام
                            </button>
                        </div>
                    </div>
                    <div class="stats-cards">
                        <div class="stat-card" onclick="showSection('testimonials-section')">
                            <i class="fas fa-comments"></i>
                            <h3><?php echo $testimonials_count; ?></h3>
                            <p>آراء العملاء</p>
                        </div>
                        <div class="stat-card" onclick="showSection('projects-section')">
                            <i class="fas fa-briefcase"></i>
                            <h3><?php echo $projects_count; ?></h3>
                            <p>أعمالنا</p>
                        </div>
                        <div class="stat-card" onclick="showSection('works-section')">
                            <i class="fas fa-tasks"></i>
                            <h3><?php echo $projects_count; ?></h3>
                            <p>الأعمال</p>
                        </div>
                        <div class="stat-card" onclick="showSection('careers-section')">
                            <i class="fas fa-user-tie"></i>
                            <h3><?php echo $jobs_count; ?></h3>
                            <p>طلبات التوظيف</p>
                        </div>
                        <div class="stat-card" onclick="showSection('partnerships-section')">
                            <i class="fas fa-handshake"></i>
                            <h3><?php echo $partnerships_count; ?></h3>
                            <p>الشراكات</p>
                        </div>
                        <div class="stat-card" onclick="showSection('services-section')">
                            <i class="fas fa-concierge-bell"></i>
                            <h3><?php echo $services_count; ?></h3>
                            <p>الخدمات</p>
                        </div>

                        <div class="stat-card" onclick="showSection('manufacturing-section')">
                            <i class="fas fa-industry"></i>
                            <h3><?php echo $manufacturing_count; ?></h3>
                            <p>عمليات التصنيع</p>
                        </div>
                    </div>

                    <!-- لوحة التحكم السريعة -->
                    <div class="section-header">
                        <h3><i class="fas fa-rocket"></i> أدوات سريعة</h3>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 20px;">
                        <button class="btn btn-primary" onclick="showSection('about-section')">
                            <i class="fas fa-edit"></i> تعديل من نحن
                        </button>
                        <button class="btn btn-success" onclick="showSection('services-section')">
                            <i class="fas fa-plus"></i> إضافة خدمة
                        </button>
                        <button class="btn btn-info" onclick="showSection('projects-section')">
                            <i class="fas fa-briefcase"></i> إضافة عمل
                        </button>
                        <button class="btn btn-warning" onclick="showSection('works-section')">
                            <i class="fas fa-tasks"></i> إدارة الأعمال
                        </button>
                        <button class="btn btn-warning" onclick="showSection('settings-section')">
                            <i class="fas fa-cog"></i> الإعدادات
                        </button>
                    </div>
                </div>

                <!-- قسم من نحن -->
                <div id="about-section" class="section hidden">
                    <div class="section-header">
                        <h3><i class="fas fa-info-circle"></i> من نحن</h3>
                    </div>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="save_about">
                        <div class="form-group">
                            <label>العنوان</label>
                            <input type="text" name="title" class="form-control" value="<?php echo $about_data['title'] ?? ''; ?>" placeholder="أدخل العنوان">
                        </div>
                        <div class="form-group">
                            <label>الشرح</label>
                            <textarea name="descrip" class="form-control" placeholder="أدخل الشرح"><?php echo $about_data['descrip'] ?? ''; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>صورة الشركة</label>
                            <div class="file-upload">
                                <label class="file-upload-label">
                                    <i class="fas fa-cloud-upload-alt"></i> اختر صورة الشركة
                                    <input type="file" name="image" accept="image/*" onchange="previewImage(this, 'about-preview')">
                                </label>
                            </div>
                            <?php if (!empty($about_data['image'])): ?>
                                <img id="about-preview" src="<?php echo $about_data['image']; ?>" class="image-preview" style="display: block;" alt="صورة الشركة">
                            <?php else: ?>
                                <img id="about-preview" src="#" class="image-preview" alt="معاينة الصورة">
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> حفظ التغييرات</button>
                    </form>
                </div>

                <!-- قسم الشراكات -->
                <div id="partnerships-section" class="section hidden">
                    <div class="section-header">
                        <h3><i class="fas fa-handshake"></i> الشراكات</h3>
                    </div>
                    
                    <div class="form-section">
                        <h4>إضافة شريك جديد</h4>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="add_partnership">
                            <div class="form-group">
                                <label>اسم الشريك</label>
                                <input type="text" name="title" class="form-control" placeholder="أدخل اسم الشريك" required>
                            </div>
                            <div class="form-group">
                                <label>شعار الشريك</label>
                                <div class="file-upload">
                                    <label class="file-upload-label">
                                        <i class="fas fa-cloud-upload-alt"></i> اختر شعار الشريك
                                        <input type="file" name="image" accept="image/*" onchange="previewImage(this, 'partnership-preview')">
                                    </label>
                                </div>
                                <img id="partnership-preview" src="#" class="image-preview" alt="معاينة الشعار">
                            </div>
                            <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> إضافة الشريك</button>
                        </form>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الشعار</th>
                                    <th>اسم الشريك</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($partnership = $partnerships->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $partnership['id']; ?></td>
                                    <td>
                                        <?php if ($partnership['image'] && $partnership['image'] !== 'default.jpg'): ?>
                                            <img src="<?php echo $partnership['image']; ?>" class="table-image" alt="شعار الشريك">
                                        <?php else: ?>
                                            <div style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-building" style="color: #6c757d;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $partnership['title']; ?></td>
                                    <td class="action-buttons">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="delete_partnership">
                                            <input type="hidden" name="id" value="<?php echo $partnership['id']; ?>">
                                            <button type="submit" class="action-btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا الشريك؟')">
                                                <i class="fas fa-trash"></i> حذف
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- قسم خدماتنا -->
                <div id="services-section" class="section hidden">
                    <div class="section-header">
                        <h3><i class="fas fa-concierge-bell"></i> خدماتنا</h3>
                    </div>
                    
                    <div class="form-section">
                        <h4>إضافة خدمة جديدة</h4>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="add_service">
                            <div class="form-group">
                                <label>اسم الخدمة</label>
                                <input type="text" name="title" class="form-control" placeholder="أدخل اسم الخدمة" required>
                            </div>
                            <div class="form-group">
                                <label>وصف الخدمة</label>
                                <textarea name="descrip" class="form-control" placeholder="أدخل وصف الخدمة" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>صورة الخدمة</label>
                                <div class="file-upload">
                                    <label class="file-upload-label">
                                        <i class="fas fa-cloud-upload-alt"></i> اختر صورة للخدمة
                                        <input type="file" name="image" accept="image/*" onchange="previewImage(this, 'service-preview')">
                                    </label>
                                </div>
                                <img id="service-preview" src="#" class="image-preview" alt="معاينة الصورة">
                            </div>
                            <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> إضافة الخدمة</button>
                        </form>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الصورة</th>
                                    <th>اسم الخدمة</th>
                                    <th>الوصف</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($service = $services->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $service['id']; ?></td>
                                    <td>
                                        <?php if ($service['image'] && $service['image'] !== 'default.jpg'): ?>
                                            <img src="<?php echo $service['image']; ?>" class="table-image" alt="صورة الخدمة">
                                        <?php else: ?>
                                            <div style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-cog" style="color: #6c757d;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $service['title']; ?></td>
                                    <td><?php echo substr($service['descrip'], 0, 50) . '...'; ?></td>
                                    <td class="action-buttons">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="delete_service">
                                            <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                                            <button type="submit" class="action-btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذه الخدمة؟')">
                                                <i class="fas fa-trash"></i> حذف
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- قسم التصنيع -->
                <div id="manufacturing-section" class="section hidden">
                    <div class="section-header">
                        <h3><i class="fas fa-industry"></i> التصنيع</h3>
                    </div>
                    
                    <div class="form-section">
                        <h4>إضافة عملية تصنيع جديدة</h4>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="add_manufacturing">
                            <div class="form-group">
                                <label>اسم العملية</label>
                                <input type="text" name="title" class="form-control" placeholder="أدخل اسم عملية التصنيع" required>
                            </div>
                            <div class="form-group">
                                <label>وصف العملية</label>
                                <textarea name="descrip" class="form-control" placeholder="أدخل وصف عملية التصنيع" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>صورة العملية</label>
                                <div class="file-upload">
                                    <label class="file-upload-label">
                                        <i class="fas fa-cloud-upload-alt"></i> اختر صورة لعملية التصنيع
                                        <input type="file" name="image" accept="image/*" onchange="previewImage(this, 'manufacturing-preview')">
                                    </label>
                                </div>
                                <img id="manufacturing-preview" src="#" class="image-preview" alt="معاينة الصورة">
                            </div>
                            <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> إضافة العملية</button>
                        </form>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الصورة</th>
                                    <th>اسم العملية</th>
                                    <th>الوصف</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($manufacturing_item = $manufacturing->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $manufacturing_item['id']; ?></td>
                                    <td>
                                        <?php if ($manufacturing_item['image'] && $manufacturing_item['image'] !== 'default.jpg'): ?>
                                            <img src="<?php echo $manufacturing_item['image']; ?>" class="table-image" alt="صورة العملية">
                                        <?php else: ?>
                                            <div style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-industry" style="color: #6c757d;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $manufacturing_item['title']; ?></td>
                                    <td><?php echo substr($manufacturing_item['descrip'], 0, 80) . '...'; ?></td>
                                    <td class="action-buttons">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="delete_manufacturing">
                                            <input type="hidden" name="id" value="<?php echo $manufacturing_item['id']; ?>">
                                            <button type="submit" class="action-btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف عملية التصنيع هذه؟')">
                                                <i class="fas fa-trash"></i> حذف
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- قسم آراء العملاء -->
                <div id="testimonials-section" class="section hidden">
                    <div class="section-header">
                        <h3><i class="fas fa-comments"></i> آراء العملاء</h3>
                    </div>
                    
                    <div class="form-section">
                        <h4>إضافة رأي جديد</h4>
                        <form method="POST">
                            <input type="hidden" name="action" value="add_testimonial">
                            <div class="form-group">
                                <label>اسم العميل</label>
                                <input type="text" name="name" class="form-control" placeholder="أدخل اسم العميل" required>
                            </div>
                            <div class="form-group">
                                <label>رأي العميل</label>
                                <textarea name="opinions" class="form-control" placeholder="أدخل رأي العميل" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> إضافة الرأي</button>
                        </form>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم العميل</th>
                                    <th>الرأي</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($testimonial = $testimonials->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $testimonial['id']; ?></td>
                                    <td><?php echo $testimonial['name']; ?></td>
                                    <td><?php echo substr($testimonial['opinions'], 0, 80) . '...'; ?></td>
                                    <td class="action-buttons">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="delete_testimonial">
                                            <input type="hidden" name="id" value="<?php echo $testimonial['id']; ?>">
                                            <button type="submit" class="action-btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا الرأي؟')">
                                                <i class="fas fa-trash"></i> حذف
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- قسم أعمالنا -->
                <div id="projects-section" class="section hidden">
                    <div class="section-header">
                        <h3><i class="fas fa-briefcase"></i> أعمالنا</h3>
                    </div>
                    
                    <div class="form-section">
                        <h4>إضافة عمل جديد</h4>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="add_project">
                            <div class="form-group">
                                <label>اسم العمل</label>
                                <input type="text" name="title" class="form-control" placeholder="أدخل اسم العمل" required>
                            </div>
                            <div class="form-group">
                                <label>وصف العمل</label>
                                <textarea name="descrip" class="form-control" placeholder="أدخل وصف العمل" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>صورة العمل</label>
                                <div class="file-upload">
                                    <label class="file-upload-label">
                                        <i class="fas fa-cloud-upload-alt"></i> اختر صورة للعمل
                                        <input type="file" name="image" accept="image/*" onchange="previewImage(this, 'project-preview')">
                                    </label>
                                </div>
                                <img id="project-preview" src="#" class="image-preview" alt="معاينة الصورة">
                            </div>
                            <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> إضافة العمل</button>
                        </form>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الصورة</th>
                                    <th>اسم العمل</th>
                                    <th>الوصف</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($project = $projects->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $project['id']; ?></td>
                                    <td>
                                        <?php if ($project['image'] && $project['image'] !== 'default.jpg'): ?>
                                            <img src="<?php echo $project['image']; ?>" class="table-image" alt="صورة العمل">
                                        <?php else: ?>
                                            <div style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-briefcase" style="color: #6c757d;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $project['title']; ?></td>
                                    <td><?php echo substr($project['descrip'], 0, 80) . '...'; ?></td>
                                    <td class="action-buttons">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="delete_project">
                                            <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                                            <button type="submit" class="action-btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا العمل؟')">
                                                <i class="fas fa-trash"></i> حذف
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- قسم الأعمال -->
                <div id="works-section" class="section hidden">
                    <div class="section-header">
                        <h3><i class="fas fa-tasks"></i> الأعمال</h3>
                    </div>
                    
                    <div class="form-section">
                        <h4>إضافة عمل جديد</h4>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="add_project">
                            <div class="form-group">
                                <label>اسم العمل</label>
                                <input type="text" name="title" class="form-control" placeholder="أدخل اسم العمل" required>
                            </div>
                            <div class="form-group">
                                <label>وصف العمل</label>
                                <textarea name="descrip" class="form-control" placeholder="أدخل وصف العمل" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>صورة العمل</label>
                                <div class="file-upload">
                                    <label class="file-upload-label">
                                        <i class="fas fa-cloud-upload-alt"></i> اختر صورة للعمل
                                        <input type="file" name="image" accept="image/*" onchange="previewImage(this, 'work-preview')">
                                    </label>
                                </div>
                                <img id="work-preview" src="#" class="image-preview" alt="معاينة الصورة">
                            </div>
                            <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> إضافة العمل</button>
                        </form>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الصورة</th>
                                    <th>اسم العمل</th>
                                    <th>الوصف</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // إعادة تعيين مؤشر النتائج لاستخدامها مرة أخرى
                                $works = $conn->query("SELECT * FROM works ORDER BY id DESC");
                                while ($work = $works->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $work['id']; ?></td>
                                    <td>
                                        <?php if ($work['image'] && $work['image'] !== 'default.jpg'): ?>
                                            <img src="<?php echo $work['image']; ?>" class="table-image" alt="صورة العمل">
                                        <?php else: ?>
                                            <div style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-tasks" style="color: #6c757d;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $work['title']; ?></td>
                                    <td><?php echo substr($work['descrip'], 0, 80) . '...'; ?></td>
                                    <td class="action-buttons">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="delete_project">
                                            <input type="hidden" name="id" value="<?php echo $work['id']; ?>">
                                            <button type="submit" class="action-btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا العمل؟')">
                                                <i class="fas fa-trash"></i> حذف
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- قسم التوظيف - محدث لعرض ملفات السيرة الذاتية -->
                <div id="careers-section" class="section hidden">
                    <div class="section-header">
                        <h3><i class="fas fa-user-tie"></i> التوظيف</h3>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الهاتف</th>
                                    <th>الوظيفة</th>
                                    <th>الرسالة</th>
                                    <th>السيرة الذاتية</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // إعادة جلب بيانات التوظيف مع ملفات السيرة الذاتية
                                $jobs_result = $conn->query("SELECT * FROM job ORDER BY id DESC");
                                while ($job = $jobs_result->fetch_assoc()): 
                                ?>
                                <tr>
                                    <td><?php echo $job['id']; ?></td>
                                    <td><?php echo $job['name']; ?></td>
                                    <td><?php echo $job['gmail']; ?></td>
                                    <td><?php echo $job['phon']; ?></td>
                                    <td><?php echo $job['jop']; ?></td>
                                    <td><?php echo substr($job['message'], 0, 30) . '...'; ?></td>
                                    <td class="action-buttons">
                                        <?php if (!empty($job['cv_file']) && file_exists($job['cv_file'])): ?>
                                            <a href="<?php echo $job['cv_file']; ?>" class="action-btn btn-info" download title="تحميل السيرة الذاتية">
                                                <i class="fas fa-download"></i> تحميل
                                            </a>
                                        <?php else: ?>
                                            <span class="action-btn btn-warning" style="cursor: default; opacity: 0.6;">
                                                <i class="fas fa-times"></i> غير متوفر
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="action-buttons">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="delete_job">
                                            <input type="hidden" name="id" value="<?php echo $job['id']; ?>">
                                            <button type="submit" class="action-btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف طلب التوظيف هذا؟')">
                                                <i class="fas fa-trash"></i> حذف
                                            </button>
                                        </form>
                                        <button class="action-btn btn-info" onclick="viewJobDetails(<?php echo $job['id']; ?>)">
                                            <i class="fas fa-eye"></i> عرض
                                        </button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- قسم الإعدادات -->
                <div id="settings-section" class="section hidden">
                    <div class="section-header">
                        <h3><i class="fas fa-cog"></i> الإعدادات</h3>
                    </div>
                    
                    <div class="form-section">
                        <h4><i class="fas fa-user-cog"></i> الملف الشخصي</h4>
                        <form method="POST">
                            <input type="hidden" name="action" value="update_profile">
                            <div class="form-group">
                                <label>اسم المستخدم</label>
                                <input type="text" name="name" class="form-control" value="<?php echo $user_data['name'] ?? 'مدير النظام'; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>البريد الإلكتروني</label>
                                <input type="email" name="email" class="form-control" value="<?php echo $user_data['email'] ?? 'admin@jawaher.com'; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> حفظ التغييرات</button>
                        </form>
                    </div>
                    
                    <div class="form-section">
                        <h4><i class="fas fa-key"></i> تغيير كلمة المرور</h4>
                        <form method="POST">
                            <input type="hidden" name="action" value="change_password">
                            <div class="form-group">
                                <label>كلمة المرور الحالية</label>
                                <input type="password" name="current_password" class="form-control" placeholder="أدخل كلمة المرور الحالية" required>
                            </div>
                            <div class="form-group">
                                <label>كلمة المرور الجديدة</label>
                                <input type="password" name="new_password" class="form-control" placeholder="أدخل كلمة المرور الجديدة" required>
                            </div>
                            <div class="form-group">
                                <label>تأكيد كلمة المرور الجديدة</label>
                                <input type="password" name="confirm_password" class="form-control" placeholder="أعد إدخال كلمة المرور الجديدة" required>
                            </div>
                            <button type="submit" class="btn btn-warning"><i class="fas fa-key"></i> تغيير كلمة المرور</button>
                        </form>
                    </div>
                    
                    <div class="form-section">
                        <h4><i class="fas fa-globe"></i> إعدادات الموقع</h4>
                        <form method="POST">
                            <input type="hidden" name="action" value="update_settings">
                            <div class="form-group">
                                <label>عنوان الموقع</label>
                                <input type="text" name="site_title" class="form-control" value="<?php echo $site_settings['site_title']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>وصف الموقع</label>
                                <textarea name="site_description" class="form-control" required><?php echo $site_settings['site_description']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>البريد الإلكتروني للتواصل</label>
                                <input type="email" name="contact_email" class="form-control" value="<?php echo $site_settings['contact_email']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>رقم الهاتف</label>
                                <input type="text" name="contact_phone" class="form-control" value="<?php echo $site_settings['contact_phone']; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> حفظ الإعدادات</button>
                        </form>
                    </div>
                    
                    <div class="form-section">
                        <h4><i class="fas fa-palette"></i> التخصيص</h4>
                        <div class="form-group">
                            <label>اللون الأساسي</label>
                            <input type="color" id="primaryColor" value="#2c5aa0" onchange="changeTheme()" style="width: 60px; height: 40px; border: none; border-radius: 5px; cursor: pointer;">
                        </div>
                        <div class="form-group">
                            <label>اللون الثانوي</label>
                            <input type="color" id="secondaryColor" value="#d4af37" onchange="changeTheme()" style="width: 60px; height: 40px; border: none; border-radius: 5px; cursor: pointer;">
                        </div>
                        <button class="btn btn-primary" onclick="resetTheme()">
                            <i class="fas fa-undo"></i> إعادة التعيين
                        </button>
                    </div>
                    
                    <div class="form-section">
                        <h4><i class="fas fa-database"></i> إدارة البيانات</h4>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <button class="btn btn-info" onclick="backupData()">
                                <i class="fas fa-download"></i> نسخ احتياطي
                            </button>
                            <button class="btn btn-warning" onclick="clearCache()">
                                <i class="fas fa-broom"></i> مسح الذاكرة المؤقتة
                            </button>
                            <button class="btn btn-danger" onclick="showSystemInfo()">
                                <i class="fas fa-info-circle"></i> معلومات النظام
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- تذييل الصفحة -->
            <div style="text-align: center; padding: 20px; background: var(--dark); color: white; margin-top: 30px;">
                <p>تم التصميم والتطوير بواسطة فريق <strong>edara</strong> | جميع الحقوق محفوظة &copy; 2024</p>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // ===== تهيئة الصفحة =====
        document.addEventListener('DOMContentLoaded', function() {
            // إخفاء شاشة التحميل بسرعة
            setTimeout(() => {
                const loadingScreen = document.getElementById('loading-screen');
                loadingScreen.style.opacity = '0';
                setTimeout(() => {
                    loadingScreen.style.display = 'none';
                }, 300);
            }, 800);

            // تحميل الثيم المحفوظ
            loadSavedTheme();
            
            // إخفاء الإشعارات بعد 5 ثواني
            setTimeout(() => {
                document.querySelectorAll('.notification').forEach(notification => {
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                });
            }, 5000);
            
            // إغلاق نتائج البحث عند النقر خارجها
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.search-box')) {
                    document.getElementById('searchResults').style.display = 'none';
                }
            });
        });

        // ===== الدوال الأساسية =====
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }

        function showSection(sectionId) {
            // إخفاء جميع الأقسام
            document.querySelectorAll('.section').forEach(section => {
                section.classList.add('hidden');
            });
            
            // إظهار القسم المطلوب
            const targetSection = document.getElementById(sectionId);
            targetSection.classList.remove('hidden');
            
            // تحديث القائمة النشطة
            document.querySelectorAll('.sidebar-menu a').forEach(link => {
                link.classList.remove('active');
            });
            
            // العثور على الرابط المناسب في القائمة
            const menuLinks = {
                'dashboard-section': 'لوحة التحكم',
                'about-section': 'من نحن',
                'partnerships-section': 'الشراكات',
                'services-section': 'خدماتنا',
                'manufacturing-section': 'التصنيع',
                'technologies-section': 'التقنيات',
                'testimonials-section': 'آراء العملاء',
                'projects-section': 'أعمالنا',
                'works-section': 'الأعمال',
                'careers-section': 'التوظيف',
                'settings-section': 'الإعدادات'
            };
            
            document.querySelectorAll('.sidebar-menu a').forEach(link => {
                if (link.textContent.includes(menuLinks[sectionId])) {
                    link.classList.add('active');
                }
            });
            
            // إغلاق الشريط الجانبي على الأجهزة المحمولة
            if (window.innerWidth <= 768) {
                toggleSidebar();
            }
            
            // إغلاق نتائج البحث
            document.getElementById('searchResults').style.display = 'none';
        }

        function refreshStats() {
            const btn = event.target;
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحديث...';
            btn.disabled = true;
            
            setTimeout(() => {
                location.reload();
            }, 1000);
        }

        function exportData() {
            // إنشاء بيانات التصدير
            const data = {
                testimonials: <?php echo $testimonials_count; ?>,
                projects: <?php echo $projects_count; ?>,
                works: <?php echo $projects_count; ?>,
                jobs: <?php echo $jobs_count; ?>,
                partnerships: <?php echo $partnerships_count; ?>,
                services: <?php echo $services_count; ?>,
                technologies: <?php echo $technologies_count; ?>,
                manufacturing: <?php echo $manufacturing_count; ?>,
                exportDate: new Date().toLocaleString('ar-EG')
            };
            
            // تحويل البيانات إلى نص
            const dataStr = JSON.stringify(data, null, 2);
            const dataBlob = new Blob([dataStr], { type: 'application/json' });
            
            // إنشاء رابط التدميل
            const url = URL.createObjectURL(dataBlob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `jawaher_export_${new Date().toISOString().split('T')[0]}.json`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            // عرض إشعار النجاح
            showNotification('تم تصدير البيانات بنجاح!', 'success');
        }

        // ===== نظام البحث المحسن =====
        function performSearch() {
            const searchTerm = document.getElementById('globalSearch').value.toLowerCase().trim();
            const searchResults = document.getElementById('searchResults');
            
            if (searchTerm.length < 2) {
                searchResults.style.display = 'none';
                // إعادة عرض كل شيء إذا كان البحث أقل من حرفين
                document.querySelectorAll('table tbody tr').forEach(row => {
                    row.style.display = '';
                });
                return;
            }
            
            // بيانات الأقسام للبحث
            const sections = [
                { id: 'dashboard-section', name: 'لوحة التحكم', keywords: ['لوحة', 'تحكم', 'dashboard', 'رئيسية'] },
                { id: 'about-section', name: 'من نحن', keywords: ['من', 'نحن', 'about', 'شركة', 'معلومات'] },
                { id: 'partnerships-section', name: 'الشراكات', keywords: ['شراكات', 'شركاء', 'partnerships', 'شريك'] },
                { id: 'services-section', name: 'خدماتنا', keywords: ['خدمات', 'خدمة', 'services', 'خدماتنا'] },
                { id: 'manufacturing-section', name: 'التصنيع', keywords: ['تصنيع', 'صناعة', 'manufacturing', 'إنتاج'] },
                { id: 'technologies-section', name: 'التقنيات', keywords: ['تقنيات', 'تكنولوجيا', 'technologies', 'تقنية'] },
                { id: 'testimonials-section', name: 'آراء العملاء', keywords: ['آراء', 'عملاء', 'testimonials', 'رأي', 'عميل'] },
                { id: 'projects-section', name: 'أعمالنا', keywords: ['أعمال', 'مشاريع', 'projects', 'عمل'] },
                { id: 'works-section', name: 'الأعمال', keywords: ['أعمال', 'مشاريع', 'works', 'عمل'] },
                { id: 'careers-section', name: 'التوظيف', keywords: ['توظيف', 'وظائف', 'careers', 'طلب', 'توظيف'] },
                { id: 'settings-section', name: 'الإعدادات', keywords: ['إعدادات', 'settings', 'تخصيص', 'ملف', 'شخصي'] }
            ];
            
            let foundResults = [];
            
            // البحث في أسماء الأقسام والكلمات المفتاحية
            sections.forEach(section => {
                if (section.name.includes(searchTerm) || 
                    section.keywords.some(keyword => keyword.includes(searchTerm))) {
                    foundResults.push({
                        type: 'section',
                        name: section.name,
                        id: section.id
                    });
                }
            });
            
            // البحث في الجداول
            document.querySelectorAll('table tbody tr').forEach(row => {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchTerm)) {
                    const table = row.closest('table');
                    const section = table.closest('.section');
                    if (section) {
                        const sectionName = section.querySelector('h3').textContent;
                        foundResults.push({
                            type: 'data',
                            name: `بيانات في ${sectionName}`,
                            id: section.id,
                            element: row
                        });
                    }
                }
            });
            
            // عرض نتائج البحث
            if (foundResults.length > 0) {
                let resultsHTML = '';
                foundResults.forEach(result => {
                    resultsHTML += `
                        <div class="search-result-item" onclick="navigateToSearchResult('${result.id}', '${result.type}')">
                            <strong>${result.name}</strong>
                            <span style="font-size: 0.8rem; color: #666;">${result.type === 'section' ? 'قسم' : 'بيانات'}</span>
                        </div>
                    `;
                });
                searchResults.innerHTML = resultsHTML;
                searchResults.style.display = 'block';
            } else {
                searchResults.innerHTML = '<div class="search-result-item">لم يتم العثور على نتائج</div>';
                searchResults.style.display = 'block';
            }
        }

        function navigateToSearchResult(sectionId, type) {
            showSection(sectionId);
            
            if (type === 'data') {
                // تمييز الصف في الجدول
                setTimeout(() => {
                    const section = document.getElementById(sectionId);
                    const tables = section.querySelectorAll('table');
                    tables.forEach(table => {
                        const rows = table.querySelectorAll('tbody tr');
                        rows.forEach(row => {
                            if (row.textContent.toLowerCase().includes(document.getElementById('globalSearch').value.toLowerCase())) {
                                row.style.background = 'rgba(44, 90, 160, 0.1)';
                                row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                setTimeout(() => {
                                    row.style.background = '';
                                }, 3000);
                            }
                        });
                    });
                }, 300);
            }
            
            document.getElementById('searchResults').style.display = 'none';
        }

        // ===== معاينة الصور =====
        function previewImage(input, previewId) {
            const file = input.files[0];
            const preview = document.getElementById(previewId);
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        }

        // ===== نظام الثيم =====
        function changeTheme() {
            const primaryColor = document.getElementById('primaryColor').value;
            const secondaryColor = document.getElementById('secondaryColor').value;
            
            // تحديث CSS Variables
            document.documentElement.style.setProperty('--primary', primaryColor);
            document.documentElement.style.setProperty('--secondary', secondaryColor);
            document.documentElement.style.setProperty('--gradient', `linear-gradient(135deg, ${primaryColor} 0%, #667eea 100%)`);
            
            // حفظ الإعدادات في localStorage
            localStorage.setItem('theme-primary', primaryColor);
            localStorage.setItem('theme-secondary', secondaryColor);
            
            showNotification('تم تغيير الألوان بنجاح!', 'success');
        }

        function resetTheme() {
            document.getElementById('primaryColor').value = '#2c5aa0';
            document.getElementById('secondaryColor').value = '#d4af37';
            changeTheme();
            showNotification('تم إعادة تعيين الألوان إلى الإعدادات الافتراضية', 'success');
        }

        // تحميل الإعدادات المحفوظة
        function loadSavedTheme() {
            const savedPrimary = localStorage.getItem('theme-primary');
            const savedSecondary = localStorage.getItem('theme-secondary');
            
            if (savedPrimary && savedSecondary) {
                document.getElementById('primaryColor').value = savedPrimary;
                document.getElementById('secondaryColor').value = savedSecondary;
                changeTheme();
            }
        }

        // ===== نظام الإشعارات =====
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                ${message}
            `;
            
            const content = document.querySelector('.content');
            content.insertBefore(notification, content.firstChild);
            
            // إزالة الإشعار بعد 5 ثواني
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    notification.remove();
                }, 500);
            }, 5000);
        }

        // ===== ميزات إضافية =====
        function showSystemInfo() {
            const info = `
                <strong>معلومات النظام:</strong><br>
                - المتصفح: ${navigator.userAgent}<br>
                - اللغة: ${navigator.language}<br>
                - المنصة: ${navigator.platform}<br>
                - الذاكرة: ${navigator.deviceMemory || 'غير معروف'} GB<br>
                - المعالجات: ${navigator.hardwareConcurrency || 'غير معروف'}<br>
                - الوقت: ${new Date().toLocaleString('ar-EG')}
            `;
            
            showNotification(info, 'info');
        }

        function backupData() {
            showNotification('جاري إنشاء نسخة احتياطية من البيانات...', 'info');
            setTimeout(() => {
                showNotification('تم إنشاء النسخة الاحتياطية بنجاح!', 'success');
                exportData(); // استخدام وظيفة التصدير الحالية
            }, 2000);
        }

        function clearCache() {
            localStorage.removeItem('theme-primary');
            localStorage.removeItem('theme-secondary');
            showNotification('تم مسح الذاكرة المؤقتة بنجاح!', 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        }

        function viewJobDetails(jobId) {
            // عرض تفاصيل طلب التوظيف
            showNotification(`جاري تحميل تفاصيل طلب التوظيف #${jobId}...`, 'info');
            // يمكن إضافة منطق عرض التفاصيل في نافذة منبثقة هنا
        }

        function logout() {
            if (confirm('هل أنت متأكد من تسجيل الخروج؟')) {
                window.location.href = 'logout.php';
            }
        }

        // ===== اختصارات لوحة المفاتيح =====
        document.addEventListener('keydown', function(e) {
            // Ctrl + K للبحث
            if (e.ctrlKey && e.key === 'k') {
                e.preventDefault();
                document.getElementById('globalSearch').focus();
            }
            
            // Escape لإلغاء البحث
            if (e.key === 'Escape') {
                document.getElementById('globalSearch').value = '';
                document.getElementById('searchResults').style.display = 'none';
                performSearch();
            }
        });

        // ===== تهيئة إضافية =====
        window.addEventListener('load', function() {
            // إضافة تأثيرات للعناصر
            document.querySelectorAll('.stat-card, .btn').forEach(element => {
                element.style.transition = 'all 0.3s ease';
            });
            
            // تحسين أداء الصور
            document.querySelectorAll('img').forEach(img => {
                img.loading = 'lazy';
            });
        });

        // ===== التعامل مع الأخطاء =====
        window.addEventListener('error', function(e) {
            console.error('حدث خطأ:', e.error);
            showNotification('حدث خطأ غير متوقع. يرجى تحديث الصفحة.', 'error');
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>