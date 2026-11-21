<!-- views/admin/payments/index.php -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- ═══ HEADER TIÊU ĐỀ ═══ -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: var(--admin-text, #0F172A);">
            <i class="bi bi-cash-coin text-success me-2"></i>Quản Lý Giao Dịch &amp; Thanh Toán
        </h3>
        <p class="text-muted mb-0 small">Theo dõi dòng tiền cọc và thanh toán từ khách hàng, kiểm duyệt bill chuyển khoản ngân hàng và đối soát hóa đơn.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>?action=admin-thanh-toan" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1 shadow-sm">
            <i class="bi bi-arrow-clockwise"></i> Làm mới
        </a>
    </div>
</div>

<!-- ═══ METRICS CARDS ═══ -->
<div class="row g-3 mb-4">
    <!-- Card 1: Doanh thu thực thu -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Tổng doanh thu thực thu</span>
                    <div class="p-2 rounded-3 bg-success-subtle text-success">
                        <i class="bi bi-wallet2 fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-success"><?= number_format($stats['total_revenue'] ?? 0, 0, ',', '.') ?> đ</h3>
                <div class="small text-muted">Từ <?= $stats['success_count'] ?? 0 ?> giao dịch thành công</div>
            </div>
        </div>
    </div>

    <!-- Card 2: Doanh thu hôm nay -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Thu trong hôm nay</span>
                    <div class="p-2 rounded-3 bg-primary-subtle text-primary">
                        <i class="bi bi-calendar-check fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-primary"><?= number_format($stats['today_revenue'] ?? 0, 0, ',', '.') ?> đ</h3>
                <div class="small text-muted">Doanh thu phát sinh ngày <?= date('d/m') ?></div>
            </div>
        </div>
    </div>

    <!-- Card 3: Giao dịch chờ duyệt -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Giao dịch chờ duyệt</span>
                    <div class="p-2 rounded-3 bg-warning-subtle text-warning">
                        <i class="bi bi-hourglass-split fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-warning"><?= $stats['pending_confirm'] ?? 0 ?></h3>
                <div class="small text-muted">Chuyển khoản hoặc hẹn tại quầy</div>
            </div>
        </div>
    </div>

    <!-- Card 4: Tổng giao dịch -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Tổng số giao dịch</span>
                    <div class="p-2 rounded-3 bg-info-subtle text-info">
                        <i class="bi bi-receipt-cutoff fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1"><?= $stats['total_transactions'] ?? 0 ?></h3>
                <div class="small text-muted">Toàn bộ lịch sử hệ thống</div>
            </div>
        </div>
    </div>
</div>

<!-- ═══ BỘ LỌC TÌM KIẾM & PHƯƠNG THỨC ═══ -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
    <div class="card-body p-3">
        <form method="GET" action="<?= BASE_URL ?>" class="row g-2 align-items-center">
            <input type="hidden" name="action" value="admin-thanh-toan">

            <div class="col-md-4 col-12">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" class="form-control border-start-0" 
                           placeholder="Mã GD, Họ tên khách, SĐT hoặc #ID đơn...">
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <select name="status" class="form-select form-select-sm">
                    <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>-- Tất cả trạng thái --</option>
                    <option value="thanh_cong" <?= $status === 'thanh_cong' ? 'selected' : '' ?>>Thành công / Đã thu</option>
                    <option value="cho_xac_nhan" <?= $status === 'cho_xac_nhan' ? 'selected' : '' ?>>Chờ xác nhận</option>
                    <option value="that_bai" <?= $status === 'that_bai' ? 'selected' : '' ?>>Thất bại / Hủy</option>
                </select>
            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <select name="method" class="form-select form-select-sm">
                    <option value="all" <?= $method === 'all' ? 'selected' : '' ?>>-- Tất cả phương thức --</option>
                    <option value="chuyen_khoan" <?= $method === 'chuyen_khoan' ? 'selected' : '' ?>>Chuyển khoản VietQR</option>
                    <option value="vnpay" <?= $method === 'vnpay' ? 'selected' : '' ?>>Cổng VNPAY</option>
                    <option value="momo" <?= $method === 'momo' ? 'selected' : '' ?>>Ví MoMo</option>
                    <option value="tien_mat" <?= $method === 'tien_mat' ? 'selected' : '' ?>>Tiền mặt tại quầy</option>
                </select>
            </div>

            <div class="col-md-2 col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm px-3 w-100">
                    <i class="bi bi-funnel-fill me-1"></i>Lọc
                </button>
                <?php if (!empty($search) || $status !== 'all' || $method !== 'all' || !empty($date)): ?>
                    <a href="<?= BASE_URL ?>?action=admin-thanh-toan" class="btn btn-outline-secondary btn-sm px-2" title="Xóa bộ lọc">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- ═══ BẢNG DANH SÁCH GIAO DỊCH ═══ -->
<div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-white py-3 px-4 border-0 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-bold" style="font-size: 1rem;">
            Danh Sách Giao Dịch Thanh Toán (<?= count($payments) ?> kết quả)
        </h5>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase small text-muted">
                <tr>
                    <th class="ps-4">Mã Giao Dịch</th>
                    <th>Đơn Tiệc</th>
                    <th>Khách Hàng</th>
                    <th>Hình Thức</th>
                    <th class="text-end">Số Tiền Thu</th>
                    <th>Thời Gian</th>
                    <th class="text-center">Trạng Thái</th>
                    <th class="text-end pe-4">Thao Tác</th>
                </tr>
            </thead>
            <tbody class="small">
                <?php if (empty($payments)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                            <h6 class="text-muted fw-semibold">Không tìm thấy giao dịch nào</h6>
                            <p class="text-muted small mb-0">Chưa có dữ liệu thanh toán phù hợp với bộ lọc hiện tại.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($payments as $p): ?>
                        <tr>
                            <!-- Mã GD -->
                            <td class="ps-4 fw-bold font-monospace text-primary">
                                <?= htmlspecialchars($p['ma_giao_dich']) ?>
                                <?php if (!empty($p['anh_bien_lai'])): ?>
                                    <a href="<?= BASE_ASSETS_UPLOADS . htmlspecialchars($p['anh_bien_lai']) ?>" target="_blank" class="badge bg-primary-subtle text-primary border border-primary-subtle ms-1" title="Xem ảnh bill">
                                        <i class="bi bi-paperclip"></i> Bill
                                    </a>
                                <?php endif; ?>
                            </td>

                            <!-- Đơn tiệc -->
                            <td>
                                <a href="<?= BASE_URL ?>?action=admin-dat-ban-chi-tiet&id=<?= $p['datban_id'] ?>" class="fw-bold text-decoration-none">
                                    #DB<?= $p['datban_id'] ?>
                                </a>
                                <div class="text-muted" style="font-size:0.75rem;">
                                    <?= !empty($p['ten_ban']) ? htmlspecialchars($p['ten_ban']) : 'Chưa xếp bàn' ?>
                                </div>
                            </td>

                            <!-- Khách hàng -->
                            <td>
                                <div class="fw-semibold text-dark"><?= htmlspecialchars($p['ten_khach'] ?? 'Khách') ?></div>
                                <div class="text-muted" style="font-size:0.75rem;"><?= htmlspecialchars($p['sdt_khach'] ?? '') ?></div>
                            </td>

                            <!-- Phương thức & loại -->
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <?php
                                    $methodIcons = [
                                        'chuyen_khoan' => '<i class="bi bi-qr-code text-success me-1"></i>VietQR',
                                        'vnpay'        => '<i class="bi bi-credit-card text-primary me-1"></i>VNPAY',
                                        'momo'         => '<i class="bi bi-wallet2 text-danger me-1"></i>MoMo',
                                        'tien_mat'     => '<i class="bi bi-cash-stack text-secondary me-1"></i>Tiền mặt',
                                    ];
                                    echo $methodIcons[$p['phuong_thuc']] ?? $p['phuong_thuc'];
                                    ?>
                                </span>
                                <div class="text-muted" style="font-size:0.72rem; margin-top:2px;">
                                    <?= $p['loai_thanh_toan'] === 'dat_coc' ? 'Đặt cọc tiệc' : 'Toàn bộ 100%' ?>
                                </div>
                            </td>

                            <!-- Số tiền -->
                            <td class="text-end fw-bold text-success fs-6">
                                <?= number_format($p['so_tien'], 0, ',', '.') ?> đ
                            </td>

                            <!-- Thời gian -->
                            <td class="text-muted">
                                <div><?= date('d/m/Y', strtotime($p['ngay_thanh_toan'])) ?></div>
                                <div style="font-size:0.75rem;"><?= date('H:i:s', strtotime($p['ngay_thanh_toan'])) ?></div>
                            </td>

                            <!-- Trạng thái -->
                            <td class="text-center">
                                <?php if ($p['trang_thai'] === 'thanh_cong'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                        <i class="bi bi-check-circle me-1"></i>Thành công
                                    </span>
                                <?php elseif ($p['trang_thai'] === 'cho_xac_nhan'): ?>
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">
                                        <i class="bi bi-hourglass-split me-1"></i>Chờ xác nhận
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1">
                                        <?= $p['trang_thai'] ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Thao tác -->
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-1">
                                    <a href="<?= BASE_URL ?>?action=hoa-don&payment_id=<?= $p['id'] ?>" target="_blank" class="btn btn-outline-secondary btn-sm py-1 px-2" title="Xem hóa đơn">
                                        <i class="bi bi-printer"></i>
                                    </a>

                                    <?php if ($p['trang_thai'] === 'cho_xac_nhan'): ?>
                                        <form method="POST" action="<?= BASE_URL ?>?action=admin-thanh-toan-cap-nhat" style="display:inline;" onsubmit="return confirm('Xác nhận giao dịch #<?= $p['ma_giao_dich'] ?> đã nhận đủ tiền?');">
                                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                            <input type="hidden" name="trang_thai" value="thanh_cong">
                                            <button type="submit" class="btn btn-success btn-sm py-1 px-2 fw-semibold" title="Xác nhận duyệt tiền">
                                                <i class="bi bi-check-lg"></i> Duyệt
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <a href="<?= BASE_URL ?>?action=admin-dat-ban-chi-tiet&id=<?= $p['datban_id'] ?>" class="btn btn-outline-primary btn-sm py-1 px-2" title="Chi tiết đơn">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
