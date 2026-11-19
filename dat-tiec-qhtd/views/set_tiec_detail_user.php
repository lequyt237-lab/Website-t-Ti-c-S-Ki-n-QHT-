<?php
/**
 * @var array $set           Thông tin set tiệc
 * @var array $monTrongSet   Danh sách món ăn trong set
 * @var array $otherSets     Các set khác (gợi ý)
 */

// Nhóm món theo nhom_mon
$grouped = [];
foreach ($monTrongSet as $m) {
    $grouped[$m['nhom_mon']][] = $m;
}
$totalGocMon = array_sum(array_column($monTrongSet, 'gia'));
?>

<style>
/* ─── HERO CHI TIẾT ─── */
.set-detail-hero {
    padding: 130px 0 70px;
    background: var(--forest-dk);
    color: #fff;
    position: relative;
    overflow: hidden;
}
.set-detail-hero::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 50% 70% at 70% 40%, rgba(196,96,60,0.12) 0%, transparent 65%),
        radial-gradient(ellipse 40% 50% at 20% 70%, rgba(44,74,53,0.5) 0%, transparent 60%);
}
.hero-breadcrumb a {
    color: rgba(255,255,255,0.5);
    text-decoration: none;
    font-size: 0.82rem;
    transition: color 0.3s;
}
.hero-breadcrumb a:hover { color: var(--amber-lt); }
.hero-breadcrumb span { color: rgba(255,255,255,0.3); }
.hero-breadcrumb .current { color: var(--amber-lt); font-weight: 600; }

.hero-price-box {
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 16px;
    padding: 28px;
    text-align: center;
}
.hero-price-box .price-main {
    font-family: 'DM Serif Display', serif;
    font-size: 2.6rem;
    color: var(--amber-lt);
    line-height: 1;
}
.hero-price-box .price-unit {
    font-size: 0.85rem;
    color: rgba(255,255,255,0.5);
    margin-top: 4px;
}

.info-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    padding: 8px 18px;
    border-radius: 24px;
    font-size: 0.85rem;
    font-weight: 500;
}
.info-pill i { color: var(--amber-lt); }

/* ─── NHÓM MÓN ─── */
.nhom-section {
    margin-bottom: 32px;
}
.nhom-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--stone);
}
.nhom-header .nhom-icon {
    width: 40px; height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    color: #fff;
}
.nhom-header h4 {
    font-family: 'DM Serif Display', serif;
    font-size: 1.25rem;
    color: var(--charcoal);
    margin: 0;
}
.nhom-header .count-badge {
    font-size: 0.72rem;
    padding: 3px 10px;
    border-radius: 12px;
    font-weight: 600;
}

.mon-detail-card {
    background: #fff;
    border: 1px solid #F1F5F9;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s;
    height: 100%;
}
.mon-detail-card:hover {
    border-color: var(--terra);
    box-shadow: 0 8px 24px rgba(44,74,53,0.08);
    transform: translateY(-3px);
}
.mon-detail-card .mon-name-d {
    font-family: 'DM Serif Display', serif;
    font-size: 1.1rem;
    color: var(--charcoal);
    margin-bottom: 6px;
}
.mon-detail-card .mon-desc {
    font-size: 0.82rem;
    color: var(--muted);
    line-height: 1.6;
}
.mon-detail-card .mon-gia {
    font-family: 'DM Serif Display', serif;
    font-size: 1.15rem;
    color: var(--terra);
}

/* ─── GỢI Ý ─── */
.suggest-card {
    background: #fff;
    border: 1px solid var(--stone);
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s;
    text-decoration: none;
    color: inherit;
    display: block;
}
.suggest-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 30px rgba(44,74,53,0.1);
    border-color: var(--terra);
    color: inherit;
}

/* Sticky summary */
.sticky-summary {
    position: sticky;
    top: 100px;
}
</style>

