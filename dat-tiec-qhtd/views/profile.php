<?php
/**
 * @var array $user           Thông tin người dùng
 * @var array $stats          Thống kê đơn đặt bàn
 * @var array $recentBookings 5 đơn đặt bàn mới nhất
 * @var float $totalSpent     Tổng tiền đã chi tiêu
 */

$statusBadgeMap = [
    'cho_duyet' => ['warning', 'Chờ duyệt', 'bi-hourglass-split'],
    'da_duyet'  => ['success', 'Đã duyệt', 'bi-check-circle-fill'],
    'tu_choi'   => ['danger', 'Từ chối', 'bi-x-circle-fill'],
    'da_huy'    => ['secondary', 'Đã hủy', 'bi-slash-circle'],
    'hoan_thanh'=> ['primary', 'Hoàn thành', 'bi-trophy-fill'],
];
?>

<style>
/* ─── HERO PROFILE ─── */
.profile-hero {
    padding: 130px 0 60px;
    background: linear-gradient(135deg, var(--forest-dk) 0%, var(--forest) 100%);
    color: #fff;
    position: relative;
    overflow: hidden;
}
.profile-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at 80% 20%, rgba(212,168,67,0.18), transparent 50%),
        radial-gradient(circle at 20% 80%, rgba(196,96,60,0.15), transparent 50%);
}

.avatar-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--amber), var(--terra));
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
    font-family: 'DM Serif Display', serif;
    box-shadow: 0 8px 24px rgba(0,0,0,0.25);
    border: 3px solid rgba(255,255,255,0.2);
}

.user-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 20px;
    padding: 4px 14px;
    font-size: 0.78rem;
    font-weight: 600;
}

/* ─── STAT CARDS ─── */
.stat-box {
    background: #fff;
    border: 1px solid var(--stone);
    border-radius: 14px;
    padding: 22px;
    transition: all 0.3s;
    height: 100%;
}
.stat-box:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(44,74,53,0.08);
    border-color: var(--terra);
}
.stat-box .stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
}
.stat-box .stat-val {
    font-family: 'DM Serif Display', serif;
    font-size: 1.8rem;
    color: var(--charcoal);
    line-height: 1.1;
    margin-top: 8px;
}
.stat-box .stat-label {
    font-size: 0.78rem;
    color: var(--muted);
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

/* ─── TABS & CARDS ─── */
.nav-profile-tabs {
    border-bottom: 2px solid var(--stone);
    margin-bottom: 24px;
}
.nav-profile-tabs .nav-link {
    border: none;
    background: transparent;
    color: var(--muted);
    font-weight: 600;
    font-size: 0.92rem;
    padding: 12px 20px;
    position: relative;
    transition: all 0.25s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.nav-profile-tabs .nav-link:hover {
    color: var(--forest);
}
.nav-profile-tabs .nav-link.active {
    color: var(--forest);
    font-weight: 700;
}
.nav-profile-tabs .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--forest);
    border-radius: 3px 3px 0 0;
}

.profile-card {
    background: #fff;
    border: 1px solid var(--stone);
    border-radius: 14px;
    padding: 28px;
    margin-bottom: 24px;
    box-shadow: 0 2px 12px rgba(44,74,53,0.03);
}

.profile-card-title {
    font-family: 'DM Serif Display', serif;
    font-size: 1.3rem;
    color: var(--forest);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 12px;
    border-bottom: 1px solid #F1F5F9;
}

.info-item-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px dashed #F1F5F9;
    font-size: 0.9rem;
}
.info-item-row:last-child {
    border-bottom: none;
}
.info-item-row .info-label {
    color: var(--muted);
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
}
.info-item-row .info-val {
    font-weight: 600;
    color: var(--charcoal);
}

/* ─── BOOKING ITEM MINI ─── */
.booking-item-mini {
    padding: 16px;
    border-radius: 10px;
    border: 1px solid #F1F5F9;
    background: #FAFAFA;
    transition: all 0.25s;
    margin-bottom: 12px;
}
.booking-item-mini:hover {
    background: #fff;
    border-color: var(--terra);
    box-shadow: 0 6px 18px rgba(44,74,53,0.06);
    transform: translateY(-2px);
}

