<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('CREATE DATABASE IF NOT EXISTS db_appkeuangan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
    echo "Database db_appkeuangan berhasil dibuat / sudah siap!\n";
} catch (Exception $e) {
    echo "Error DB: " . $e->getMessage() . "\n";
}
