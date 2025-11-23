<?php
// config.php - إصدار مبسط وآمن

// منع التحميل المزدوج
if (!defined('CONFIG_LOADED')) {
    define('CONFIG_LOADED', true);
    
    // إعدادات قاعدة البيانات
    $host = "localhost";
    $username = "root"; 
    $password = "";
    $dbname = "jawaherest";

    // الاتصال بقاعدة البيانات
    $conn = new mysqli($host, $username, $password, $dbname);
    
    // التحقق من الاتصال
    if ($conn->connect_error) {
        // لا تستخدم die() هنا، فقط سجل الخطأ
        error_log("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
        $conn = null;
    } else {
        $conn->set_charset("utf8");
    }
}
?>