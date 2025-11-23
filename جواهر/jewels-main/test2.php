<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$username = "root"; 
$password = "";
$dbname = "jawaherest";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}
$conn->set_charset("utf8");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض البيانات بعد الإصلاح</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f5f5f5; }
        .section { background: white; padding: 20px; margin: 20px 0; border-radius: 10px; }
        .image-box { text-align: center; margin: 15px 0; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
    </style>
</head>
<body>
    <h1>🎯 عرض البيانات بعد الإصلاح</h1>

    <div class="section">
        <h2>من نحن</h2>
        <?php
        $result = $conn->query("SELECT * FROM about LIMIT 1");
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            ?>
            
            <div class="image-box">
                <?php
                $image_path = $row['image'];
                echo "<p><strong>مسار الصورة في قاعدة البيانات:</strong> $image_path</p>";
                
                // البحث عن المسار الصحيح
                $actual_path = '';
                $possible_paths = [
                    $image_path,
                    'uploads/' . $image_path,
                    'img/' . $image_path,
                    basename($image_path),
                    'uploads/' . basename($image_path)
                ];
                
                foreach ($possible_paths as $path) {
                    if (file_exists($path)) {
                        $actual_path = $path;
                        break;
                    }
                }
                
                if (!empty($actual_path) && file_exists($actual_path)) {
                    echo "<p class='success'>✅ الصورة موجودة في: $actual_path</p>";
                    echo "<img src='$actual_path' style='max-width: 400px; border: 3px solid green; border-radius: 10px;'>";
                } else {
                    echo "<p class='error'>❌ الصورة غير موجودة في أي مسار</p>";
                    echo "<p class='warning'>⚠️ جاري استخدام صورة افتراضية</p>";
                    echo "<img src='https://via.placeholder.com/400x300/4a77bf/ffffff?text=جواهر' style='max-width: 400px; border: 2px dashed #ccc;'>";
                }
                ?>
            </div>
            
            <h3><?php echo $row['title']; ?></h3>
            <p><?php echo nl2br($row['descrip']); ?></p>
            
        <?php } else { ?>
            <p class="error">❌ لا توجد بيانات في جدول about</p>
        <?php } ?>
    </div>

    <div class="section">
        <h2>شركائنا</h2>
        <?php
        $result = $conn->query("SELECT * FROM partnerships");
        if ($result && $result->num_rows > 0) {
            echo '<div style="display: flex; gap: 20px; flex-wrap: wrap;">';
            while ($row = $result->fetch_assoc()) {
                ?>
                <div style="border: 1px solid #ddd; padding: 15px; border-radius: 10px; text-align: center;">
                    <?php
                    $image_path = $row['image'];
                    echo "<p><strong>مسار:</strong> $image_path</p>";
                    
                    // البحث عن المسار الصحيح
                    $actual_path = '';
                    $possible_paths = [
                        $image_path,
                        'uploads/' . $image_path,
                        'img/' . $image_path,
                        basename($image_path),
                        'uploads/' . basename($image_path)
                    ];
                    
                    foreach ($possible_paths as $path) {
                        if (file_exists($path)) {
                            $actual_path = $path;
                            break;
                        }
                    }
                    
                    if (!empty($actual_path) && file_exists($actual_path)) {
                        echo "<p class='success'>✅ موجودة</p>";
                        echo "<img src='$actual_path' style='width: 150px; height: 150px; object-fit: cover; border-radius: 10px; border: 2px solid green;'>";
                    } else {
                        echo "<p class='error'>❌ غير موجودة</p>";
                        echo "<img src='https://via.placeholder.com/150/cccccc/666666?text=شريك' style='width: 150px; height: 150px; border-radius: 10px; border: 2px dashed #ccc;'>";
                    }
                    ?>
                    <h4><?php echo $row['title']; ?></h4>
                </div>
                <?php
            }
            echo '</div>';
        } else {
            echo '<p class="warning">⚠️ لا توجد شراكات في قاعدة البيانات</p>';
        }
        ?>
    </div>

    <div class="section">
        <h2>🛠️ إجراءات سريعة</h2>
        <p><a href="fix_data.php" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">إصلاح البيانات تلقائياً</a></p>
        <p><a href="upload_test_image.php" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">رفع صورة تجريبية</a></p>
    </div>

</body>
</html>
<?php $conn->close(); ?>