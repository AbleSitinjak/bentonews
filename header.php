<?php require_once 'config.php'; // Sebaiknya gunakan require_once ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    
    <link rel="stylesheet" href="/berita-ps5/style.css">
</head>
<body>

    <header class="main-header">
        <div class="container header-container">
            <a href="/berita-ps5/" class="logo">
                <img src="/berita-ps5/icon.png" alt="Bento News Logo" class="logo-icon">
                <span>BENTO NEWS</span>
            </a>
            
            <form action="/berita-ps5/search.php" method="GET" class="search-form">
                <input type="text" name="q" class="search-input" placeholder="Cari berita..." required>
                <button type="submit" class="search-button">Cari</button>
            </form>
        </div>
    </header>

    <main>