<style>
/* ─── HEADER SET TIỆC ─── */
.set-header-sec {
    padding: 130px 0 70px;
    background: var(--forest-dk);
    color: #fff;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.set-header-sec::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 50% 60% at 30% 50%, rgba(212,168,67,0.12) 0%, transparent 65%),
        radial-gradient(ellipse 50% 50% at 70% 60%, rgba(196,96,60,0.1) 0%, transparent 60%);
}

/* ─── SET CARDS ─── */
.set-user-card {
    background: #fff;
    border: 1px solid var(--stone);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.35s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.set-user-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 50px rgba(44, 74, 53, 0.12);
    border-color: var(--terra);
}
.set-card-top {
    background: linear-gradient(135deg, var(--forest) 0%, #3b6b4d 100%);
    padding: 28px 24px 22px;
    color: #fff;
    position: relative;
}
.set-card-top::after {
    content: '';
    position: absolute;
    right: -20px; bottom: -20px;
    width: 100px; height: 100px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
}
.set-card-top .set-number {
    font-size: 0.65rem;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: var(--amber-lt);
    font-weight: 600;
    margin-bottom: 6px;
}
.set-card-top h3 {
    font-family: 'DM Serif Display', serif;
    font-size: 1.5rem;
    margin: 0;
    line-height: 1.2;
}
.set-card-top .set-price-tag {
    display: inline-block;
    margin-top: 10px;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 700;
}
.set-card-top .set-price-tag .amount {
    font-family: 'DM Serif Display', serif;
    font-size: 1.3rem;
    color: var(--amber-lt);
}

.set-card-body {
    padding: 24px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}
.set-info-row {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 0;
    border-bottom: 1px solid #F1F5F9;
    font-size: 0.88rem;
}
.set-info-row:last-child { border-bottom: none; }
.set-info-row i {
    width: 24px;
    text-align: center;
    color: var(--terra);
    font-size: 1rem;
}

/* Danh sách món preview */
.mon-preview {
    margin-top: 16px;
    flex-grow: 1;
}
.mon-preview-title {
    font-size: 0.72rem;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--muted);
    font-weight: 700;
    margin-bottom: 10px;
}
.mon-preview-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 0;
    font-size: 0.84rem;
    border-bottom: 1px dashed #F1F5F9;
}
.mon-preview-item:last-child { border-bottom: none; }
.mon-preview-item .mon-name-u {
    color: var(--charcoal);
    font-weight: 500;
}
.mon-preview-item .mon-nhom {
    font-size: 0.7rem;
    color: var(--muted);
    background: #F8FAFC;
    padding: 2px 8px;
    border-radius: 10px;
}
.mon-more {
    text-align: center;
    padding: 8px;
    font-size: 0.78rem;
    color: var(--terra);
    font-weight: 600;
}

