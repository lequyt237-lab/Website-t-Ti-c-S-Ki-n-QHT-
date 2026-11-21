<!-- Thẻ thông báo nếu có -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- ═══ METRIC STATS CARDS ═══ -->
<div class="row g-3 mb-4">
    <!-- Card Doanh thu thực thu -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: linear-gradient(135deg, #f0fdf4, #ffffff); border-left: 4px solid #16a34a !important;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-success small fw-bold text-uppercase">Doanh thu thực thu</span>
                    <div class="p-2 rounded-3 bg-success-subtle text-success">
                        <i class="bi bi-cash-coin fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-success"><?= number_format($stats['revenue'] ?? 0, 0, ',', '.') ?> đ</h3>
                <div class="small text-muted d-flex justify-content-between">
                    <span>Hôm nay: <strong><?= number_format($stats['today_revenue'] ?? 0, 0, ',', '.') ?> đ</strong></span>
                    <a href="<?= BASE_URL ?>?action=admin-thanh-toan" class="text-success text-decoration-none fw-semibold">Chi tiết &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 1: Đơn đặt bàn -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Đơn đặt bàn</span>
                    <div class="p-2 rounded-3 bg-danger-subtle text-danger">
                        <i class="bi bi-calendar-event fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1"><?= $stats['bookings'] ?></h3>
                <div class="small text-muted d-flex justify-content-between">
                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> <?= $stats['pending'] ?> chờ duyệt</span>
                    <?php if (!empty($stats['pending_payments'])): ?>
                        <span class="badge bg-danger"><i class="bi bi-credit-card"></i> <?= $stats['pending_payments'] ?> cọc chờ</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Bàn & Phòng -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Bàn &amp; Phòng</span>
                    <div class="p-2 rounded-3 bg-success-subtle text-success">
                        <i class="bi bi-door-open fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1"><?= $stats['tables'] ?></h3>
                <div class="small text-muted">Bàn thường &amp; Phòng VIP</div>
            </div>
        </div>
    </div>

    <!-- Card 3: Ẩm thực -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Món ăn &amp; Set tiệc</span>
                    <div class="p-2 rounded-3 bg-primary-subtle text-primary">
                        <i class="bi bi-egg-fried fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1"><?= $stats['dishes'] ?> món</h3>
                <div class="small text-muted"><?= $stats['sets'] ?> set tiệc có sẵn</div>
            </div>
        </div>
    </div>
</div>

<!-- ═══ BẢNG ĐẶT BÀN GẦN ĐÂY ═══ -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold fs-6"><i class="bi bi-clock-history me-2 text-primary"></i>Đơn Đặt Bàn Gần Đây</h5>
                <span class="badge bg-light text-dark border">Thời gian thực</span>
            </div>
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light small text-uppercase text-secondary">
                        <tr>
                            <th class="ps-3">Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Vị trí</th>
                            <th>Dịp tiệc</th>
                            <th>Ngày &amp; Ca</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <?php if (empty($recentBookings)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-1"></i>
                                    Chưa có đơn đặt bàn nào trong hệ thống.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentBookings as $b): ?>
                                <tr>
                                    <td class="ps-3 fw-bold">
                                        <a href="<?= BASE_URL ?>?action=admin-dat-ban-chi-tiet&id=<?= $b['id'] ?>" class="text-decoration-none text-primary" title="Xem chi tiết đơn">
                                            #<?= $b['id'] ?>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?= htmlspecialchars($b['khach_hang']) ?></div>
                                        <div class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($b['so_dien_thoai']) ?></div>
                                    </td>
                                    <td>
                                        <?php if (empty($b['ten_ban'])): ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle py-1 px-2" style="font-size: 0.75rem;">
                                                <i class="bi bi-clock-history me-1"></i>Chưa xếp bàn
                                            </span>
                                            <?php if (!empty($b['yeu_cau_phong_rieng'])): ?>
                                                <span class="badge ms-1" style="background:#f3e8ff; color:#7c3aed; border:1px solid #c4b5fd; font-size: 0.7rem;">
                                                    <i class="bi bi-door-open me-1"></i>VIP
                                                </span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-light text-dark border"><?= htmlspecialchars($b['ten_ban']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= !empty($b['ten_dip']) ? htmlspecialchars($b['ten_dip']) : '<span class="text-muted fst-italic" style="font-size: 0.8rem;">Không chỉ định</span>' ?>
                                    </td>
                                    <td>
                                        <div>
                                            <?= date('d/m/Y', strtotime($b['ngay_dat'])) ?>
                                            <?php if (!empty($b['gio_den'])): ?>
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle ms-1" style="font-size:0.7rem;">
                                                    <i class="bi bi-clock me-1"></i><?= date('H:i', strtotime($b['gio_den'])) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.7rem;">
                                            Ca <?= $b['ca'] == 'trua' ? 'Trưa' : 'Tối' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $badgeMap = [
                                            'cho_duyet'   => ['warning', 'Chờ duyệt'],
                                            'da_duyet'    => ['success', 'Đã duyệt'],
                                            'yeu_cau_huy' => ['danger', 'Yêu cầu hủy'],
                                            'tu_choi'     => ['danger', 'Từ chối'],
                                            'hoan_thanh'  => ['primary', 'Hoàn thành'],
                                            'huy'         => ['secondary', 'Đã hủy'],
                                        ];
                                        $st = $badgeMap[$b['trang_thai']] ?? ['secondary', $b['trang_thai']];
                                        ?>
                                        <span class="badge bg-<?= $st[0] ?>-subtle text-<?= $st[0] ?> border border-<?= $st[0] ?>-subtle">
                                            <?= $st[1] ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Cột phải: Danh sách bàn/phòng hiện tại -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="mb-0 fw-bold fs-6"><i class="bi bi-door-open me-2 text-success"></i>Bàn &amp; Phòng Trong Hệ Thống</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush small">
                    <?php foreach ($tables as $t): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-3">
                            <div>
                                <div class="fw-semibold text-dark">
                                    <i class="bi bi-<?= $t['loai'] == 'phong_rieng' ? 'star-fill text-warning' : 'circle-fill text-success' ?> me-1" style="font-size: 0.65rem;"></i>
                                    <?= htmlspecialchars($t['ten']) ?>
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    Sức chứa: <?= $t['suc_chua'] ?> người 
                                    <?= $t['phu_phi'] > 0 ? '| Phụ phí: ' . number_format($t['phu_phi']) . 'đ' : '' ?>
                                </div>
                            </div>
                            <span class="badge <?= $t['loai'] == 'phong_rieng' ? 'bg-primary-subtle text-primary' : 'bg-light text-secondary border' ?>">
                                <?= $t['loai'] == 'phong_rieng' ? 'Phòng VIP' : 'Bàn sảnh' ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

