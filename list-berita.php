<?php
require 'config.php';

if (!isset($_GET['kategori']) || empty($_GET['kategori'])) {
    header("Location: /berita-ps5/");
    exit;
}

$kategori_slug = $_GET['kategori'];
$kategori_nama = ucwords(str_replace('-', ' ', $kategori_slug));

$page_title = "Kategori: " . htmlspecialchars($kategori_nama);
require 'header.php';

$jumlahDataPerHalaman = 6; 
$count_query = mysqli_prepare($koneksi, "SELECT COUNT(*) as total FROM berita WHERE kategori = ?");
mysqli_stmt_bind_param($count_query, "s", $kategori_nama);
mysqli_stmt_execute($count_query);
$result = mysqli_stmt_get_result($count_query);
$row = mysqli_fetch_assoc($result);
$jumlahData = $row['total'];

if ($jumlahData == 0) {
    $berita_result = [];
    $jumlahHalaman = 0;
} else {
    $jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
    $halamanAktif = (isset($_GET["halaman"])) ? (int)$_GET["halaman"] : 1;
    $awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

    $query_berita = mysqli_prepare($koneksi, "SELECT id, judul, slug, isi, gambar, tanggal_publikasi FROM berita WHERE kategori = ? ORDER BY tanggal_publikasi DESC LIMIT ?, ?");
    mysqli_stmt_bind_param($query_berita, "sii", $kategori_nama, $awalData, $jumlahDataPerHalaman);
    mysqli_stmt_execute($query_berita);
    $berita_result = mysqli_stmt_get_result($query_berita);
}
?>

<title><?= $page_title ?> - Bento News</title>

<section class="page-header">
    <div class="container">
        <h1 class="page-title">Kategori: <span><?= htmlspecialchars($kategori_nama); ?></span></h1>
    </div>
</section>

<section class="news-grid-section">
    <div class="container">
        <?php if ($jumlahData > 0): ?>
            <div class="news-grid">
                <?php while ($row = mysqli_fetch_assoc($berita_result)) : ?>
                <div class="news-card">
                    <a href="/berita-ps5/berita/<?= htmlspecialchars($row['slug']); ?>" class="card-link">
                        <div class="card-image-container">
                            <img src="/berita-ps5/<?= htmlspecialchars($row['gambar']); ?>" alt="<?= htmlspecialchars($row['judul']); ?>">
                        </div>
                        <div class="card-content">
                            <h3 class="card-title"><?= htmlspecialchars($row['judul']); ?></h3>
                            <p class="card-excerpt"><?= substr(strip_tags($row['isi']), 0, 90); ?>...</p>
                        </div>
                    </a>
                </div>
                <?php endwhile; ?>
            </div>

            <nav class="pagination">
                <?php if ($halamanAktif > 1) : ?>
                    <a href="?kategori=<?= urlencode($kategori_slug) ?>&halaman=<?= $halamanAktif - 1; ?>" class="page-link">&laquo; Sebelumnya</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                    <a href="?kategori=<?= urlencode($kategori_slug) ?>&halaman=<?= $i; ?>" class="page-link <?= ($i == $halamanAktif) ? 'active' : ''; ?>"><?= $i; ?></a>
                <?php endfor; ?>
                <?php if ($halamanAktif < $jumlahHalaman) : ?>
                    <a href="?kategori=<?= urlencode($kategori_slug) ?>&halaman=<?= $halamanAktif + 1; ?>" class="page-link">Selanjutnya &raquo;</a>
                <?php endif; ?>
            </nav>
        <?php else: ?>
            <p class="no-news-found">Belum ada berita dalam kategori "<?= htmlspecialchars($kategori_nama); ?>".</p>
        <?php endif; ?>
    </div>
</section>

<?php require 'footer.php'; ?>