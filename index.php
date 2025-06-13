<?php
require 'config.php'; 

// 1. Mengambil 6 berita terbaru untuk slideshow "trending"
$trending_news_query = mysqli_query($koneksi, "SELECT judul, slug, isi, gambar FROM berita ORDER BY tanggal_publikasi DESC LIMIT 6");
$trending_news = [];
if ($trending_news_query) {
    while ($row = mysqli_fetch_assoc($trending_news_query)) {
        $trending_news[] = $row;
    }
}

// 2. Daftar kategori 
$categories = [
    ['name' => 'Teknologi','image' => 'images/teknologi.jpg','link' => 'list-berita.php?kategori=teknologi'],
    ['name' => 'Olahraga','image' => 'images/olahraga.jpg','link' => 'list-berita.php?kategori=olahraga'],
    ['name' => 'Internasional','image' => 'images/internasional.jpg','link' => 'list-berita.php?kategori=internasional'],
    ['name' => 'Hiburan','image' => 'images/hiburan.jpg','link' => 'list-berita.php?kategori=hiburan'],
    ['name' => 'Sains','image' => 'images/sains.jpg','link' => 'list-berita.php?kategori=sains'],
    ['name' => 'Bisnis','image' => 'images/bisnis.jpg','link' => 'list-berita.php?kategori=bisnis'],
];

$page_title = "Bento News - Portal Berita Terkini";
require 'header.php'; 
?>

<title><?= $page_title ?></title>

<section class="slideshow-container">
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            <?php foreach ($trending_news as $news) : ?>
            <div class="swiper-slide" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.4)), url('/berita-ps5/<?= htmlspecialchars($news['gambar']); ?>');">
                <div class="slide-content">
                    <h2 class="slide-title"><?= htmlspecialchars($news['judul']); ?></h2>
                    <p class="slide-excerpt"><?= substr(strip_tags($news['isi']), 0, 150); ?>...</p>
                    <a href="/berita-ps5/berita/<?= htmlspecialchars($news['slug']); ?>" class="btn btn-primary">Baca Selengkapnya</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>
</section>

<section class="category-hero">
    <div class="container">
        <h1 class="category-hero-title">Jelajahi Berita</h1>
        <p class="category-hero-subtitle">Pilih kategori yang ingin Anda lihat</p>
    </div>
</section>

<section class="category-grid-section">
    <div class="container">
        <div class="category-grid">
            <?php foreach ($categories as $category) : ?>
                <a href="/berita-ps5/<?= htmlspecialchars($category['link']); ?>" class="category-card" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('<?= htmlspecialchars($category['image']); ?>');">
                    <h3 class="category-name"><?= htmlspecialchars($category['name']); ?></h3>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper(".mySwiper", {
        spaceBetween: 30,
        centeredSlides: true,
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
</script>

<?php require 'footer.php'; ?>