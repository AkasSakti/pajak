<?php
session_start();

if(isset($_POST['login'])) {
    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verifikasi username dan password (contoh sederhana, dapat diganti dengan metode autentikasi yang lebih aman)
    if($username === 'akas' && $password === 'akas') {
        $_SESSION['user'] = $username;
        header("Location: formval.php"); // Redirect ke halaman dashboard jika login berhasil
        exit();
    } else {
        echo "Username atau password salah";
    }
}