.set-card-footer {
    padding: 16px 24px 24px;
    display: flex;
    gap: 10px;
}
.btn-set-detail {
    flex: 1;
    background: var(--forest);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 12px;
    font-size: 0.82rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.btn-set-detail:hover {
    background: var(--terra);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(196,96,60,0.3);
}
.btn-set-book {
    flex: 1;
    background: transparent;
    color: var(--forest);
    border: 1.5px solid var(--forest);
    border-radius: 8px;
    padding: 12px;
    font-size: 0.82rem;
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.btn-set-book:hover {
    background: var(--forest);
    color: #fff;
}

/* ─── CTA BANNER ─── */
.cta-custom-set {
    background: linear-gradient(135deg, var(--forest), var(--forest-dk));
    color: #fff;
    border-radius: 16px;
    overflow: hidden;
    position: relative;
}
.cta-custom-set::before {
    content: '';
    position: absolute;
    right: -60px; top: -60px;
    width: 250px; height: 250px;
    border-radius: 50%;
    background: rgba(212,168,67,0.08);
}

/* Comparison bar */
.compare-bar {
    background: #fff;
    border: 1px solid var(--stone);
    border-radius: 12px;
    padding: 20px 28px;
}
</style>

<!-- ═══ HEADER ═══ -->
<section class="set-header-sec">
    <div class="container position-relative" style="z-index: 2;">
        <span class="d-inline-block text-uppercase small fw-semibold" style="letter-spacing: 3px; color: var(--amber-lt);">
            <i class="bi bi-stars me-1"></i> Thực Đơn Trọn Gói
        </span>
        <h1 class="mt-2 mb-3" style="font-family: 'DM Serif Display', serif; font-size: clamp(2.2rem, 4vw, 3.2rem);">
            Set Tiệc – <em style="color: var(--amber-lt); font-style: italic;">Trọn Vẹn Cho Mọi Dịp</em>
        </h1>
        <p class="text-white-50 mx-auto mb-4" style="max-width: 640px; font-size: 0.95rem; line-height: 1.8;">
            Lựa chọn set tiệc phù hợp với số lượng khách và ngân sách, mỗi set đều được bếp trưởng QHT lên thực đơn cân đối giữa khai vị, món chính, và tráng miệng.
        </p>

        <!-- Thanh so sánh nhanh -->
        <div class="d-flex justify-content-center flex-wrap gap-3 mt-4">
            <?php foreach ($setsWithMon as $idx => $s): ?>
                <div class="d-inline-flex align-items-center gap-2 px-3 py-2" style="background: rgba(255,255,255,0.08); border-radius: 8px; backdrop-filter: blur(10px);">
                    <span class="fw-bold" style="color: var(--amber-lt);"><?= htmlspecialchars($s['ten']) ?></span>
                    <span class="text-white-50 small"><?= number_format($s['gia'], 0, ',', '.') ?>đ / <?= $s['so_nguoi_phu_hop'] ?> người</span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ═══ DANH SÁCH SET TIỆC ═══ -->
<section class="py-5" style="background: var(--cream);">
    <div class="container">

        <?php if (empty($setsWithMon)): ?>
            <div class="text-center py-5">
                <i class="bi bi-box-seam" style="font-size: 4rem; color: var(--stone);"></i>
                <h4 class="mt-3" style="font-family: 'DM Serif Display', serif; color: var(--muted);">Chưa có Set Tiệc nào</h4>
                <p class="text-muted">Chúng tôi đang cập nhật thực đơn set tiệc. Vui lòng quay lại sau.</p>
            </div>
        <?php else: ?>

            <div class="row g-4">
                <?php foreach ($setsWithMon as $idx => $s): ?>
                    <div class="col-lg-6 col-xl-<?= count($setsWithMon) <= 3 ? '4' : '6' ?>">
                        <div class="set-user-card">
                            <!-- TOP: Tên + Giá -->
                            <div class="set-card-top">
                                <div class="set-number">Set #<?= str_pad($s['id'], 2, '0', STR_PAD_LEFT) ?></div>
                                <h3><?= htmlspecialchars($s['ten']) ?></h3>
                                <div class="set-price-tag">
                                    <span class="amount"><?= number_format($s['gia'], 0, ',', '.') ?>đ</span>
                                    <span style="opacity: 0.7;"> / người</span>
                                </div>
                            </div>

                            <!-- BODY: Thông tin + Preview món -->
                            <div class="set-card-body">
                                <!-- Thông tin -->
                                <div class="set-info-row">
                                    <i class="bi bi-people-fill"></i>
                                    <span>Phù hợp <strong><?= $s['so_nguoi_phu_hop'] ?></strong> người</span>
                                </div>
                                <div class="set-info-row">
                                    <i class="bi bi-egg-fried"></i>
                                    <span><strong><?= $s['so_mon'] ?></strong> món ăn trong set</span>
                                </div>
                                <?php if (!empty($s['mo_ta'])): ?>
                                    <div class="set-info-row">
                                        <i class="bi bi-info-circle"></i>
                                        <span class="text-muted"><?= htmlspecialchars($s['mo_ta']) ?></span>
                                    </div>
                                <?php endif; ?>

                                <!-- Preview món ăn -->
                                <div class="mon-preview">
                                    <div class="mon-preview-title">
                                        <i class="bi bi-list-ul me-1"></i> Danh sách món
                                    </div>
                                    <?php
                                    $previewMon = array_slice($s['mon_an'], 0, 5);
                                    $remaining  = count($s['mon_an']) - 5;
                                    ?>
                                    <?php foreach ($previewMon as $m): ?>
                                        <div class="mon-preview-item">
                                            <span class="mon-name-u">
                                                <i class="bi bi-check2 text-success me-1" style="font-size: 0.8rem;"></i>
                                                <?= htmlspecialchars($m['ten']) ?>
                                            </span>
                                            <span class="mon-nhom"><?= htmlspecialchars($m['nhom_mon']) ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php if ($remaining > 0): ?>
                                        <div class="mon-more">
                                            <i class="bi bi-plus-circle me-1"></i> và <?= $remaining ?> món khác...
                                        </div>
                                    <?php endif; ?>
                                    <?php if (empty($s['mon_an'])): ?>
                                        <p class="text-muted small text-center py-2 mb-0">
                                            <i class="bi bi-clock me-1"></i>Đang cập nhật danh sách món
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- FOOTER: Buttons -->
                            <div class="set-card-footer">
                                <a href="<?= BASE_URL ?>?action=chi-tiet-set-tiec&id=<?= $s['id'] ?>" class="btn-set-detail">
                                    <i class="bi bi-eye"></i> Xem Chi Tiết
                                </a>
                                <a href="<?= BASE_URL ?>#dat-ban" class="btn-set-book">
                                    <i class="bi bi-calendar2-check"></i> Đặt Bàn
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- ═══ BẢNG SO SÁNH ═══ -->
            <div class="compare-bar mt-5">
                <h5 class="text-center mb-4" style="font-family: 'DM Serif Display', serif; color: var(--forest);">
                    <i class="bi bi-arrow-left-right me-2"></i>So Sánh Nhanh Các Set Tiệc
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover text-center align-middle mb-0">
                        <thead>
                            <tr style="background: var(--cream-dk);">
                                <th class="text-start" style="width: 30%;">Tên Set</th>
                                <th>Giá / người</th>
                                <th>Số người</th>
                                <th>Số món</th>
                                <th>Tổng ước tính</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($setsWithMon as $s): ?>
                                <tr>
                                    <td class="text-start">
                                        <strong style="color: var(--forest);"><?= htmlspecialchars($s['ten']) ?></strong>
                                        <?php if (!empty($s['mo_ta'])): ?>
                                            <div class="text-muted small"><?= htmlspecialchars(mb_strimwidth($s['mo_ta'], 0, 50, '...')) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="fw-bold" style="color: var(--terra); font-size: 1.05rem;">
                                            <?= number_format($s['gia'], 0, ',', '.') ?>đ
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success px-3 py-2" style="border-radius: 8px;">
                                            <i class="bi bi-people me-1"></i><?= $s['so_nguoi_phu_hop'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary px-3 py-2" style="border-radius: 8px;">
                                            <i class="bi bi-egg-fried me-1"></i><?= $s['so_mon'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong>~<?= number_format($s['gia'] * $s['so_nguoi_phu_hop'], 0, ',', '.') ?>đ</strong>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>?action=chi-tiet-set-tiec&id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-dark" style="border-radius: 6px; font-size: 0.78rem;">
                                            Chi tiết <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php endif; ?>

        <!-- ═══ CTA BANNER ═══ -->
        <div class="cta-custom-set mt-5 p-4 p-md-5">
            <div class="row align-items-center position-relative" style="z-index: 2;">
                <div class="col-lg-7">
                    <span class="d-inline-block small fw-semibold text-uppercase mb-2" style="letter-spacing: 3px; color: var(--amber-lt);">
                        Tùy chỉnh theo ý bạn
                    </span>
                    <h3 style="font-family: 'DM Serif Display', serif; font-size: 1.8rem; line-height: 1.3;">
                        Không tìm thấy set phù hợp?<br>
                        <em style="color: var(--amber-lt);">Hãy để chúng tôi thiết kế cho bạn!</em>
                    </h3>
                    <p class="text-white-50 mt-3 mb-0" style="max-width: 500px; line-height: 1.8;">
                        QHT Banquet hỗ trợ tùy chỉnh thực đơn theo yêu cầu riêng: thay đổi món ăn, điều chỉnh khẩu phần, hoặc tạo set tiệc mới hoàn toàn theo sở thích và ngân sách của bạn.
                    </p>
                </div>
                <div class="col-lg-5 text-lg-end mt-4 mt-lg-0">
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-lg-end">
                        <a href="<?= BASE_URL ?>#dat-ban" class="btn btn-light px-4 py-3 fw-bold text-uppercase" style="letter-spacing: 1px; color: var(--forest); border-radius: 8px; font-size: 0.85rem;">
                            <i class="bi bi-calendar-check me-2"></i> Đặt Bàn Ngay
                        </a>
                        <a href="tel:0123456789" class="btn btn-outline-light px-4 py-3 fw-semibold" style="border-radius: 8px; font-size: 0.85rem;">
                            <i class="bi bi-telephone me-2"></i> 0123 456 789
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
