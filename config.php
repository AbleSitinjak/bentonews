<?php
// Pengaturan Waktu
date_default_timezone_set('Asia/Jakarta');

// Pengaturan Database
$db_host = 'localhost';
$db_user = 'root'; // User default XAMPP
$db_pass = '';     // Password default XAMPP kosong
$db_name = 'db_berita_ps5';

// Membuat Koneksi
$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Cek Koneksi
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

/**
 * Fungsi ini tidak kita gunakan untuk insert karena slug sudah ada di database,
 * tapi ini adalah contoh fungsi untuk membuat slug otomatis dari judul.
 * Bisa digunakan jika Anda ingin membuat halaman admin untuk menambah berita.
 */
function createSlug($text) {
    // Ganti spasi dengan strip
    $text = str_replace(' ', '-', $text);
    // Hapus karakter yang tidak diizinkan
    $text = preg_replace('/[^A-Za-z0-9\-]/', '', $text);
    // Ubah menjadi huruf kecil
    $text = strtolower($text);
    // Hapus strip berlebih
    $text = preg_replace('/-+/', '-', $text);
    return $text;
}
?>