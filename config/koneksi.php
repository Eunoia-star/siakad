<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "siakad_db"
);

if(!$conn){
    die("Koneksi gagal");
}
?>