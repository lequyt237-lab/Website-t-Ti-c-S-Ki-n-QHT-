<style>
/* ─── HERO ─── */
.hero {
    min-height: 100vh;
    background: var(--forest-dk);
    display: flex; align-items: center;
    padding-top: 90px; position: relative; overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 55% 70% at 70% 50%, rgba(196,96,60,0.12) 0%, transparent 65%),
        radial-gradient(ellipse 40% 50% at 20% 80%, rgba(44,74,53,0.6) 0%, transparent 60%);
}
.hero-tag {
    display: inline-flex; align-items: center; gap: 10px;
    font-size: 0.72rem; letter-spacing: 3.5px; text-transform: uppercase;
    color: var(--amber); font-weight: 500; margin-bottom: 20px;
}
.hero-tag::before {
    content: ''; width: 32px; height: 1px; background: var(--amber);
}
.hero h1 {
    font-size: clamp(2.6rem, 5vw, 4.2rem);
    color: var(--white); line-height: 1.12; margin-bottom: 22px;
}
.hero h1 em {
    font-style: italic; color: var(--amber-lt); display: block;
}
.hero-desc {
    font-size: 0.95rem; color: rgba(255,255,255,0.6);
    line-height: 1.95; max-width: 460px; margin-bottom: 38px;
}
.btn-primary-q {
    display: inline-block; background: var(--terra);
    color: var(--white); border-radius: 3px;
    padding: 14px 34px; text-decoration: none;
    font-size: 0.78rem; font-weight: 600; letter-spacing: 2px;
    text-transform: uppercase; transition: all 0.3s;
    border: none;
}
.btn-primary-q:hover {
    background: var(--terra-lt); color: var(--white);
    transform: translateY(-2px); box-shadow: 0 8px 24px rgba(196,96,60,0.35);
}
.btn-ghost-q {
    display: inline-block; background: transparent;
    color: rgba(255,255,255,0.75);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 3px; padding: 13px 34px; text-decoration: none;
    font-size: 0.78rem; font-weight: 500; letter-spacing: 2px;
    text-transform: uppercase; transition: all 0.3s;
}
.btn-ghost-q:hover {
    border-color: var(--amber); color: var(--amber-lt);
}
.hero-stats {
    display: flex; gap: 40px; margin-top: 52px;
    padding-top: 32px; border-top: 1px solid rgba(255,255,255,0.08);
}
.stat-num {
    font-family: 'DM Serif Display', serif;
    font-size: 2.3rem; color: var(--amber-lt); line-height: 1;
}
.stat-lbl {
    font-size: 0.7rem; letter-spacing: 2px; text-transform: uppercase;
    color: rgba(255,255,255,0.35); margin-top: 4px;
}
/* Hero visual panel */
.hero-panel {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px; padding: 32px;
    backdrop-filter: blur(10px);
}
.hero-panel-title {
    font-size: 0.7rem; letter-spacing: 3px; text-transform: uppercase;
    color: var(--amber); margin-bottom: 20px;
}
.mini-occasion {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 16px; border-radius: 8px;
    background: rgba(255,255,255,0.04); margin-bottom: 10px;
    border: 1px solid rgba(255,255,255,0.05);
    transition: all 0.3s; cursor: pointer;
}
.mini-occasion:hover { background: rgba(196,96,60,0.12); border-color: rgba(196,96,60,0.3); }
.mini-occ-icon {
    width: 42px; height: 42px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.moi-1 { background: rgba(194,24,91,0.2); color: #F48FB1; }
.moi-2 { background: rgba(230,81,0,0.2); color: #FFCC80; }
.moi-3 { background: rgba(44,74,53,0.4); color: #A5D6A7; }
.moi-4 { background: rgba(21,101,192,0.2); color: #90CAF9; }
.mini-occ-name { font-size: 0.88rem; color: rgba(255,255,255,0.8); font-weight: 500; }
.mini-occ-sub { font-size: 0.72rem; color: rgba(255,255,255,0.35); }
.mini-occ-arrow { margin-left: auto; color: rgba(255,255,255,0.2); font-size: 0.8rem; }

/* ─── SECTION ─── */
.sec-eyebrow {
    font-size: 0.7rem; letter-spacing: 3.5px; text-transform: uppercase;
    color: var(--terra); font-weight: 600; margin-bottom: 10px;
    display: flex; align-items: center; gap: 10px;
}
.sec-eyebrow::before { content: ''; width: 24px; height: 2px; background: var(--terra); border-radius: 2px; }
.sec-title { font-size: clamp(1.9rem, 3vw, 2.6rem); color: var(--charcoal); line-height: 1.2; }
.sec-title.lt { color: var(--white); }
.sec-title em { font-style: italic; color: var(--terra); }
.sec-title.lt em { color: var(--amber-lt); }
.sec-body { color: var(--muted); font-size: 0.9rem; line-height: 1.9; }

/* ─── FEATURES ─── */
.feat-sec { padding: 100px 0; background: var(--white); }
.feat-card {
    padding: 36px 28px; border-radius: 10px; height: 100%;
    border: 1.5px solid var(--stone); background: var(--white);
    transition: all 0.35s;
}
.feat-card:hover {
    border-color: var(--terra);
    box-shadow: 0 16px 40px rgba(44,74,53,0.07);
    transform: translateY(-5px);
}
.feat-icon {
    width: 52px; height: 52px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; margin-bottom: 18px;
}
.fi-1 { background: rgba(44,74,53,0.09); color: var(--forest); }
.fi-2 { background: rgba(196,96,60,0.1); color: var(--terra); }
.fi-3 { background: rgba(212,168,67,0.12); color: #A07820; }
.fi-4 { background: rgba(21,101,192,0.09); color: #1565C0; }
.feat-card h5 { font-size: 1.25rem; font-weight: 400; margin-bottom: 10px; color: var(--charcoal); }
.feat-card p { font-size: 0.87rem; color: var(--muted); line-height: 1.8; }

/* ─── OCCASIONS ─── */
.occ-sec { padding: 100px 0; background: var(--cream-dk); }
.occ-card {
    border-radius: 10px; overflow: hidden;
    height: 300px; cursor: pointer; position: relative;
}
.occ-card-bg {
    width: 100%; height: 100%;
    display: flex; align-items: flex-end; padding: 28px;
    transition: transform 0.5s ease;
}
.occ-card:hover .occ-card-bg { transform: scale(1.04); }
.occ-bg-1 { background: linear-gradient(to top, rgba(26,48,36,0.92) 0%, rgba(26,48,36,0.3) 55%), linear-gradient(135deg, #C2185B 0%, #E91E63 100%); }
.occ-bg-2 { background: linear-gradient(to top, rgba(26,48,36,0.92) 0%, rgba(26,48,36,0.3) 55%), linear-gradient(135deg, #BF360C 0%, #E64A19 100%); }
.occ-bg-3 { background: linear-gradient(to top, rgba(26,48,36,0.92) 0%, rgba(26,48,36,0.3) 55%), linear-gradient(135deg, #2E7D32 0%, #388E3C 100%); }
.occ-bg-4 { background: linear-gradient(to top, rgba(26,48,36,0.92) 0%, rgba(26,48,36,0.3) 55%), linear-gradient(135deg, #1565C0 0%, #1976D2 100%); }
.occ-tag {
    position: absolute; top: 18px; left: 18px;
    background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2);
    color: rgba(255,255,255,0.85); border-radius: 20px;
    padding: 4px 14px; font-size: 0.7rem; letter-spacing: 1.5px; text-transform: uppercase;
}
.occ-icon-big { font-size: 1.8rem; color: rgba(255,255,255,0.7); margin-bottom: 8px; display: block; }
.occ-card h5 { color: var(--white); font-size: 1.5rem; margin-bottom: 4px; }
.occ-card p { color: rgba(255,255,255,0.65); font-size: 0.83rem; }
.occ-arrow {
    margin-left: auto; color: rgba(255,255,255,0.4);
    font-size: 1.1rem; align-self: flex-end;
}

/* ─── PROCESS ─── */
.proc-sec { padding: 100px 0; background: var(--forest); }
.proc-card {
    padding: 36px 24px; text-align: center;
    border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.07);
    background: rgba(255,255,255,0.03);
    height: 100%; transition: all 0.35s;
}
.proc-card:hover {
    background: rgba(255,255,255,0.06);
    border-color: rgba(212,168,67,0.3);
}
.proc-num {
    font-family: 'DM Serif Display', serif;
    font-size: 3rem; color: rgba(212,168,67,0.2); line-height: 1; margin-bottom: -8px;
}
.proc-ico {
    width: 54px; height: 54px; border-radius: 50%;
    border: 1px solid rgba(212,168,67,0.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; color: var(--amber-lt);
    margin: 0 auto 16px;
}
.proc-card h5 { color: var(--white); font-size: 1.15rem; font-weight: 400; margin-bottom: 10px; }
.proc-card p { color: rgba(255,255,255,0.45); font-size: 0.84rem; line-height: 1.8; }
.proc-arrow { color: rgba(212,168,67,0.25); font-size: 1.3rem; padding-top: 60px; }

/* ─── CTA ─── */
.cta-sec {
    padding: 100px 0; background: var(--cream);
    text-align: center;
}
.cta-sec h2 { font-size: clamp(2rem, 4vw, 3rem); color: var(--charcoal); margin-bottom: 14px; }
.cta-sec h2 em { font-style: italic; color: var(--forest); }
.cta-sec p { color: var(--muted); font-size: 0.92rem; margin-bottom: 36px; }
.btn-cta {
    display: inline-block; background: var(--forest);
    color: var(--white); border-radius: 3px;
    padding: 15px 42px; text-decoration: none;
    font-size: 0.78rem; font-weight: 600;
    letter-spacing: 2px; text-transform: uppercase;
    transition: all 0.3s; margin-right: 12px;
}
.btn-cta:hover {
    background: var(--terra); color: var(--white);
    transform: translateY(-2px); box-shadow: 0 8px 24px rgba(44,74,53,0.2);
}
.btn-cta-out {
    display: inline-block; background: transparent;
    color: var(--forest);
    border: 1.5px solid var(--forest); border-radius: 3px;
    padding: 14px 42px; text-decoration: none;
    font-size: 0.78rem; font-weight: 600;
    letter-spacing: 2px; text-transform: uppercase;
    transition: all 0.3s;
}
.btn-cta-out:hover { background: var(--forest); color: var(--white); }
.hotline {
    font-family: 'DM Serif Display', serif;
    font-size: 1.9rem; color: var(--terra);
    text-decoration: none; display: block; margin-top: 22px;
}
.hotline:hover { color: var(--forest); }
</style>

<!-- ═══ HERO ═══ -->
<section class="hero">
    <div class="container position-relative" style="z-index:2">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow mb-4" role="alert" style="border-radius: 8px;">
                <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i>
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow mb-4" role="alert" style="border-radius: 8px;">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i>
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <div class="row align-items-center g-5">

            <!-- Left -->
            <div class="col-lg-6">
                <div class="hero-tag">Nhà Hàng Tiệc Gia Đình</div>
                <h1>
                    Mỗi khoảnh khắc
                    <em>đáng được trân trọng</em>
                </h1>
                <p class="hero-desc">
                    QHT Banquet mang đến không gian ấm cúng, thực đơn phong phú và dịch vụ
                    tận tâm cho sinh nhật, mừng thọ, họp mặt gia đình hay tiệc công ty.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#dat-ban" class="btn-primary-q">
                        <i class="bi bi-calendar2-check me-2"></i>Đặt Bàn Ngay
                    </a>
                    <a href="<?= BASE_URL ?>?action=thuc-don" class="btn-ghost-q">
                        Xem Thực Đơn
                    </a>
                    <a href="<?= isset($_SESSION['user']) ? '#lich-su-dat-ban' : BASE_URL . '?action=lich-su-dat-ban' ?>" class="btn-ghost-q" style="border-color: rgba(212,168,67,0.4); color: var(--amber-lt);">
                        <i class="bi bi-clock-history me-1"></i>Lịch Sử Đặt Bàn
                    </a>
                </div>
                <div class="hero-stats">
                    <div>
                        <div class="stat-num">500+</div>
                        <div class="stat-lbl">Bữa tiệc</div>
                    </div>
                    <div>
                        <div class="stat-num">50+</div>
                        <div class="stat-lbl">Món ăn</div>
                    </div>
                    <div>
                        <div class="stat-num">20+</div>
                        <div class="stat-lbl">Bàn &amp; Phòng</div>
                    </div>
                </div>
            </div>

            <!-- Right panel -->
            <div class="col-lg-5 offset-lg-1 d-none d-lg-block">
                <div class="hero-panel">
                    <div class="hero-panel-title">Chọn loại tiệc</div>
                    <div class="mini-occasion">
                        <div class="mini-occ-icon moi-1"><i class="bi bi-cake2"></i></div>
                        <div>
                            <div class="mini-occ-name">Tiệc Sinh Nhật</div>
                            <div class="mini-occ-sub">Không gian ấm cúng & bánh kem</div>
                        </div>
                        <div class="mini-occ-arrow"><i class="bi bi-chevron-right"></i></div>
                    </div>
                    <div class="mini-occasion">
                        <div class="mini-occ-icon moi-2"><i class="bi bi-heart"></i></div>
                        <div>
                            <div class="mini-occ-name">Tiệc Mừng Thọ</div>
                            <div class="mini-occ-sub">Tri ân ông bà, cha mẹ</div>
                        </div>
                        <div class="mini-occ-arrow"><i class="bi bi-chevron-right"></i></div>
                    </div>
                    <div class="mini-occasion">
                        <div class="mini-occ-icon moi-3"><i class="bi bi-people"></i></div>
                        <div>
                            <div class="mini-occ-name">Họp Mặt Gia Đình</div>
                            <div class="mini-occ-sub">Sum vầy trong không gian riêng</div>
                        </div>
                        <div class="mini-occ-arrow"><i class="bi bi-chevron-right"></i></div>
                    </div>
                    <div class="mini-occasion">
                        <div class="mini-occ-icon moi-4"><i class="bi bi-building"></i></div>
                        <div>
                            <div class="mini-occ-name">Tiệc Công Ty</div>
                            <div class="mini-occ-sub">Liên hoan, gặp mặt đối tác</div>
                        </div>
                        <div class="mini-occ-arrow"><i class="bi bi-chevron-right"></i></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ═══ LỊCH SỬ ĐẶT BÀN (DÀNH CHO KHÁCH HÀNG) ═══ -->
<section class="home-history-sec py-5" id="lich-su-dat-ban" style="background: var(--cream-dk); border-bottom: 1px solid var(--stone);">
    <div class="container">
        
        <?php if (isset($_SESSION['user'])): ?>
            <!-- KHÁCH ĐÃ ĐĂNG NHẬP: HIỂN THỊ ĐƠN ĐẶT BÀN GẦN ĐÂY -->
            <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
                <div>
                    <div class="sec-eyebrow">
                        <i class="bi bi-clock-history me-1 text-danger"></i>Lịch Sử Của Bạn
                    </div>
                    <h2 class="sec-title mb-1">Đơn Đặt Bàn <em>Gần Đây</em></h2>
                    <p class="sec-body mb-0">
                        Xin chào <strong><?= htmlspecialchars($_SESSION['user']['ho_ten']) ?></strong>! Dưới đây là các đơn tiệc gần nhất của bạn tại QHT Banquet.
                    </p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= BASE_URL ?>?action=lich-su-dat-ban" class="btn btn-outline-dark px-3 py-2 fw-semibold" style="border-radius: 4px; font-size: 0.85rem;">
                        <i class="bi bi-collection me-1"></i> Xem tất cả lịch sử (<?= $bookingStats['total'] ?? 0 ?>)
                    </a>
                    <a href="<?= BASE_URL ?>?action=dat-ban" class="btn-primary-q py-2 px-3" style="font-size: 0.8rem;">
                        <i class="bi bi-calendar2-plus me-1"></i> Đặt Bàn Mới
                    </a>
                </div>
            </div>

            <!-- Thống kê nhanh trạng thái -->
            <div class="d-flex flex-wrap gap-2 mb-4">
                <span class="badge bg-white text-dark border px-3 py-2 fw-semibold shadow-sm">
                    <i class="bi bi-journal-check text-primary me-1"></i> Tổng đơn: <strong><?= $bookingStats['total'] ?? 0 ?></strong>
                </span>
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2 fw-semibold shadow-sm">
                    <i class="bi bi-hourglass-split me-1 text-warning"></i> Chờ duyệt: <strong><?= $bookingStats['cho_duyet'] ?? 0 ?></strong>
                </span>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fw-semibold shadow-sm">
                    <i class="bi bi-check-circle-fill me-1 text-success"></i> Đã xác nhận: <strong><?= $bookingStats['da_duyet'] ?? 0 ?></strong>
                </span>
                <?php if (($bookingStats['da_huy'] ?? 0) > 0 || ($bookingStats['tu_choi'] ?? 0) > 0): ?>
                    <span class="badge bg-secondary-subtle text-secondary border px-3 py-2 fw-semibold shadow-sm">
                        <i class="bi bi-slash-circle me-1"></i> Đã hủy / Từ chối: <strong><?= ($bookingStats['da_huy'] ?? 0) + ($bookingStats['tu_choi'] ?? 0) ?></strong>
                    </span>
                <?php endif; ?>
            </div>

            <?php if (empty($userBookings)): ?>
                <!-- Trạng thái chưa có đơn -->
                <div class="card border-0 shadow-sm p-4 p-md-5 text-center" style="border-radius: 12px; background: #ffffff;">
                    <div class="text-muted mb-3">
                        <i class="bi bi-calendar-event" style="font-size: 3rem; color: var(--amber);"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Bạn chưa có đơn đặt bàn nào gần đây</h5>
                    <p class="text-muted small mb-4" style="max-width: 480px; margin: 0 auto;">
                        Hãy khám phá các set tiệc gia đình thượng hạng và không gian phòng riêng ấm cúng tại QHT Banquet ngay hôm nay!
                    </p>
                    <div>
                        <a href="<?= BASE_URL ?>?action=dat-ban" class="btn-primary-q">
                            <i class="bi bi-calendar2-check me-1"></i> Đặt Bàn Ngay
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Lưới danh sách đơn đặt bàn -->
                <div class="row g-4">
                    <?php foreach ($userBookings as $b): ?>
                        <?php
                        $statusMap = [
                            'cho_duyet' => ['badge' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle', 'text' => 'Chờ duyệt', 'icon' => 'bi-hourglass-split'],
                            'da_duyet'  => ['badge' => 'bg-success-subtle text-success border border-success-subtle', 'text' => 'Đã duyệt', 'icon' => 'bi-check-circle-fill'],
                            'tu_choi'   => ['badge' => 'bg-danger-subtle text-danger border border-danger-subtle', 'text' => 'Từ chối', 'icon' => 'bi-x-circle-fill'],
                            'da_huy'    => ['badge' => 'bg-secondary-subtle text-secondary border border-secondary-subtle', 'text' => 'Đã hủy', 'icon' => 'bi-slash-circle'],
                        ];
                        $st = $statusMap[$b['trang_thai']] ?? ['badge' => 'bg-secondary text-white', 'text' => $b['trang_thai'], 'icon' => 'bi-info-circle'];
                        ?>
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm h-100 p-4" style="border-radius: 12px; background: #ffffff; transition: transform 0.25s, box-shadow 0.25s;">
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold text-secondary">#DB<?= str_pad($b['id'], 4, '0', STR_PAD_LEFT) ?></span>
                                        <span class="badge <?= $st['badge'] ?> px-2 py-1 fw-semibold">
                                            <i class="bi <?= $st['icon'] ?> me-1"></i><?= $st['text'] ?>
                                        </span>
                                    </div>
                                    <span class="text-muted small">
                                        <i class="bi bi-clock me-1"></i><?= date('d/m/Y H:i', strtotime($b['ngay_tao'])) ?>
                                    </span>
                                </div>

                                <div class="d-flex align-items-start justify-content-between mb-3 pb-3 border-bottom">
                                    <div>
                                        <h5 class="fw-bold text-dark mb-1">
                                            <i class="bi bi-<?= ($b['loai_ban'] ?? '') === 'phong_rieng' ? 'star-fill text-warning' : 'circle-fill text-success' ?> me-1" style="font-size: 0.85rem;"></i>
                                            <?= htmlspecialchars($b['ten_ban'] ?? 'Bàn sảnh') ?>
                                        </h5>
                                        <span class="badge <?= ($b['loai_ban'] ?? '') === 'phong_rieng' ? 'bg-warning-subtle text-warning-emphasis' : 'bg-light text-secondary border' ?> small">
                                            <?= ($b['loai_ban'] ?? '') === 'phong_rieng' ? 'Phòng VIP riêng tư' : 'Bàn sảnh chung' ?>
                                        </span>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fw-semibold">
                                            <i class="bi bi-balloon-heart me-1"></i><?= htmlspecialchars($b['ten_dip'] ?? 'Dịp tiệc') ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="row g-2 small text-secondary mb-3">
                                    <div class="col-sm-6">
                                        <div><i class="bi bi-calendar3 text-danger me-1"></i> <strong>Ngày:</strong> <?= date('d/m/Y', strtotime($b['ngay_dat'])) ?></div>
                                        <div>
                                            <i class="bi bi-sun text-warning me-1"></i> <strong>Ca:</strong> 
                                            <?= $b['ca'] === 'trua' ? 'Trưa (11:00-14:00)' : 'Tối (17:00-21:30)' ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div><i class="bi bi-people text-info me-1"></i> <strong>Khách:</strong> <?= $b['so_nguoi'] ?> người</div>
                                        <div>
                                            <i class="bi bi-journals text-primary me-1"></i> <strong>Thực đơn:</strong> 
                                            <?= !empty($b['ten_set']) ? htmlspecialchars($b['ten_set']) : 'Gọi món lẻ' ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                                    <div>
                                        <span class="text-muted small">Tổng tiền:</span>
                                        <span class="fw-bold text-danger fs-5 ms-1"><?= number_format($b['tong_tien'], 0, ',', '.') ?> đ</span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <?php if ($b['trang_thai'] === 'cho_duyet'): ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger px-3 fw-semibold" onclick="promptCancelBooking(<?= $b['id'] ?>)">
                                                <i class="bi bi-x-circle me-1"></i>Hủy bàn
                                            </button>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-dark px-3 fw-semibold" onclick="viewBookingDetail(<?= $b['id'] ?>)">
                                            <i class="bi bi-eye me-1"></i>Chi tiết
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="text-center mt-4">
                    <a href="<?= BASE_URL ?>?action=lich-su-dat-ban" class="btn btn-outline-dark px-4 py-2 fw-semibold">
                        <i class="bi bi-clock-history me-1"></i> Xem Toàn Bộ Lịch Sử Đặt Bàn Của Bạn <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- KHÁCH CHƯA ĐĂNG NHẬP: KHUNG TRA CỨU NHANH TRÊN TRANG CHỦ -->
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <div class="sec-eyebrow">
                        <i class="bi bi-search me-1 text-danger"></i>Tra Cứu Trực Tuyến
                    </div>
                    <h2 class="sec-title mb-2">Xem Lịch Sử <em>Đặt Bàn</em></h2>
                    <p class="sec-body mb-4">
                        Quý khách đã gửi yêu cầu đặt tiệc tại QHT Banquet? Nhập số điện thoại đặt bàn hoặc mã đơn để kiểm tra nhanh trạng thái phê duyệt và thông tin thực đơn.
                    </p>

                    <form action="<?= BASE_URL ?>" method="GET" class="d-flex flex-sm-row flex-column gap-2 mb-3">
                        <input type="hidden" name="action" value="lich-su-dat-ban">
                        <div class="input-group input-group-lg shadow-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-phone"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Nhập số điện thoại hoặc mã đơn..." required>
                        </div>
                        <button type="submit" class="btn-primary-q text-nowrap py-3 px-4">
                            <i class="bi bi-search me-1"></i> Tra Cứu
                        </button>
                    </form>
                    <div class="small text-muted">
                        <i class="bi bi-shield-check text-success me-1"></i> Thông tin tra cứu được bảo mật và hiển thị nhanh chóng.
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="p-4 rounded-3 border bg-white shadow-sm text-center">
                        <div class="p-3 d-inline-block rounded-circle bg-warning-subtle text-warning mb-3">
                            <i class="bi bi-person-circle fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Tài Khoản QHT Banquet</h5>
                        <p class="text-muted small mb-3">
                            Đăng nhập tài khoản để tự động lưu trữ, theo dõi và quản lý mọi đơn đặt tiệc của gia đình bạn một cách trọn vẹn nhất.
                        </p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="<?= BASE_URL ?>?action=dang-nhap" class="btn btn-warning fw-bold px-3 py-2">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Đăng Nhập
                            </a>
                            <a href="<?= BASE_URL ?>?action=dang-ky" class="btn btn-outline-secondary px-3 py-2">
                                Đăng Ký Mới
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- ═══ FEATURES ═══ -->
<section class="feat-sec">
    <div class="container">
        <div class="row mb-5 align-items-end">
            <div class="col-lg-6">
                <div class="sec-eyebrow">Dịch Vụ</div>
                <h2 class="sec-title">Tại sao chọn<br><em>QHT Banquet?</em></h2>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <p class="sec-body mt-3">Chúng tôi chú trọng từng chi tiết nhỏ nhất — từ món ăn, không gian đến phong cách phục vụ — để bữa tiệc của bạn thực sự đáng nhớ.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="feat-card">
                    <div class="feat-icon fi-1"><i class="bi bi-book-half"></i></div>
                    <h5>Thực đơn đa dạng</h5>
                    <p>Hơn 50 món ăn từ nguyên liệu tươi ngon, bếp trưởng lành nghề chế biến tinh tế</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feat-card">
                    <div class="feat-icon fi-2"><i class="bi bi-gift"></i></div>
                    <h5>Set tiệc trọn gói</h5>
                    <p>Các set tiệc thiết kế sẵn theo số người, tiết kiệm thời gian và tối ưu chi phí</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feat-card">
                    <div class="feat-icon fi-3"><i class="bi bi-door-open"></i></div>
                    <h5>Phòng riêng tư</h5>
                    <p>Không gian riêng sang trọng, trang trí theo yêu cầu cho từng loại dịp</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feat-card">
                    <div class="feat-icon fi-4"><i class="bi bi-phone"></i></div>
                    <h5>Đặt bàn tiện lợi</h5>
                    <p>Chọn ngày, bàn, set tiệc ngay trên web — nhanh chóng chỉ trong vài phút</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ OCCASIONS ═══ -->
<section class="occ-sec">
    <div class="container">
        <div class="text-center mb-5">
            <div class="sec-eyebrow justify-content-center">Loại Dịp</div>
            <h2 class="sec-title">Phục vụ <em>mọi dịp</em> đặc biệt</h2>
        </div>
        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <div class="occ-card">
                    <span class="occ-tag">Sinh Nhật</span>
                    <div class="occ-card-bg occ-bg-1">
                        <div>
                            <i class="bi bi-cake2 occ-icon-big"></i>
                            <h5>Tiệc Sinh Nhật</h5>
                            <p>Kỷ niệm ngày đặc biệt cùng người thân</p>
                        </div>
                        <div class="occ-arrow"><i class="bi bi-arrow-up-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="occ-card">
                    <span class="occ-tag">Mừng Thọ</span>
                    <div class="occ-card-bg occ-bg-2">
                        <div>
                            <i class="bi bi-heart occ-icon-big"></i>
                            <h5>Tiệc Mừng Thọ</h5>
                            <p>Tri ân công đức ông bà, cha mẹ</p>
                        </div>
                        <div class="occ-arrow"><i class="bi bi-arrow-up-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="occ-card">
                    <span class="occ-tag">Họp Mặt</span>
                    <div class="occ-card-bg occ-bg-3">
                        <div>
                            <i class="bi bi-people occ-icon-big"></i>
                            <h5>Họp Mặt Gia Đình</h5>
                            <p>Sum vầy trong không gian ấm áp</p>
                        </div>
                        <div class="occ-arrow"><i class="bi bi-arrow-up-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="occ-card">
                    <span class="occ-tag">Công Ty</span>
                    <div class="occ-card-bg occ-bg-4">
                        <div>
                            <i class="bi bi-building occ-icon-big"></i>
                            <h5>Tiệc Công Ty</h5>
                            <p>Liên hoan, gặp mặt đối tác</p>
                        </div>
                        <div class="occ-arrow"><i class="bi bi-arrow-up-right"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ BOOKING FORM SECTION ═══ -->
<section class="booking-sec py-5" id="dat-ban" style="background: var(--cream); border-top: 1px solid var(--stone); border-bottom: 1px solid var(--stone);">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="text-center mb-4">
                    <div class="sec-eyebrow justify-content-center">Đặt Bàn Trực Tuyến</div>
                    <h2 class="sec-title">Đặt Bàn &amp; Phòng Tiệc <em>Gia Đình</em></h2>
                    <p class="sec-body mx-auto mt-2" style="max-width: 620px;">
                        Chỉ vài thao tác đơn giản, nhà hàng sẽ chuẩn bị không gian ấm cúng, sang trọng và chu đáo nhất cho bữa tiệc của bạn.
                    </p>
                </div>

                <!-- Thông báo thành công -->
                <?php if (isset($_SESSION['booking_success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4 border-0" role="alert" style="border-radius: 10px; background: #e8f5e9; color: #1b5e20;">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-check-circle-fill fs-5 mt-1 text-success"></i>
                            <div><?= $_SESSION['booking_success']; unset($_SESSION['booking_success']); ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Thông báo lỗi nếu có -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4 border-0" role="alert" style="border-radius: 10px;">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-exclamation-triangle-fill fs-5 mt-1 text-danger"></i>
                            <div><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php 
                // Xử lý dữ liệu điền sẵn nếu đã đăng nhập hoặc nếu submit bị lỗi
                $oldBooking = $_SESSION['booking_old'] ?? [];
                unset($_SESSION['booking_old']);

                $isLoggedIn = isset($_SESSION['user']);
                $currentUser = $_SESSION['user'] ?? null;

                $defaultName  = $oldBooking['ho_ten'] ?? ($currentUser['ho_ten'] ?? '');
                $defaultPhone = $oldBooking['so_dien_thoai'] ?? ($currentUser['so_dien_thoai'] ?? '');
                $defaultEmail = $oldBooking['email'] ?? ($currentUser['email'] ?? '');
                ?>

                <!-- Thẻ Form Đặt Tiệc -->
                <div class="card border-0 shadow-sm" style="border-radius: 14px; background: #fff; border: 1.5px solid var(--stone) !important;">
                    <div class="card-body p-4 p-md-5">

                        <!-- Trạng thái đăng nhập -->
                        <?php if ($isLoggedIn): ?>
                            <div class="alert alert-light border d-flex align-items-center justify-content-between p-3 mb-4" style="border-radius: 8px; background: rgba(44, 74, 53, 0.04) !important; border-color: rgba(44,74,53,0.15) !important;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-person-check-fill text-success fs-5"></i>
                                    <span class="small">
                                        Đang đặt tiệc với tài khoản: <b><?= htmlspecialchars($currentUser['ho_ten']) ?></b> (Thông tin liên hệ đã được tự động điền)
                                    </span>
                                </div>
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Khách hàng thành viên</span>
                            </div>
                        <?php else: ?>
                            <div class="small text-muted mb-4 p-2 px-3 bg-light rounded-2 d-flex align-items-center gap-2">
                                <i class="bi bi-info-circle text-primary"></i>
                                <span>Bạn đã có tài khoản? <a href="<?= BASE_URL ?>?action=dang-nhap" class="fw-semibold text-decoration-none" style="color:var(--terra)">Đăng nhập ngay</a> để tự động điền thông tin và tích lũy ưu đãi.</span>
                            </div>
                        <?php endif; ?>

                        <form action="<?= BASE_URL ?>?action=xu-ly-dat-ban" method="POST" id="bookingForm">
                            <!-- Hàng 1: Thông tin người đặt -->
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold small text-uppercase text-secondary" style="letter-spacing: 1px;">
                                        <i class="bi bi-person-lines-fill me-1 text-primary"></i> 1. Thông Tin Người Đặt
                                    </h6>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" name="ho_ten" class="form-control" placeholder="Nguyễn Văn A" 
                                           value="<?= htmlspecialchars($defaultName) ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="tel" name="so_dien_thoai" class="form-control" placeholder="0912 345 678" 
                                           value="<?= htmlspecialchars($defaultPhone) ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Địa chỉ Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" placeholder="email@example.com" 
                                           value="<?= htmlspecialchars($defaultEmail) ?>" required>
                                </div>
                            </div>

                            <hr style="border-color: var(--stone); opacity: 0.6;">

                            <!-- Hàng 2: Chọn Vị trí & Loại dịp -->
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold small text-uppercase text-secondary" style="letter-spacing: 1px;">
                                        <i class="bi bi-geo-alt-fill me-1 text-danger"></i> 2. Chọn Không Gian &amp; Dịp Tiệc
                                    </h6>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Chọn Bàn hoặc Phòng riêng <span class="text-danger">*</span></label>
                                    <select name="banphong_id" id="select_banphong" class="form-select" required>
                                        <option value="">-- Chọn không gian phù hợp --</option>
                                        <?php if (!empty($roomsAndTables)): ?>
                                            <?php foreach ($roomsAndTables as $tbl): ?>
                                                <?php 
                                                $selected = ($oldBooking['banphong_id'] ?? 0) == $tbl['id'] ? 'selected' : '';
                                                $extraText = $tbl['loai'] === 'phong_rieng' 
                                                    ? ' [Phòng VIP - ' . $tbl['suc_chua'] . ' người' . ($tbl['phu_phi'] > 0 ? ' - Phụ phí: ' . number_format($tbl['phu_phi']) . 'đ' : '') . ']'
                                                    : ' [Bàn sảnh - ' . $tbl['suc_chua'] . ' người]';
                                                ?>
                                                <option value="<?= $tbl['id'] ?>" data-capacity="<?= $tbl['suc_chua'] ?>" data-fee="<?= $tbl['phu_phi'] ?>" <?= $selected ?>>
                                                    <?= htmlspecialchars($tbl['ten']) . $extraText ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="1">Bàn A1 [Bàn sảnh - 8 người]</option>
                                            <option value="4">Phòng Hoa Lan [Phòng VIP - 20 người - Phụ phí: 500,000đ]</option>
                                            <option value="5">Phòng Trúc Xanh [Phòng VIP - 30 người - Phụ phí: 800,000đ]</option>
                                        <?php endif; ?>
                                    </select>
                                    <div class="form-text small" id="table_hint">
                                        <i class="bi bi-info-circle me-1"></i> Phòng riêng phù hợp tiệc ấm cúng, có trang trí theo yêu cầu.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Mục đích dùng bữa / Loại dịp <span class="text-danger">*</span></label>
                                    <select name="loaidip_id" class="form-select" required>
                                        <option value="">-- Chọn dịp (Ăn uống bạn bè, gia đình, sự kiện...) --</option>
                                        <?php if (!empty($occasions)): ?>
                                            <?php foreach ($occasions as $occ): ?>
                                                <?php $selected = ($oldBooking['loaidip_id'] ?? 0) == $occ['id'] ? 'selected' : ''; ?>
                                                <option value="<?= $occ['id'] ?>" <?= $selected ?>>
                                                    <?= htmlspecialchars($occ['ten']) ?> (<?= htmlspecialchars($occ['mo_ta'] ?? '') ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="5">Gặp gỡ bạn bè (Tụ họp bạn bè, ăn uống thân mật dưới sảnh)</option>
                                            <option value="6">Bữa ăn thường ngày / Dịp khác (Đặt bàn dùng bữa thông thường)</option>
                                            <option value="1">Sinh nhật (Tiệc kỷ niệm ngày sinh)</option>
                                            <option value="2">Mừng thọ (Tiệc tri ân ông bà, cha mẹ)</option>
                                            <option value="3">Họp mặt gia đình (Tiệc sum vầy gia đình)</option>
                                            <option value="4">Tiệc công ty (Liên hoan, gặp mặt đối tác)</option>
                                        <?php endif; ?>
                                    </select>
                                    <div class="form-text small">
                                        <i class="bi bi-people me-1"></i> Đi ăn bạn bè 2–3 người dưới sảnh chọn <b>Gặp gỡ bạn bè</b> hoặc <b>Bữa ăn thường ngày</b>.
                                    </div>
                                </div>
                            </div>

                            <hr style="border-color: var(--stone); opacity: 0.6;">

                            <!-- Hàng 3: Thời gian & Số lượng khách -->
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold small text-uppercase text-secondary" style="letter-spacing: 1px;">
                                        <i class="bi bi-calendar3 me-1 text-success"></i> 3. Thời Gian &amp; Số Lượng Khách
                                    </h6>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Ngày đặt tiệc <span class="text-danger">*</span></label>
                                    <!-- CHẶN NGÀY TRONG QUÁ KHỨ BẰNG min=HÔM NAY -->
                                    <input type="date" name="ngay_dat" id="input_ngay_dat" class="form-control" 
                                           min="<?= date('Y-m-d') ?>" 
                                           value="<?= htmlspecialchars($oldBooking['ngay_dat'] ?? date('Y-m-d')) ?>" required>
                                    <div class="form-text small text-muted"><i class="bi bi-shield-check text-success"></i> Đã chặn ngày trong quá khứ</div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Khung giờ / Ca tiệc <span class="text-danger">*</span></label>
                                    <select name="ca" class="form-select" required>
                                        <option value="trua" <?= ($oldBooking['ca'] ?? '') === 'trua' ? 'selected' : '' ?>>
                                            Ca trưa (11:00 – 14:00)
                                        </option>
                                        <option value="toi" <?= ($oldBooking['ca'] ?? 'toi') === 'toi' ? 'selected' : '' ?>>
                                            Ca tối (17:00 – 21:30)
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Số lượng khách <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted"><i class="bi bi-people"></i></span>
                                        <input type="number" name="so_nguoi" id="input_so_nguoi" class="form-control" 
                                               min="1" max="100" 
                                               value="<?= htmlspecialchars($oldBooking['so_nguoi'] ?? '4') ?>" required>
                                        <span class="input-group-text bg-light text-muted">người</span>
                                    </div>
                                    <!-- Cảnh báo đoàn lớn -->
                                    <div id="warning_doan_lon" class="alert alert-warning py-2 px-3 mt-2 small d-none" style="border-radius: 8px;">
                                        <i class="bi bi-telephone-outbound-fill text-danger me-1"></i>
                                        <b>Đoàn tiệc lớn (&gt; 30 người):</b> Quý khách vui lòng liên hệ trực tiếp Quản trị viên qua Hotline: <b><a href="tel:0123456789" class="text-dark fw-bold">0123 456 789</a></b> để được tư vấn thiết kế sảnh riêng và nhận ưu đãi đoàn tiệc.
                                    </div>
                                </div>
                            </div>

                            <hr style="border-color: var(--stone); opacity: 0.6;">

                            <!-- Hàng 4: Ghi chú & Yêu cầu đặc biệt -->
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold small text-uppercase text-secondary" style="letter-spacing: 1px;">
                                        <i class="bi bi-chat-left-heart-fill me-1 text-warning"></i> 4. Yêu Cầu Đặc Biệt &amp; Ghi Chú
                                    </h6>
                                    <label class="form-label small fw-semibold">Ghi chú cho nhà hàng</label>
                                    <textarea name="ghi_chu" class="form-control" rows="3" 
                                              placeholder="Ví dụ: Đặt thêm bánh kem sinh nhật, yêu cầu trang trí bóng bay/hoa tươi mừng thọ, cần ghế trẻ em, chuẩn bị máy chiếu sân khấu..."><?= htmlspecialchars($oldBooking['ghi_chu'] ?? '') ?></textarea>
                                    <div class="form-text small">Mọi yêu cầu trang trí hoặc chuẩn bị riêng sẽ được đội ngũ chăm sóc khách hàng tư vấn chi tiết khi gọi xác nhận.</div>
                                </div>
                            </div>

                            <!-- Nút Xác Nhận -->
                            <div class="text-center pt-2">
                                <button type="submit" class="btn btn-primary-q px-5 py-3 fs-6 w-100 w-md-auto" style="border-radius: 6px;">
                                    <i class="bi bi-calendar2-check-fill me-2"></i> GỬI YÊU CẦU ĐẶT BÀN NGAY
                                </button>
                                <div class="small text-muted mt-2">
                                    <i class="bi bi-shield-lock me-1"></i> Thông tin của quý khách được bảo mật tuyệt đối. Miễn phí hủy trước 4 tiếng.
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Script kiểm tra số lượng người đoàn lớn & ràng buộc ngày -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputSoNguoi = document.getElementById('input_so_nguoi');
        const warningDoanLon = document.getElementById('warning_doan_lon');

        function checkSoNguoi() {
            if (!inputSoNguoi || !warningDoanLon) return;
            const val = parseInt(inputSoNguoi.value, 10);
            if (val > 30) {
                warningDoanLon.classList.remove('d-none');
            } else {
                warningDoanLon.classList.add('d-none');
            }
        }

        if (inputSoNguoi) {
            inputSoNguoi.addEventListener('input', checkSoNguoi);
            checkSoNguoi(); // kiểm tra ngay khi tải trang
        }
    });
</script>

<!-- ═══ PROCESS ═══ -->
<section class="proc-sec">
    <div class="container">
        <div class="text-center mb-5">
            <div class="sec-eyebrow justify-content-center" style="color:var(--amber)">Quy Trình</div>
            <h2 class="sec-title lt">Đặt bàn chỉ <em>4 bước đơn giản</em></h2>
        </div>
        <div class="row align-items-start g-3">
            <div class="col-lg-3 col-md-6">
                <div class="proc-card">
                    <div class="proc-num">01</div>
                    <div class="proc-ico"><i class="bi bi-calendar3"></i></div>
                    <h5>Chọn dịp & ngày</h5>
                    <p>Chọn loại tiệc và ngày bạn muốn tổ chức</p>
                </div>
            </div>
            <div class="col-auto d-none d-lg-flex align-items-center proc-arrow">
                <i class="bi bi-arrow-right"></i>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="proc-card">
                    <div class="proc-num">02</div>
                    <div class="proc-ico"><i class="bi bi-grid-1x2"></i></div>
                    <h5>Chọn bàn / phòng</h5>
                    <p>Xem bàn còn trống và chọn vị trí phù hợp</p>
                </div>
            </div>
            <div class="col-auto d-none d-lg-flex align-items-center proc-arrow">
                <i class="bi bi-arrow-right"></i>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="proc-card">
                    <div class="proc-num">03</div>
                    <div class="proc-ico"><i class="bi bi-journals"></i></div>
                    <h5>Chọn set / món</h5>
                    <p>Chọn set tiệc sẵn hoặc tự gọi món theo ý</p>
                </div>
            </div>
            <div class="col-auto d-none d-lg-flex align-items-center proc-arrow">
                <i class="bi bi-arrow-right"></i>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="proc-card">
                    <div class="proc-num">04</div>
                    <div class="proc-ico"><i class="bi bi-check2-circle"></i></div>
                    <h5>Xác nhận</h5>
                    <p>Gửi yêu cầu và nhà hàng sẽ duyệt cho bạn</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══ CTA ═══ -->
<section class="cta-sec">
    <div class="container">
        <div class="sec-eyebrow justify-content-center">Liên Hệ Ngay</div>
        <h2>Sẵn sàng cho<br><em>bữa tiệc tiếp theo?</em></h2>
        <p>Đặt bàn ngay hôm nay để nhận tư vấn miễn phí và ưu đãi dành cho khách mới.</p>
        <div>
            <a href="#dat-ban" class="btn-cta">
                <i class="bi bi-calendar2-check me-1"></i> Đặt Bàn Ngay
            </a>
            <a href="<?= BASE_URL ?>?action=thuc-don" class="btn-cta-out">Xem Thực Đơn</a>
        </div>
        <a href="tel:0123456789" class="hotline">0123 456 789</a>
    </div>
</section>

<!-- MODAL CHI TIẾT & HỦY BÀN -->
<?php require_once PATH_VIEW . 'booking_modal.php'; ?>
