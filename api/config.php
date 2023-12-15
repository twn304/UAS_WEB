<?php
$host = 'localhost';
$dbname = 'ckt';
$user = 'root';
$password = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Koneksi berhasil";
} catch (PDOException $e) {
    //echo "Koneksi gagal: " . $e->getMessage();
}
?>
