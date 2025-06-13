<?php
$page_title = "Hasil Pencarian"; 
require 'header.php'; 

$search_results = [];
$jumlahData = 0;
$search_query = '';
$jumlahHalaman = 0;
$halamanAktif = 1;

if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $search_query = trim($_GET['q']);
    $page_title = "Hasil Pencarian untuk: '" . htmlspecialchars($search_query) . "'";

    $search_term = "%" . $search_query . "%";

    $jumlahDataPerHalaman = 6;
    $count_stmt = $koneksi->prepare("SELECT COUNT(*) as total FROM berita WHERE judul LIKE ? OR isi LIKE ?");
    $count_stmt->bind_param("ss", $search_term, $search_term);
    $count_stmt->execute();
    $result = $count_stmt->get_result();
    $row = $result->fetch_assoc();
    $jumlahData = $row['total'];

    if ($jumlahData > 0) {
        $jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
        $halamanAktif = (isset($_GET["halaman"])) ? (int)$_GET["halaman"] : 1;
        $awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

        $search_stmt = $koneksi->prepare("SELECT * FROM berita WHERE judul LIKE ? OR isi LIKE ? ORDER BY tanggal_publikasi DESC LIMIT ?, ?");
        $search_stmt->bind_param("ssii", $search_term, $search_term, $awalData, $jumlahDataPerHalaman);
        $search_stmt->execute();
        $search_results = $search_stmt->get_result();
    }
}
?>

<title><?= $page_title ?> - Bento News</title>

<section class="page-header">
    <div class="container">
        <h1 class="page-title">
            <?php if (!empty($search_query)): ?>
                Hasil Pencarian untuk: <span>"<?= htmlspecialchars($search_query); ?>"</span>
            <?php else: ?>
                Silakan Masukkan Kata Kunci Pencarian
            <?php endif; ?>
        </h1>
    </div>
</section>

<section class="news-grid-section">
    <div class="container">
        <?php if ($jumlahData > 0): ?>
            <p class="search-info">Menemukan <?= $jumlahData ?> berita yang cocok.</p>
            <div class="news-grid">
                <?php while ($row = mysqli_fetch_assoc($search_results)) : ?>
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
                <?php if($halamanAktif > 1) : ?>
                    <a href="?q=<?= urlencode($search_query) ?>&halaman=<?= $halamanAktif - 1; ?>" class="page-link">&laquo; Sebelumnya</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                    <a href="?q=<?= urlencode($search_query) ?>&halaman=<?= $i; ?>" class="page-link <?= ($i == $halamanAktif) ? 'active' : ''; ?>"><?= $i; ?></a>
                <?php endfor; ?>

                <?php if($halamanAktif < $jumlahHalaman) : ?>
                    <a href="?q=<?= urlencode($search_query) ?>&halaman=<?= $halamanAktif + 1; ?>" class="page-link">Selanjutnya &raquo;</a>
                <?php endif; ?>
            </nav>
        <?php elseif (!empty($search_query)): ?>
            <p class="no-news-found">Tidak ada berita yang cocok dengan kata kunci "<?= htmlspecialchars($search_query); ?>". Coba kata kunci lain.</p>
        <?php endif; ?>
    </div>
</section>

<?php require 'footer.php'; ?>