.form-control-custom {
    border: 1.5px solid var(--stone);
    border-radius: 8px;
    padding: 11px 16px;
    font-size: 0.9rem;
    transition: all 0.2s;
}
.form-control-custom:focus {
    border-color: var(--forest);
    box-shadow: 0 0 0 3px rgba(44,74,53,0.12);
    outline: none;
}
.btn-save-profile {
    background: var(--forest);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 12px 28px;
    font-size: 0.88rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s;
}
.btn-save-profile:hover {
    background: var(--terra);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(196,96,60,0.3);
}
</style>

<!-- ═══ HERO SECTION ═══ -->
<section class="profile-hero">
    <div class="container position-relative" style="z-index: 2;">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-4">
            <!-- User identity -->
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle">
                    <?= mb_strtoupper(mb_substr($user['ho_ten'] ?? 'K', 0, 1, 'UTF-8')) ?>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                        <h2 class="mb-0 fw-bold" style="font-family: 'DM Serif Display', serif; font-size: clamp(1.6rem, 3vw, 2.2rem);">
                            <?= htmlspecialchars($user['ho_ten']) ?>
                        </h2>
                        <span class="user-badge text-warning">
                            <i class="bi bi-shield-check"></i>
                            <?= $user['vai_tro'] === 'admin' ? 'Quản Trị Viên' : 'Khách Hàng Thân Thiết' ?>
                        </span>
                    </div>
                    <p class="text-white-50 mb-0 small">
                        <i class="bi bi-envelope me-1"></i> <?= htmlspecialchars($user['email']) ?>
                        <span class="mx-2 opacity-50">•</span>
                        <i class="bi bi-telephone me-1"></i> <?= htmlspecialchars($user['so_dien_thoai'] ?? 'Chưa cập nhật') ?>
                        <span class="mx-2 opacity-50">•</span>
                        <i class="bi bi-calendar3 me-1"></i> Thành viên từ: <?= !empty($user['ngay_tao']) ? date('d/m/Y', strtotime($user['ngay_tao'])) : '2026' ?>
                    </p>
                </div>
            </div>

            <!-- Quick actions -->
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= BASE_URL ?>#dat-ban" class="btn btn-warning fw-bold text-dark px-3 py-2" style="border-radius: 8px; font-size: 0.85rem;">
                    <i class="bi bi-calendar-plus me-1"></i> Đặt Bàn Ngay
                </a>
                <a href="<?= BASE_URL ?>?action=lich-su-dat-ban" class="btn btn-outline-light px-3 py-2 fw-semibold" style="border-radius: 8px; font-size: 0.85rem;">
                    <i class="bi bi-clock-history me-1"></i> Lịch Sử Đặt
                </a>
                <?php if ($user['vai_tro'] === 'admin'): ?>
                    <a href="<?= BASE_URL ?>?action=admin" class="btn btn-light px-3 py-2 fw-bold text-danger" style="border-radius: 8px; font-size: 0.85rem;">
                        <i class="bi bi-shield-lock-fill me-1"></i> Trang Quản Trị
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ═══ NỘI DUNG CHÍNH ═══ -->
<section class="py-5" style="background: var(--cream);">
    <div class="container">

        <!-- THÔNG BÁO FLASH MESSAGE -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 10px;">
                <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i>
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 10px;">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i>
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- ═══ CARDS THỐNG KÊ ═══ -->
        <div class="row g-3 mb-5">
            <!-- Tổng đơn -->
            <div class="col-6 col-lg-3">
                <div class="stat-box">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="stat-label">Tổng đơn đặt</span>
                        <div class="stat-icon bg-primary-subtle text-primary">
                            <i class="bi bi-calendar2-range"></i>
                        </div>
                    </div>
                    <div class="stat-val"><?= $stats['total'] ?></div>
                    <div class="text-muted small mt-1">Lần trải nghiệm ẩm thực</div>
                </div>
            </div>

            <!-- Chờ duyệt -->
            <div class="col-6 col-lg-3">
                <div class="stat-box">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="stat-label">Đang chờ duyệt</span>
                        <div class="stat-icon bg-warning-subtle text-warning">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                    <div class="stat-val text-warning"><?= $stats['cho_duyet'] ?></div>
                    <div class="text-muted small mt-1">Nhà hàng đang xử lý</div>
                </div>
            </div>

            <!-- Đã duyệt -->
            <div class="col-6 col-lg-3">
                <div class="stat-box">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="stat-label">Đã xác nhận</span>
                        <div class="stat-icon bg-success-subtle text-success">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                    <div class="stat-val text-success"><?= $stats['da_duyet'] ?></div>
                    <div class="text-muted small mt-1">Sẵn sàng đón tiếp bạn</div>
                </div>
            </div>

            <!-- Tổng chi tiêu -->
            <div class="col-6 col-lg-3">
                <div class="stat-box">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="stat-label">Tích lũy chi tiêu</span>
                        <div class="stat-icon text-danger" style="background: rgba(196,96,60,0.12);">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                    <div class="stat-val" style="color: var(--terra); font-size: 1.5rem;">
                        <?= number_format($totalSpent, 0, ',', '.') ?><small style="font-size: 0.9rem;">đ</small>
                    </div>
                    <div class="text-muted small mt-1">Hạng thành viên Bạc</div>
                </div>
            </div>
        </div>

        <!-- ═══ TABS CHỨC NĂNG ═══ -->
        <ul class="nav nav-profile-tabs" id="profileTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#tab-overview" type="button" role="tab">
                    <i class="bi bi-grid-fill"></i> Tổng Quan &amp; Đơn Gần Đây
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="edit-tab" data-bs-toggle="tab" data-bs-target="#tab-edit" type="button" role="tab">
                    <i class="bi bi-person-gear"></i> Cập Nhật Thông Tin
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#tab-security" type="button" role="tab">
                    <i class="bi bi-shield-lock"></i> Đổi Mật Khẩu
                </button>
            </li>
        </ul>

        <div class="tab-content" id="profileTabContent">

            <!-- ── TAB 1: TỔNG QUAN ── -->
            <div class="tab-pane fade show active" id="tab-overview" role="tabpanel">
                <div class="row g-4">
                    <!-- Cột trái: Thông tin hồ sơ tóm tắt -->
                    <div class="col-lg-5">
                        <div class="profile-card">
                            <h4 class="profile-card-title">
                                <i class="bi bi-person-lines-fill text-success"></i> Hồ Sơ Của Bạn
                            </h4>
                            <div class="info-item-row">
                                <span class="info-label"><i class="bi bi-person text-secondary"></i> Họ và tên</span>
                                <span class="info-val"><?= htmlspecialchars($user['ho_ten']) ?></span>
                            </div>
                            <div class="info-item-row">
                                <span class="info-label"><i class="bi bi-envelope text-secondary"></i> Email</span>
                                <span class="info-val"><?= htmlspecialchars($user['email']) ?></span>
                            </div>
                            <div class="info-item-row">
                                <span class="info-label"><i class="bi bi-telephone text-secondary"></i> Số điện thoại</span>
                                <span class="info-val"><?= htmlspecialchars($user['so_dien_thoai'] ?? 'Chưa cập nhật') ?></span>
                            </div>
                            <div class="info-item-row">
                                <span class="info-label"><i class="bi bi-shield-check text-secondary"></i> Vai trò</span>
                                <span class="badge bg-success-subtle text-success px-2 py-1">
                                    <?= $user['vai_tro'] === 'admin' ? 'Quản trị viên' : 'Khách hàng' ?>
                                </span>
                            </div>
                            <div class="info-item-row">
                                <span class="info-label"><i class="bi bi-clock-history text-secondary"></i> Ngày tham gia</span>
                                <span class="info-val"><?= !empty($user['ngay_tao']) ? date('d/m/Y - H:i', strtotime($user['ngay_tao'])) : 'N/A' ?></span>
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                <button type="button" class="btn btn-outline-success w-100 fw-semibold" onclick="document.getElementById('edit-tab').click();" style="border-radius: 8px;">
                                    <i class="bi bi-pencil-square me-1"></i> Chỉnh sửa thông tin
                                </button>
                            </div>
                        </div>

                        <!-- Card ưu đãi khách hàng -->
                        <div class="profile-card" style="background: linear-gradient(135deg, rgba(212,168,67,0.1), rgba(196,96,60,0.06)); border-color: rgba(212,168,67,0.3);">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <i class="bi bi-gift-fill text-warning fs-3"></i>
                                <div>
                                    <h6 class="mb-0 fw-bold" style="color: var(--forest);">Đặc quyền khách hàng QHT</h6>
                                    <small class="text-muted">Ưu đãi dành riêng cho tiệc gia đình &amp; sinh nhật</small>
                                </div>
                            </div>
                            <ul class="small mb-0 ps-3 mt-3 text-secondary" style="line-height: 1.9;">
                                <li>Tặng nến và bảng chúc mừng khi đặt tiệc sinh nhật.</li>
                                <li>Miễn phí trang trí bàn tiệc theo tông màu yêu cầu.</li>
                                <li>Giảm 10% cho lần đặt tiếp theo từ 10 khách trở lên.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Cột phải: Các đơn đặt bàn gần đây -->
                    <div class="col-lg-7">
                        <div class="profile-card">
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                <h4 class="profile-card-title mb-0 border-0 p-0">
                                    <i class="bi bi-calendar-check text-primary"></i> Đơn Đặt Bàn Gần Đây
                                </h4>
                                <a href="<?= BASE_URL ?>?action=lich-su-dat-ban" class="btn btn-sm btn-outline-dark" style="border-radius: 6px; font-size: 0.8rem;">
                                    Xem tất cả (<?= $stats['total'] ?>) <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>

                            <?php if (empty($recentBookings)): ?>
                                <div class="text-center py-5">
                                    <i class="bi bi-calendar-x fs-1 text-muted d-block mb-3"></i>
                                    <h6 class="fw-bold text-secondary">Bạn chưa có đơn đặt bàn nào</h6>
                                    <p class="text-muted small mb-4">Hãy đặt bàn hoặc chọn set tiệc ngay hôm nay để nhận nhiều ưu đãi đặc biệt!</p>
                                    <a href="<?= BASE_URL ?>#dat-ban" class="btn btn-success px-4 py-2" style="border-radius: 8px; font-size: 0.88rem;">
                                        <i class="bi bi-calendar-plus me-1"></i> Đặt Bàn Tiệc Ngay
                                    </a>
                                </div>
                            <?php else: ?>
                                <?php foreach ($recentBookings as $b): ?>
                                    <?php
                                    $st = $statusBadgeMap[$b['trang_thai']] ?? ['secondary', $b['trang_thai'], 'bi-dot'];
                                    ?>
                                    <div class="booking-item-mini">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <span class="fw-bold text-secondary small">#DB<?= str_pad($b['id'], 3, '0', STR_PAD_LEFT) ?></span>
                                                <h6 class="mb-0 mt-1 fw-bold" style="color: var(--charcoal);">
                                                    <i class="bi bi-door-open text-success me-1"></i> <?= htmlspecialchars($b['ten_ban'] ?? 'Bàn tiệc') ?>
                                                    <?php if (!empty($b['ten_set'])): ?>
                                                        <span class="badge bg-light text-dark border ms-1" style="font-size: 0.75rem;">
                                                            <?= htmlspecialchars($b['ten_set']) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </h6>
                                            </div>
                                            <span class="badge bg-<?= $st[0] ?>-subtle text-<?= $st[0] ?> border border-<?= $st[0] ?>-subtle px-2 py-1" style="font-size: 0.75rem;">
                                                <i class="bi <?= $st[2] ?> me-1"></i><?= $st[1] ?>
                                            </span>
                                        </div>

                                        <div class="d-flex flex-wrap gap-3 small text-muted mt-2">
                                            <span>
                                                <i class="bi bi-calendar-event me-1"></i>
                                                <?= date('d/m/Y', strtotime($b['ngay_dat'])) ?>
                                                (Ca <?= $b['ca'] === 'trua' ? 'Trưa 11:00-14:00' : 'Tối 17:00-21:30' ?>)
                                            </span>
                                            <span><i class="bi bi-people me-1"></i><?= $b['so_nguoi'] ?> khách</span>
                                            <?php if (!empty($b['ten_dip'])): ?>
                                                <span><i class="bi bi-balloon me-1"></i><?= htmlspecialchars($b['ten_dip']) ?></span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                            <span class="small text-muted">Tổng tiền:</span>
                                            <div class="d-flex align-items-center gap-2">
                                                <strong style="color: var(--terra); font-size: 1rem;">
                                                    <?= number_format($b['tong_tien'] ?? 0, 0, ',', '.') ?>đ
                                                </strong>
                                                <a href="<?= BASE_URL ?>?action=chi-tiet-dat-ban&id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size: 0.75rem; border-radius: 6px;">
                                                    Xem chi tiết
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── TAB 2: CẬP NHẬT THÔNG TIN ── -->
            <div class="tab-pane fade" id="tab-edit" role="tabpanel">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="profile-card">
                            <h4 class="profile-card-title">
                                <i class="bi bi-pencil-square text-success"></i> Cập Nhật Thông Tin Cá Nhân
                            </h4>
                            <p class="text-muted small mb-4">
                                Thông tin này sẽ được tự động điền khi bạn đặt bàn hoặc đặt set tiệc tại QHT Banquet.
                            </p>

                            <form action="<?= BASE_URL ?>?action=cap-nhat-thong-tin" method="POST">
                                <!-- Họ và tên -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-person me-1"></i> Họ và tên <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="ho_ten" class="form-control form-control-custom"
                                           value="<?= htmlspecialchars($user['ho_ten'] ?? '') ?>" required>
                                </div>

                                <!-- Email (Không cho đổi để đảm bảo an toàn) -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-envelope me-1"></i> Địa chỉ Email
                                    </label>
                                    <input type="email" class="form-control form-control-custom bg-light"
                                           value="<?= htmlspecialchars($user['email'] ?? '') ?>" readonly disabled>
                                    <div class="form-text small text-muted">
                                        <i class="bi bi-lock me-1"></i> Email dùng làm tên đăng nhập, không thể thay đổi.
                                    </div>
                                </div>

                                <!-- Số điện thoại -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-telephone me-1"></i> Số điện thoại <span class="text-danger">*</span>
                                    </label>
                                    <input type="tel" name="so_dien_thoai" class="form-control form-control-custom"
                                           value="<?= htmlspecialchars($user['so_dien_thoai'] ?? '') ?>" required
                                           placeholder="Ví dụ: 0987654321">
                                    <div class="form-text small text-muted">Nhà hàng sẽ liên hệ xác nhận đơn đặt bàn qua số điện thoại này.</div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 pt-2">
                                    <button type="reset" class="btn btn-outline-secondary px-4 py-2" style="border-radius: 8px;">
                                        Đặt lại
                                    </button>
                                    <button type="submit" class="btn-save-profile">
                                        <i class="bi bi-check2-circle me-1"></i> Lưu Thay Đổi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── TAB 3: ĐỔI MẬT KHẨU ── -->
            <div class="tab-pane fade" id="tab-security" role="tabpanel">
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <div class="profile-card">
                            <h4 class="profile-card-title">
                                <i class="bi bi-shield-lock-fill text-warning"></i> Đổi Mật Khẩu
                            </h4>
                            <p class="text-muted small mb-4">
                                Để bảo mật tài khoản, vui lòng chọn mật khẩu có ít nhất 6 ký tự kết hợp chữ và số.
                            </p>

                            <form action="<?= BASE_URL ?>?action=doi-mat-khau" method="POST">
                                <!-- Mật khẩu hiện tại -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-key me-1"></i> Mật khẩu hiện tại <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" name="mat_khau_cu" class="form-control form-control-custom" required
                                           placeholder="Nhập mật khẩu đang dùng">
                                </div>

                                <!-- Mật khẩu mới -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-lock me-1"></i> Mật khẩu mới <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" name="mat_khau_moi" class="form-control form-control-custom" required minlength="6"
                                           placeholder="Tối thiểu 6 ký tự">
                                </div>

                                <!-- Nhập lại mật khẩu mới -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-check-lg me-1"></i> Xác nhận mật khẩu mới <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" name="nhap_lai_mat_khau" class="form-control form-control-custom" required minlength="6"
                                           placeholder="Nhập lại mật khẩu mới">
                                </div>

                                <div class="d-flex justify-content-end gap-2 pt-2">
                                    <button type="submit" class="btn-save-profile" style="background: var(--terra);">
                                        <i class="bi bi-arrow-repeat me-1"></i> Cập Nhật Mật Khẩu
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Script xử lý hash url để mở đúng tab nếu có #tab-security -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.location.hash) {
        const hash = window.location.hash;
        if (hash === '#tab-security') {
            const secTab = document.getElementById('security-tab');
            if (secTab) secTab.click();
        } else if (hash === '#tab-edit') {
            const editTab = document.getElementById('edit-tab');
            if (editTab) editTab.click();
        }
    }
});
</script>
