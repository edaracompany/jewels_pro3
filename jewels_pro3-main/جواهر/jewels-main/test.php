<?php
// تشغيل جميع الأخطاء
error_reporting(E_ALL);
ini_set('display_errors', 1);

// الاتصال المباشر بقاعدة البيانات
$host = "localhost";
$username = "root"; 
$password = "";
$dbname = "jawaherest";

echo "<h2>🔍 بدء التحقق من المشاكل...</h2>";

// 1. التحقق من الاتصال بقاعدة البيانات
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("<h3 style='color: red;'>❌ فشل الاتصال بقاعدة البيانات: " . $conn->connect_error . "</h3>");
} else {
    echo "<h3 style='color: green;'>✅ الاتصال بقاعدة البيانات ناجح</h3>";
}

// 2. التحقق من الجداول
echo "<h3>📊 التحقق من الجداول:</h3>";
$tables = ['about', 'partnerships', 'services', 'works'];
foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✅ جدول $table موجود</p>";
    } else {
        echo "<p style='color: red;'>❌ جدول $table غير موجود</p>";
    }
}

// 3. التحقق من بيانات about
echo "<h3>📝 بيانات من نحن:</h3>";
$result = $conn->query("SELECT * FROM about");
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "<pre>";
    print_r($row);
    echo "</pre>";
    
    // التحقق من الصورة
    $image_path = $row['image'];
    echo "<p>مسار الصورة في قاعدة البيانات: <strong>$image_path</strong></p>";
    
    if (file_exists($image_path)) {
        echo "<p style='color: green;'>✅ الصورة موجودة في المسار المحدد</p>";
        echo "<img src='$image_path' style='max-width: 300px; border: 3px solid green;'><br>";
    } else {
        echo "<p style='color: red;'>❌ الصورة غير موجودة في المسار المحدد</p>";
        
        // البحث عن الصورة في مسارات مختلفة
        $possible_paths = [
            $image_path,
            'uploads/' . $image_path,
            'img/' . $image_path,
            '../' . $image_path,
            basename($image_path), // اسم الملف فقط
            'uploads/' . basename($image_path),
            'img/' . basename($image_path)
        ];
        
        echo "<p>🔍 البحث في المسارات البديلة:</p>";
        $found = false;
        foreach ($possible_paths as $path) {
            if (file_exists($path)) {
                echo "<p style='color: green;'>✅ وجدت الصورة في: $path</p>";
                echo "<img src='$path' style='max-width: 300px; border: 3px solid blue;'><br>";
                $found = true;
                break;
            } else {
                echo "<p style='color: orange;'>❌ غير موجود: $path</p>";
            }
        }
        
        if (!$found) {
            echo "<p style='color: red;'>❌ لم أجد الصورة في أي مسار</p>";
        }
    }
} else {
    echo "<p style='color: red;'>❌ لا توجد بيانات في جدول about</p>";
}

// 4. التحقق من partnerships
echo "<h3>🤝 بيانات الشراكات:</h3>";
$result = $conn->query("SELECT * FROM partnerships");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px;'>";
        echo "<pre>";
        print_r($row);
        echo "</pre>";
        
        $image_path = $row['image'];
        echo "<p>مسار الصورة: <strong>$image_path</strong></p>";
        
        if (file_exists($image_path)) {
            echo "<p style='color: green;'>✅ الصورة موجودة</p>";
            echo "<img src='$image_path' style='max-width: 150px; border: 2px solid green;'>";
        } else {
            echo "<p style='color: red;'>❌ الصورة غير موجودة</p>";
        }
        echo "</div>";
    }
} else {
    echo "<p style='color: orange;'>⚠️ لا توجد شراكات في قاعدة البيانات</p>";
}

// 5. إصلاح تلقائي للبيانات
echo "<h3>🛠️ محاولة الإصلاح التلقائي:</h3>";

// إنشاء مجلد uploads إذا لم يكن موجوداً
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
    echo "<p style='color: green;'>✅ تم إنشاء مجلد uploads</p>";
}