<!-- ═══ HERO ═══ -->
<section class="set-detail-hero">
    <div class="container position-relative" style="z-index: 2;">
        <!-- Breadcrumb -->
        <div class="hero-breadcrumb mb-3">
            <a href="<?= BASE_URL ?>">Trang chủ</a>
            <span class="mx-2">/</span>
            <a href="<?= BASE_URL ?>?action=set-tiec">Set Tiệc</a>
            <span class="mx-2">/</span>
            <span class="current"><?= htmlspecialchars($set['ten']) ?></span>
        </div>

        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="d-inline-block text-uppercase small fw-semibold mb-2" style="letter-spacing: 3px; color: var(--amber-lt);">
                    Set #<?= str_pad($set['id'], 2, '0', STR_PAD_LEFT) ?>
                </span>
                <h1 class="mb-3" style="font-family: 'DM Serif Display', serif; font-size: clamp(2rem, 4vw, 3rem);">
                    <?= htmlspecialchars($set['ten']) ?>
                </h1>
                <?php if (!empty($set['mo_ta'])): ?>
                    <p class="text-white-50 mb-4" style="font-size: 1rem; line-height: 1.8; max-width: 500px;">
                        <?= htmlspecialchars($set['mo_ta']) ?>
                    </p>
                <?php endif; ?>

                <div class="d-flex flex-wrap gap-3">
                    <span class="info-pill"><i class="bi bi-people-fill"></i> <?= $set['so_nguoi_phu_hop'] ?> người</span>
                    <span class="info-pill"><i class="bi bi-egg-fried"></i> <?= count($monTrongSet) ?> món</span>
                    <span class="info-pill"><i class="bi bi-tags"></i> <?= count($grouped) ?> nhóm</span>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="hero-price-box">
                    <div class="price-main"><?= number_format($set['gia'], 0, ',', '.') ?>đ</div>
                    <div class="price-unit">mỗi người</div>
                    <hr style="border-color: rgba(255,255,255,0.1); margin: 16px 0;">
                    <div class="d-flex justify-content-between small" style="color: rgba(255,255,255,0.6);">
                        <span>Tổng cho <?= $set['so_nguoi_phu_hop'] ?> người:</span>
                        <strong style="color: var(--amber-lt);"><?= number_format($set['gia'] * $set['so_nguoi_phu_hop'], 0, ',', '.') ?>đ</strong>
                    </div>
                    <a href="<?= BASE_URL ?>#dat-ban" class="btn btn-light w-100 mt-3 py-3 fw-bold text-uppercase" style="letter-spacing: 1px; color: var(--forest); border-radius: 8px;">
                        <i class="bi bi-calendar2-check me-2"></i> Đặt Bàn Với Set Này
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ NỘI DUNG CHI TIẾT ═══ -->
<section class="py-5" style="background: var(--cream);">
    <div class="container">
        <div class="row g-4">

            <!-- CỘT TRÁI: Danh sách món ăn -->
            <div class="col-lg-8">
                <h3 class="mb-4" style="font-family: 'DM Serif Display', serif; color: var(--forest);">
                    <i class="bi bi-list-stars me-2"></i>Thực Đơn Chi Tiết
                </h3>

                <?php if (empty($monTrongSet)): ?>
                    <div class="text-center py-5" style="background: #fff; border-radius: 14px; border: 1px solid var(--stone);">
                        <i class="bi bi-clock-history" style="font-size: 3rem; color: var(--stone);"></i>
                        <h5 class="mt-3" style="font-family: 'DM Serif Display', serif; color: var(--muted);">Đang cập nhật</h5>
                        <p class="text-muted small">Danh sách món ăn cho set này đang được bếp trưởng hoàn thiện.</p>
                    </div>
                <?php else: ?>
                    <?php
                    $nhomColors = ['#2C4A35', '#C4603C', '#D4A843', '#1E508C', '#7C3AED', '#059669'];
                    $nhomIcons  = ['bi-cup-hot-fill', 'bi-fire', 'bi-star-fill', 'bi-flower1', 'bi-cake2-fill', 'bi-egg-fried'];
                    $ci = 0;
                    ?>
                    <?php foreach ($grouped as $nhom => $mons): ?>
                        <div class="nhom-section">
                            <div class="nhom-header">
                                <div class="nhom-icon" style="background: <?= $nhomColors[$ci % count($nhomColors)] ?>;">
                                    <i class="bi <?= $nhomIcons[$ci % count($nhomIcons)] ?>"></i>
                                </div>
                                <h4><?= htmlspecialchars($nhom) ?></h4>
                                <span class="count-badge bg-light text-secondary border"><?= count($mons) ?> món</span>
                            </div>
                            <div class="row g-3">
                                <?php foreach ($mons as $m): ?>
                                    <div class="col-md-6">
                                        <div class="mon-detail-card">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h5 class="mon-name-d"><?= htmlspecialchars($m['ten']) ?></h5>
                                                <span class="mon-gia"><?= number_format($m['gia'], 0, ',', '.') ?>đ</span>
                                            </div>
                                            <?php if (!empty($m['mo_ta'])): ?>
                                                <p class="mon-desc mb-0 mt-1"><?= htmlspecialchars($m['mo_ta']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php $ci++; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- CỘT PHẢI: Sticky Summary + Gợi ý -->
            <div class="col-lg-4">
                <div class="sticky-summary">
                    <!-- Tóm tắt -->
                    <div class="p-4 mb-4" style="background: #fff; border: 1px solid var(--stone); border-radius: 14px;">
                        <h5 class="fw-bold mb-3" style="font-family: 'DM Serif Display', serif; color: var(--forest);">
                            <i class="bi bi-receipt me-2"></i>Tóm Tắt Set
                        </h5>
                        <div class="d-flex justify-content-between py-2 border-bottom small">
                            <span class="text-muted">Tên set:</span>
                            <strong><?= htmlspecialchars($set['ten']) ?></strong>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom small">
                            <span class="text-muted">Số món:</span>
                            <strong><?= count($monTrongSet) ?> món</strong>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom small">
                            <span class="text-muted">Số nhóm món:</span>
                            <strong><?= count($grouped) ?> nhóm</strong>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom small">
                            <span class="text-muted">Phù hợp:</span>
                            <strong><?= $set['so_nguoi_phu_hop'] ?> người</strong>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom small">
                            <span class="text-muted">Tổng giá gốc các món:</span>
                            <span class="text-decoration-line-through text-muted"><?= number_format($totalGocMon, 0, ',', '.') ?>đ</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 small">
                            <span class="fw-bold" style="color: var(--forest);">Giá set / người:</span>
                            <strong style="color: var(--terra); font-size: 1.15rem;"><?= number_format($set['gia'], 0, ',', '.') ?>đ</strong>
                        </div>

                        <?php if ($totalGocMon > $set['gia']): ?>
                            <div class="mt-2 p-2 text-center" style="background: #FEF3C7; border-radius: 8px;">
                                <small class="fw-bold" style="color: #92400E;">
                                    <i class="bi bi-tag-fill me-1"></i>Tiết kiệm <?= number_format($totalGocMon - $set['gia'], 0, ',', '.') ?>đ / người so với gọi lẻ
                                </small>
                            </div>
                        <?php endif; ?>

                        <a href="<?= BASE_URL ?>#dat-ban" class="btn w-100 mt-3 py-3 fw-bold text-uppercase" style="background: var(--forest); color: #fff; letter-spacing: 1px; border-radius: 8px;">
                            <i class="bi bi-calendar2-check me-2"></i> Đặt Bàn Ngay
                        </a>
                        <a href="tel:0123456789" class="btn btn-outline-secondary w-100 mt-2 py-2 fw-semibold" style="border-radius: 8px;">
                            <i class="bi bi-telephone me-2"></i> Hotline: 0123 456 789
                        </a>
                    </div>

                    <!-- Gợi ý set khác -->
                    <?php if (!empty($otherSets)): ?>
                        <div class="p-4" style="background: #fff; border: 1px solid var(--stone); border-radius: 14px;">
                            <h6 class="fw-bold mb-3" style="color: var(--forest);">
                                <i class="bi bi-stars me-2 text-warning"></i>Set Tiệc Khác
                            </h6>
                            <?php foreach (array_slice($otherSets, 0, 3) as $os): ?>
                                <a href="<?= BASE_URL ?>?action=chi-tiet-set-tiec&id=<?= $os['id'] ?>" class="suggest-card mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold small" style="color: var(--charcoal);"><?= htmlspecialchars($os['ten']) ?></div>
                                            <div class="text-muted" style="font-size: 0.75rem;">
                                                <i class="bi bi-people me-1"></i><?= $os['so_nguoi_phu_hop'] ?> người
                                                · <?= $os['so_mon'] ?> món
                                            </div>
                                        </div>
                                        <span class="fw-bold" style="color: var(--terra); font-size: 0.95rem;">
                                            <?= number_format($os['gia'], 0, ',', '.') ?>đ
                                        </span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                            <a href="<?= BASE_URL ?>?action=set-tiec" class="btn btn-sm btn-outline-dark w-100 mt-1" style="border-radius: 6px;">
                                Xem tất cả Set Tiệc <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</section>
