<?php
$host = "localhost";        // Veritabanı sunucusu
$user = "root";             // Varsayılan kullanıcı
$password = "1234";             // Eğer root kullanıcısına şifre verdiysen buraya yaz
$database = "hospital";     // Veritabanı adın (phpMyAdmin’de görünen)

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Bağlantı hatası: " . mysqli_connect_error());
}
?>