// إنشاء مجلد img إذا لم يكن موجوداً
if (!file_exists('img')) {
    mkdir('img', 0777, true);
    echo "<p style='color: green;'>✅ تم إنشاء مجلد img</p>";
}

// إنشاء صورة افتراضية
$default_image = 'img/default.jpg';
if (!file_exists($default_image)) {
    // إنشاء صورة افتراضية بسيطة
    $im = imagecreate(200, 200);
    $bg_color = imagecolorallocate($im, 74, 119, 191); // لون أزرق
    $text_color = imagecolorallocate($im, 255, 255, 255);
    imagestring($im, 5, 50, 90, 'جواهر', $text_color);
    imagejpeg($im, $default_image);
    imagedestroy($im);
    echo "<p style='color: green;'>✅ تم إنشاء صورة افتراضية</p>";
    echo "<img src='$default_image' style='max-width: 200px;'>";
}

$conn->close();
?>

<hr>

<h2>🎯 الحل النهائي لعرض الصور:</h2>

<?php
// إعادة الاتصال لعرض البيانات بشكل صحيح
$conn = new mysqli($host, $username, $password, $dbname);
?>

<div style="background: white; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>من نحن</h3>
    <?php
    $result = $conn->query("SELECT * FROM about LIMIT 1");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = $row['image'];
        
        // البحث عن المسار الصحيح للصورة
        if (!file_exists($image_path)) {
            $possible_paths = [
                $image_path,
                'uploads/' . $image_path,
                'img/' . $image_path,
                'uploads/' . basename($image_path),
                'img/' . basename($image_path),
                basename($image_path)
            ];
            
            foreach ($possible_paths as $path) {
                if (file_exists($path)) {
                    $image_path = $path;
                    break;
                }
            }
            
            // إذا لم توجد الصورة، استخدم الصورة الافتراضية
            if (!file_exists($image_path)) {
                $image_path = 'img/default.jpg';
            }
        }
        ?>
        
        <img src="<?php echo $image_path; ?>" 
             alt="صورة الشركة" 
             style="max-width: 400px; border-radius: 10px; border: 2px solid #333;"
             onerror="this.src='img/default.jpg'">
        <p><strong><?php echo $row['title'] ?? 'مصنع جواهر'; ?></strong></p>
        <p><?php echo $row['descrip'] ?? 'شركة رائدة في مجال التصنيع'; ?></p>
    <?php } else { ?>
        <p>❌ لا توجد بيانات في جدول about</p>
        <img src="img/default.jpg" alt="صورة افتراضية" style="max-width: 400px;">
    <?php } ?>
</div>

<div style="background: white; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>شركائنا</h3>
    <?php
    $result = $conn->query("SELECT * FROM partnerships");
    if ($result && $result->num_rows > 0) {
        echo '<div style="display: flex; gap: 20px; flex-wrap: wrap;">';
        while ($row = $result->fetch_assoc()) {
            $image_path = $row['image'];
            
            // البحث عن المسار الصحيح
            if (!file_exists($image_path)) {
                $possible_paths = [
                    'uploads/' . $image_path,
                    'img/' . $image_path,
                    'uploads/' . basename($image_path),
                    'img/' . basename($image_path),
                    basename($image_path)
                ];
                
                foreach ($possible_paths as $path) {
                    if (file_exists($path)) {
                        $image_path = $path;
                        break;
                    }
                }
                
                if (!file_exists($image_path)) {
                    $image_path = 'img/default.jpg';
                }
            }
            ?>
            
            <div style="text-align: center; border: 1px solid #ddd; padding: 15px; border-radius: 10px;">
                <img src="<?php echo $image_path; ?>" 
                     alt="<?php echo $row['title']; ?>" 
                     style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px;"
                     onerror="this.src='img/default.jpg'">
                <p><strong><?php echo $row['title']; ?></strong></p>
            </div>
        <?php }
        echo '</div>';
    } else {
        echo '<p>❌ لا توجد شراكات في قاعدة البيانات</p>';
    }
    $conn->close();
    ?>
</div>

<h2 style="color: green;">✅ تم الانتهاء من التحقق</h2>
<p>هذه الصفحة ستعطيك كل المعلومات عن المشكلة والحل</p>