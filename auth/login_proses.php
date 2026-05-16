<?php
session_start();
include "../config/koneksi.php";
$login = $_POST['login'];
$password = md5($_POST['password']);

$query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE (username='$login' OR email='$login') AND password='$password'" );

$data = mysqli_fetch_assoc($query);
if ($data) {
    $_SESSION['login'] = true;
    $_SESSION['id'] = $data['id'];
    $_SESSION['role'] = $data['role'];
    $_SESSION['username'] = $data['username'];
    if ($data['role'] == 'super_admin') {
        header("Location:../admin/dashboard.php");
    } elseif (
        $data['role'] == 'dosen') {
        header("Location:../dosen/dashboard.php");
    } elseif (
        $data['role'] == 'mahasiswa') {
        header("Location:../mahasiswa/dashboard.php");
    }
} else {
    echo "<script> 
        alert('Login gagal');
        window.location.href = '../login.php';
    </script>";
}
