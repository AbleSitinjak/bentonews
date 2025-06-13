<?php
require 'config.php';

if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    header("Location: /berita-ps5/");
    exit;
}
$slug = $_GET['slug'];

$stmt = $koneksi->prepare("SELECT id, judul, kategori, isi, gambar, tanggal_publikasi FROM berita WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();
$berita = $result->fetch_assoc();

if (!$berita) {
    http_response_code(404);
    echo "<h1>404 - Berita tidak ditemukan</h1>";
    echo "<a href='/berita-ps5/'>Kembali ke Halaman Utama</a>";
    exit;
}

$related_news = [];
$current_id = $berita['id'];
$current_category = $berita['kategori'];

$related_stmt = $koneksi->prepare("SELECT judul, slug, gambar FROM berita WHERE kategori = ? AND id != ? ORDER BY tanggal_publikasi DESC LIMIT 4");
$related_stmt->bind_param("si", $current_category, $current_id);
$related_stmt->execute();
$related_result = $related_stmt->get_result();
while ($row = $related_result->fetch_assoc()) {
    $related_news[] = $row;
}
$stmt->close();
$related_stmt->close();

$page_title = htmlspecialchars($berita['judul']);
require 'header.php';
?>
<title><?= $page_title ?> - Bento News</title>

<div class="container article-container">
    <a href="javascript:history.back()" class="back-link">&laquo; Kembali</a>
    <article>
        <div class="article-header">
            <h1 class="article-title"><?= htmlspecialchars($berita['judul']); ?></h1>
            <p class="article-meta">
                Kategori: <a href="/berita-ps5/list-berita.php?kategori=<?= urlencode(strtolower($berita['kategori'])) ?>"><?= htmlspecialchars($berita['kategori']); ?></a> | Dipublikasikan pada <?= date('d F Y', strtotime($berita['tanggal_publikasi'])); ?>
            </p>
        </div>
        <img src="/berita-ps5/<?= htmlspecialchars($berita['gambar']); ?>" alt="<?= htmlspecialchars($berita['judul']); ?>" class="article-image">
        <div class="article-content">
            <?= nl2br(htmlspecialchars($berita['isi'])); ?>
        </div>
    </article>

    <?php if (!empty($related_news)): ?>
    <section class="related-news">
        <h2 class="related-title">Berita Terkait Lainnya</h2>
        <div class="related-grid">
            <?php foreach($related_news as $related_item): ?>
            <a href="/berita-ps5/berita/<?= htmlspecialchars($related_item['slug']) ?>" class="related-card">
                <img src="/berita-ps5/<?= htmlspecialchars($related_item['gambar']) ?>" alt="<?= htmlspecialchars($related_item['judul']) ?>" class="related-card-image">
                <div class="related-card-content">
                    <h3 class="related-card-title"><?= htmlspecialchars($related_item['judul']) ?></h3>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<?php require 'footer.php'; ?>