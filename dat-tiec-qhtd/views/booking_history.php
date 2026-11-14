<style>
.history-hero {
    background: linear-gradient(135deg, var(--forest-dk) 0%, var(--forest) 100%);
    padding: 120px 0 50px;
    color: var(--white);
    position: relative;
    overflow: hidden;
}
.history-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 80% 20%, rgba(212,168,67,0.15), transparent 50%);
}
.history-card {
    background: var(--white);
    border-radius: 12px;
    border: 1px solid var(--stone);
    transition: all 0.3s ease;
}
.history-card:hover {
    border-color: var(--terra);
    box-shadow: 0 10px 30px rgba(44,74,53,0.08);
    transform: translateY(-2px);
}
.filter-tab-pill {
    padding: 8px 18px;
    border-radius: 30px;
    font-size: 0.82rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.25s;
    border: 1px solid var(--stone);
    color: var(--charcoal);
    background: var(--white);
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.filter-tab-pill:hover {
    border-color: var(--terra);
    color: var(--terra);
}
.filter-tab-pill.active {
    background: var(--forest);
    border-color: var(--forest);
    color: var(--white);
}
.badge-status-cho_duyet {
    background: #FFF3CD;
    color: #856404;
    border: 1px solid #FFEEBA;
}
.badge-status-da_duyet {
    background: #D4EDDA;
    color: #155724;
    border: 1px solid #C3E6CB;
}
.badge-status-tu_choi {
    background: #F8D7DA;
    color: #721C24;
    border: 1px solid #F5C6CB;
}
.badge-status-da_huy {
    background: #E2E3E5;
    color: #383D41;
    border: 1px solid #D6D8DB;
}
</style>

<!-- HERO BANNER -->
<section class="history-hero">
    <div class="container position-relative" style="z-index: 2;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small text-white-50 mb-2">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-white text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item active text-warning" aria-current="page">Lịch sử đặt bàn</li>
            </ol>
        </nav>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-2" style="letter-spacing: 1.5px; font-size: 0.7rem;">
                    <i class="bi bi-clock-history me-1"></i>Quản lý tiệc gia đình
                </span>
                <h1 class="h2 fw-bold mb-1">Lịch Sử Đặt Bàn Của Quý Khách</h1>
                <p class="text-white-50 small mb-0">Theo dõi trạng thái, chi tiết đặt chỗ và xem lại các bữa tiệc ấm cúng của bạn.</p>
            </div>
            <div>
                <a href="<?= BASE_URL ?>?action=dat-ban" class="btn btn-warning fw-bold px-4 py-2 shadow-sm" style="border-radius: 6px;">
                    <i class="bi bi-calendar-plus me-1"></i> Đặt Bàn Mới
                </a>
            </div>
        </div>
    </div>
</section>

<!-- THÔNG BÁO FLASH MESSAGE -->
<div class="container mt-4">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i>
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i>
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
</div>

<div class="container my-5">

    <?php if ($isLoggedIn): ?>
        <!-- ═══ PHẦN DÀNH CHO KHÁCH ĐÃ ĐĂNG NHẬP ═══ -->

        <!-- STATS COUNTERS -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="p-3 bg-white rounded-3 border shadow-sm">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-muted small fw-semibold">Tổng đơn đặt</span>
                        <div class="p-2 rounded bg-primary-subtle text-primary"><i class="bi bi-journal-text fs-5"></i></div>
                    </div>
                    <div class="fs-3 fw-bold text-dark mt-2"><?= $stats['total'] ?></div>
                    <div class="text-muted small">Tất cả thời gian</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="p-3 bg-white rounded-3 border shadow-sm">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-muted small fw-semibold">Đang chờ duyệt</span>
                        <div class="p-2 rounded bg-warning-subtle text-warning"><i class="bi bi-hourglass-split fs-5"></i></div>
                    </div>
                    <div class="fs-3 fw-bold text-warning mt-2"><?= $stats['cho_duyet'] ?></div>
                    <div class="text-muted small">Nhà hàng đang xử lý</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="p-3 bg-white rounded-3 border shadow-sm">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-muted small fw-semibold">Đã xác nhận</span>
                        <div class="p-2 rounded bg-success-subtle text-success"><i class="bi bi-check2-circle fs-5"></i></div>
                    </div>
                    <div class="fs-3 fw-bold text-success mt-2"><?= $stats['da_duyet'] ?></div>
                    <div class="text-muted small">Sẵn sàng đón khách</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="p-3 bg-white rounded-3 border shadow-sm">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-muted small fw-semibold">Đã hủy / Từ chối</span>
                        <div class="p-2 rounded bg-secondary-subtle text-secondary"><i class="bi bi-slash-circle fs-5"></i></div>
                    </div>
                    <div class="fs-3 fw-bold text-secondary mt-2"><?= ($stats['da_huy'] + $stats['tu_choi']) ?></div>
                    <div class="text-muted small">Không thực hiện</div>
                </div>
            </div>
        </div>

        <!-- BỘ LỌC TRẠNG THÁI -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 p-3 bg-white rounded-3 border shadow-sm">
            <div class="d-flex flex-wrap gap-2">
                <a href="<?= BASE_URL ?>?action=lich-su-dat-ban" 
                   class="filter-tab-pill <?= empty($statusFilter) ? 'active' : '' ?>">
                    Tất cả <span class="badge bg-light text-dark rounded-pill"><?= $stats['total'] ?></span>
                </a>
                <a href="<?= BASE_URL ?>?action=lich-su-dat-ban&status=cho_duyet" 
                   class="filter-tab-pill <?= $statusFilter === 'cho_duyet' ? 'active' : '' ?>">
                    <i class="bi bi-hourglass-split text-warning"></i> Chờ duyệt 
                    <span class="badge bg-warning text-dark rounded-pill"><?= $stats['cho_duyet'] ?></span>
                </a>
                <a href="<?= BASE_URL ?>?action=lich-su-dat-ban&status=da_duyet" 
                   class="filter-tab-pill <?= $statusFilter === 'da_duyet' ? 'active' : '' ?>">
                    <i class="bi bi-check-circle-fill text-success"></i> Đã duyệt 
                    <span class="badge bg-success rounded-pill"><?= $stats['da_duyet'] ?></span>
                </a>
                <a href="<?= BASE_URL ?>?action=lich-su-dat-ban&status=da_huy" 
                   class="filter-tab-pill <?= $statusFilter === 'da_huy' ? 'active' : '' ?>">
                    <i class="bi bi-slash-circle text-secondary"></i> Đã hủy 
                    <span class="badge bg-secondary rounded-pill"><?= $stats['da_huy'] ?></span>
                </a>
                <a href="<?= BASE_URL ?>?action=lich-su-dat-ban&status=tu_choi" 
                   class="filter-tab-pill <?= $statusFilter === 'tu_choi' ? 'active' : '' ?>">
                    <i class="bi bi-x-circle-fill text-danger"></i> Từ chối 
                    <span class="badge bg-danger rounded-pill"><?= $stats['tu_choi'] ?></span>
                </a>
            </div>

            <!-- Tìm kiếm nhanh -->
            <div class="ms-auto" style="min-width: 250px;">
                <input type="text" id="filterKeywordInput" class="form-control form-control-sm" placeholder="Tìm kiếm nhanh đơn...">
            </div>
        </div>

        <!-- DANH SÁCH ĐƠN ĐẶT BÀN -->
        <?php if (empty($bookings)): ?>
            <div class="text-center py-5 bg-white rounded-3 border shadow-sm">
                <div class="text-muted mb-3">
                    <i class="bi bi-calendar-x" style="font-size: 3.5rem; color: var(--stone);"></i>
                </div>
                <h5 class="fw-bold text-dark">Chưa có đơn đặt bàn nào trong mục này</h5>
                <p class="text-muted small mb-4" style="max-width: 450px; margin: 0 auto;">
                    <?= !empty($statusFilter) ? 'Hiện không có đơn nào ở trạng thái lọc này.' : 'Quý khách chưa tạo đơn đặt tiệc nào. Hãy đặt ngay bàn tiệc ấm cúng hôm nay!' ?>
                </p>
                <a href="<?= BASE_URL ?>?action=dat-ban" class="btn btn-warning fw-bold px-4 py-2">
                    <i class="bi bi-calendar2-check me-1"></i> Đặt Bàn Ngay
                </a>
            </div>
        <?php else: ?>
            <div class="row g-4" id="bookingCardContainer">
                <?php foreach ($bookings as $b): ?>
                    <?php
                    $statusMap = [
                        'cho_duyet' => ['badge' => 'badge-status-cho_duyet', 'text' => 'Chờ duyệt', 'icon' => 'bi-hourglass-split'],
                        'da_duyet'  => ['badge' => 'badge-status-da_duyet',  'text' => 'Đã duyệt',  'icon' => 'bi-check-circle-fill'],
                        'tu_choi'   => ['badge' => 'badge-status-tu_choi',   'text' => 'Từ chối',   'icon' => 'bi-x-circle-fill'],
                        'da_huy'    => ['badge' => 'badge-status-da_huy',    'text' => 'Đã hủy',    'icon' => 'bi-slash-circle'],
                    ];
                    $st = $statusMap[$b['trang_thai']] ?? ['badge' => 'bg-secondary text-white', 'text' => $b['trang_thai'], 'icon' => 'bi-info-circle'];
                    ?>
                    <div class="col-lg-6 booking-item-card" data-search="<?= strtolower(htmlspecialchars($b['id'] . ' ' . $b['ten_ban'] . ' ' . $b['ten_dip'] . ' ' . ($b['ten_set'] ?? ''))) ?>">
                        <div class="history-card p-4 h-100 d-flex flex-column justify-content-between">
                            
                            <!-- Card Header -->
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold text-secondary fs-6">#DB<?= str_pad($b['id'], 4, '0', STR_PAD_LEFT) ?></span>
                                        <span class="badge <?= $st['badge'] ?> px-2 py-1 fw-semibold">
                                            <i class="bi <?= $st['icon'] ?> me-1"></i><?= $st['text'] ?>
                                        </span>
                                    </div>
                                    <span class="text-muted small">
                                        <i class="bi bi-clock me-1"></i><?= date('d/m/Y H:i', strtotime($b['ngay_tao'])) ?>
                                    </span>
                                </div>

                                <!-- Card Body: Table & Occasion -->
                                <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                                    <div>
                                        <h5 class="fw-bold text-dark mb-1">
                                            <i class="bi bi-<?= ($b['loai_ban'] ?? '') === 'phong_rieng' ? 'star-fill text-warning' : 'circle-fill text-success' ?> me-1" style="font-size: 0.85rem;"></i>
                                            <?= htmlspecialchars($b['ten_ban'] ?? 'Bàn sảnh') ?>
                                        </h5>
                                        <span class="badge bg-light text-secondary border small">
                                            <?= ($b['loai_ban'] ?? '') === 'phong_rieng' ? 'Phòng VIP riêng tư' : 'Bàn sảnh chung' ?>
                                        </span>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fw-semibold">
                                            <i class="bi bi-balloon-heart me-1"></i><?= htmlspecialchars($b['ten_dip'] ?? 'Dịp tiệc') ?>
                                        </span>
                                    </div>
                                </div>

                                <!-- Grid info -->
                                <div class="row g-2 small text-secondary mb-3">
                                    <div class="col-sm-6">
                                        <div><i class="bi bi-calendar3 text-danger me-1"></i> <strong>Ngày:</strong> <?= date('d/m/Y', strtotime($b['ngay_dat'])) ?></div>
                                        <div>
                                            <i class="bi bi-sun text-warning me-1"></i> <strong>Ca:</strong> 
                                            <?= $b['ca'] === 'trua' ? 'Ca Trưa (11:00-14:00)' : 'Ca Tối (17:00-21:30)' ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div><i class="bi bi-people text-info me-1"></i> <strong>Số khách:</strong> <?= $b['so_nguoi'] ?> người</div>
                                        <div>
                                            <i class="bi bi-journals text-primary me-1"></i> <strong>Thực đơn:</strong> 
                                            <?= !empty($b['ten_set']) ? htmlspecialchars($b['ten_set']) : 'Gọi món lẻ' ?>
                                        </div>
                                    </div>
                                </div>

                                <?php if (!empty($b['ghi_chu'])): ?>
                                    <div class="p-2 rounded bg-light border small text-muted mb-3 fst-italic">
                                        <i class="bi bi-chat-left-dots text-secondary me-1"></i> 
                                        "<?= htmlspecialchars(mb_strimwidth($b['ghi_chu'], 0, 90, '...')) ?>"
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Card Footer: Total Price & Buttons -->
                            <div class="pt-3 border-top d-flex flex-wrap justify-content-between align-items-center gap-2 mt-auto">
                                <div>
                                    <div class="text-muted small">Tổng tiền:</div>
                                    <div class="fw-bold text-danger fs-5"><?= number_format($b['tong_tien'], 0, ',', '.') ?> đ</div>
                                </div>
                                <div class="d-flex gap-2">
                                    <?php if ($b['trang_thai'] === 'cho_duyet'): ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger px-3 fw-semibold" onclick="promptCancelBooking(<?= $b['id'] ?>)">
                                            <i class="bi bi-x-circle me-1"></i>Hủy đơn
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
        <?php endif; ?>

    <?php else: ?>
        <!-- ═══ PHẦN DÀNH CHO KHÁCH CHƯA ĐĂNG NHẬP (TRA CỨU NHANH) ═══ -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <!-- Hộp tra cứu -->
                <div class="card border-0 shadow-sm p-4 p-md-5 mb-5" style="border-radius: 16px; background: var(--white);">
                    <div class="text-center mb-4">
                        <div class="p-3 d-inline-block rounded-circle bg-warning-subtle text-warning mb-3">
                            <i class="bi bi-search fs-2"></i>
                        </div>
                        <h3 class="fw-bold text-dark">Tra Cứu Lịch Sử Đặt Bàn</h3>
                        <p class="text-muted small">
                            Nhập <strong>Số điện thoại</strong> đã đăng ký hoặc <strong>Mã đơn đặt bàn</strong> (Ví dụ: 0912345678 hoặc #1) để kiểm tra trạng thái và chi tiết.
                        </p>
                    </div>

                    <form action="<?= BASE_URL ?>" method="GET" class="mb-4">
                        <input type="hidden" name="action" value="lich-su-dat-ban">
                        <div class="input-group input-group-lg shadow-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Nhập số điện thoại hoặc mã đơn..." value="<?= htmlspecialchars($searchTerm) ?>" required>
                            <button class="btn btn-primary-q" type="submit">Tra Cứu</button>
                        </div>
                    </form>

                    <div class="text-center pt-3 border-top">
                        <span class="text-muted small">Đã có tài khoản QHT Banquet?</span>
                        <a href="<?= BASE_URL ?>?action=dang-nhap" class="fw-bold text-success text-decoration-none ms-1">
                            Đăng nhập ngay <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Kết quả tra cứu nếu có -->
                <?php if (!empty($searchTerm)): ?>
                    <h5 class="fw-bold mb-3 text-dark">
                        <i class="bi bi-list-check me-2 text-primary"></i>Kết quả tra cứu cho "<?= htmlspecialchars($searchTerm) ?>" (<?= count($bookings) ?> kết quả):
                    </h5>

                    <?php if (empty($bookings)): ?>
                        <div class="alert alert-warning text-center p-4 rounded-3 border-0 shadow-sm">
                            <i class="bi bi-info-circle-fill fs-3 d-block mb-2 text-warning"></i>
                            Không tìm thấy đơn đặt bàn nào khớp với từ khóa "<strong><?= htmlspecialchars($searchTerm) ?></strong>".<br>
                            Vui lòng kiểm tra lại số điện thoại hoặc liên hệ hotline <strong>0123 456 789</strong> để được hỗ trợ.
                        </div>
                    <?php else: ?>
                        <div class="row g-4">
                            <?php foreach ($bookings as $b): ?>
                                <?php
                                $statusMap = [
                                    'cho_duyet' => ['badge' => 'badge-status-cho_duyet', 'text' => 'Chờ duyệt', 'icon' => 'bi-hourglass-split'],
                                    'da_duyet'  => ['badge' => 'badge-status-da_duyet',  'text' => 'Đã duyệt',  'icon' => 'bi-check-circle-fill'],
                                    'tu_choi'   => ['badge' => 'badge-status-tu_choi',   'text' => 'Từ chối',   'icon' => 'bi-x-circle-fill'],
                                    'da_huy'    => ['badge' => 'badge-status-da_huy',    'text' => 'Đã hủy',    'icon' => 'bi-slash-circle'],
                                ];
                                $st = $statusMap[$b['trang_thai']] ?? ['badge' => 'bg-secondary text-white', 'text' => $b['trang_thai'], 'icon' => 'bi-info-circle'];
                                ?>
                                <div class="col-md-6">
                                    <div class="history-card p-4 h-100 d-flex flex-column justify-content-between">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="fw-bold fs-6 text-secondary">#DB<?= str_pad($b['id'], 4, '0', STR_PAD_LEFT) ?></span>
                                                <span class="badge <?= $st['badge'] ?> px-2 py-1"><i class="bi <?= $st['icon'] ?> me-1"></i><?= $st['text'] ?></span>
                                            </div>
                                            <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($b['ten_ban'] ?? 'Bàn sảnh') ?></h6>
                                            <div class="text-muted small mb-2"><?= htmlspecialchars($b['ten_dip'] ?? 'Dịp tiệc') ?></div>
                                            <div class="small text-secondary mb-2">
                                                <div><i class="bi bi-calendar3 me-1 text-danger"></i> Ngày: <?= date('d/m/Y', strtotime($b['ngay_dat'])) ?></div>
                                                <div><i class="bi bi-clock me-1 text-warning"></i> Ca: <?= $b['ca'] === 'trua' ? 'Trưa (11:00-14:00)' : 'Tối (17:00-21:30)' ?></div>
                                                <div><i class="bi bi-people me-1 text-info"></i> Số lượng: <?= $b['so_nguoi'] ?> khách</div>
                                            </div>
                                        </div>
                                        <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                                            <div class="fw-bold text-danger"><?= number_format($b['tong_tien'], 0, ',', '.') ?> đ</div>
                                            <button type="button" class="btn btn-sm btn-dark px-3" onclick="viewBookingDetail(<?= $b['id'] ?>)">
                                                <i class="bi bi-eye me-1"></i>Xem chi tiết
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

            </div>
        </div>
    <?php endif; ?>

</div>

<!-- NHÚNG MODAL CHI TIẾT & HỦY ĐẶT BÀN -->
<?php require_once PATH_VIEW . 'booking_modal.php'; ?>

<script>
// Lọc nhanh bằng JavaScript trong trang
document.getElementById('filterKeywordInput')?.addEventListener('input', function(e) {
    const val = e.target.value.toLowerCase().trim();
    const cards = document.querySelectorAll('.booking-item-card');
    cards.forEach(card => {
        const searchData = card.getAttribute('data-search') || '';
        card.style.display = searchData.includes(val) ? '' : 'none';
    });
});
</script>
