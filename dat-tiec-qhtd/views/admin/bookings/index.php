<!-- views/admin/bookings/index.php -->
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

<!-- ═══ HEADER TIÊU ĐỀ & NÚT THAO TÁC ═══ -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: var(--admin-text, #0F172A);">
            <i class="bi bi-calendar2-check-fill text-primary me-2"></i>Quản Lý Duyệt Đặt Bàn
        </h3>
        <p class="text-muted mb-0 small">Theo dõi đơn tiệc gia đình, tiếp nhận yêu cầu, kiểm tra lịch bàn và duyệt đặt chỗ.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>?action=admin-dat-ban" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1 shadow-sm">
            <i class="bi bi-arrow-clockwise"></i> Làm mới
        </a>
    </div>
</div>

<!-- ═══ TABS TRẠNG THÁI LỌC ═══ -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
    <div class="card-body p-2 p-md-3">
        <div class="d-flex flex-wrap gap-2">
            <?php
            $tabItems = [
                'all'         => ['label' => 'Tất cả',       'icon' => 'bi-collection-fill',         'class' => 'secondary'],
                'cho_duyet'   => ['label' => 'Chờ duyệt',    'icon' => 'bi-hourglass-split',         'class' => 'warning'],
                'da_duyet'    => ['label' => 'Đã duyệt',     'icon' => 'bi-check-circle-fill',       'class' => 'success'],
                'yeu_cau_huy' => ['label' => 'Yêu cầu hủy',  'icon' => 'bi-exclamation-octagon-fill', 'class' => 'danger'],
                'hoan_thanh'  => ['label' => 'Hoàn thành',   'icon' => 'bi-patch-check-fill',        'class' => 'primary'],
                'tu_choi'     => ['label' => 'Từ chối',      'icon' => 'bi-x-circle-fill',           'class' => 'secondary'],
                'huy'         => ['label' => 'Đã hủy',       'icon' => 'bi-slash-circle-fill',       'class' => 'dark'],
            ];
            foreach ($tabItems as $key => $tab):
                $isActive = ($status === $key);
                $queryUrl = BASE_URL . '?action=admin-dat-ban&status=' . $key . (!empty($search) ? '&q=' . urlencode($search) : '') . (!empty($date) ? '&date=' . urlencode($date) : '');
            ?>
                <a href="<?= $queryUrl ?>" 
                   class="btn btn-sm <?= $isActive ? 'btn-' . $tab['class'] : 'btn-light border' ?> d-flex align-items-center gap-2 px-3 py-2"
                   style="border-radius: 8px; font-weight: 500;">
                    <i class="bi <?= $tab['icon'] ?>"></i>
                    <span><?= $tab['label'] ?></span>
                    <span class="badge <?= $isActive ? 'bg-light text-dark' : 'bg-' . $tab['class'] . '-subtle text-' . $tab['class'] ?> rounded-pill ms-1">
                        <?= $counts[$key] ?? 0 ?>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ═══ BỘ LỌC TÌM KIẾM & NGÀY ═══ -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
    <div class="card-body p-3">
        <form method="GET" action="<?= BASE_URL ?>" class="row g-2 align-items-center">
            <input type="hidden" name="action" value="admin-dat-ban">
            <input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>">

            <div class="col-md-5 col-12">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" 
                           class="form-control border-start-0" 
                           placeholder="Tìm theo Họ tên khách, SĐT hoặc Mã đơn (#ID)...">
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-12">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="bi bi-calendar-event"></i>
                    </span>
                    <input type="date" name="date" value="<?= htmlspecialchars($date) ?>" 
                           class="form-control border-start-0">
                </div>
            </div>

            <div class="col-md-4 col-sm-6 col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm px-3 d-flex align-items-center gap-1">
                    <i class="bi bi-funnel-fill"></i> Lọc dữ liệu
                </button>
                <?php if (!empty($search) || !empty($date) || $status !== 'all'): ?>
                    <a href="<?= BASE_URL ?>?action=admin-dat-ban" class="btn btn-outline-secondary btn-sm px-2" title="Xóa bộ lọc">
                        <i class="bi bi-x-lg"></i> Xóa lọc
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- ═══ DANH SÁCH ĐƠN ĐẶT BÀN ═══ -->
<div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-white py-3 px-4 border-0 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-bold" style="font-size: 1rem;">
            Danh Sách Đơn Đặt Bàn (<?= count($bookings) ?> kết quả)
        </h5>
        <?php if (!empty($date)): ?>
            <span class="badge bg-info-subtle text-info border border-info-subtle">
                <i class="bi bi-calendar-check me-1"></i> Ngày: <?= date('d/m/Y', strtotime($date)) ?>
            </span>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase small text-muted" style="letter-spacing: 0.5px;">
                <tr>
                    <th class="ps-4" style="width: 70px;">Mã Đơn</th>
                    <th>Khách Hàng</th>
                    <th>Không Gian / Dịp Tiệc</th>
                    <th>Ngày &amp; Ca Tiệc</th>
                    <th class="text-center">Số Khách</th>
                    <th class="text-end">Tổng Tiền</th>
                    <th class="text-center">Trạng Thái</th>
                    <th class="text-end pe-4">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bookings)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="py-4">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                <h6 class="text-muted fw-semibold">Không tìm thấy đơn đặt bàn nào</h6>
                                <p class="text-muted small mb-0">Vui lòng thử điều chỉnh bộ lọc hoặc từ khóa tìm kiếm.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($bookings as $b): ?>
                        <tr>
                            <!-- Mã đơn -->
                            <td class="ps-4 fw-bold text-secondary">
                                #<?= $b['id'] ?>
                            </td>

                            <!-- Khách hàng -->
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($b['khach_hang']) ?></div>
                                <div class="small text-muted d-flex align-items-center gap-1 mt-1">
                                    <i class="bi bi-telephone-fill text-secondary" style="font-size: 0.75rem;"></i>
                                    <a href="tel:<?= htmlspecialchars($b['so_dien_thoai']) ?>" class="text-decoration-none text-muted">
                                        <?= htmlspecialchars($b['so_dien_thoai']) ?>
                                    </a>
                                </div>
                            </td>

                            <!-- Bàn phòng & Dịp tiệc -->
                            <td>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <?php if (empty($b['ten_ban'])): ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle py-1 px-2" style="font-size: 0.75rem;">
                                            <i class="bi bi-clock-history me-1"></i>Chưa xếp bàn
                                        </span>
                                    <?php else: ?>
                                        <span class="fw-semibold text-dark"><?= htmlspecialchars($b['ten_ban']) ?></span>
                                        <?php if ($b['loai_ban'] === 'phong_rieng'): ?>
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle small py-0 px-2" style="font-size: 0.7rem;">VIP</span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-secondary border small py-0 px-2" style="font-size: 0.7rem;">Thường</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if (!empty($b['yeu_cau_phong_rieng'])): ?>
                                        <span class="badge bg-purple-subtle border small py-0 px-2" style="font-size: 0.7rem; background:#f3e8ff; color:#7c3aed; border-color:#c4b5fd !important;">
                                            <i class="bi bi-door-open me-1"></i>Yêu cầu phòng riêng
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="small text-muted mt-1">
                                    <i class="bi bi-balloon-heart text-danger me-1"></i>
                                    <?= !empty($b['ten_dip']) ? htmlspecialchars($b['ten_dip']) : '<span class="fst-italic text-muted">Không chỉ định</span>' ?>
                                    <?php if (!empty($b['ten_set'])): ?>
                                        <span class="text-secondary ms-1">| <i class="bi bi-box2-heart text-primary me-1"></i><?= htmlspecialchars($b['ten_set']) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($b['so_mon_ngoai']) && $b['so_mon_ngoai'] > 0): ?>
                                        <span class="badge bg-warning-subtle text-dark border border-warning-subtle ms-1" style="font-size: 0.7rem;">
                                            <i class="bi bi-basket2-fill text-warning me-1"></i>+<?= $b['so_mon_ngoai'] ?> món ngoài
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Ngày & Giờ đến -->
                            <td>
                                <div class="fw-semibold text-primary">
                                    <i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y', strtotime($b['ngay_dat'])) ?>
                                </div>
                                <div class="mt-1 d-flex align-items-center gap-1 flex-wrap">
                                    <?php if (!empty($b['gio_den'])): ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-bold py-1 px-2">
                                            <i class="bi bi-clock-fill me-1"></i><?= date('H:i', strtotime($b['gio_den'])) ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($b['ca'] === 'trua'): ?>
                                        <span class="badge bg-warning-subtle text-dark border border-warning-subtle py-1 px-2" style="font-size:0.75rem;">
                                            ☀️ Trưa
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-dark-subtle text-dark border border-dark-subtle py-1 px-2" style="font-size:0.75rem;">
                                            🌙 Tối
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Số người -->
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-2 py-1 fs-6 fw-semibold">
                                    <i class="bi bi-people-fill me-1 text-secondary"></i><?= $b['so_nguoi'] ?>
                                </span>
                            </td>

                            <!-- Tổng tiền & Thanh toán -->
                            <td class="text-end">
                                <div class="fw-bold fs-6" style="color: #C4603C;"><?= number_format($b['tong_tien'], 0, ',', '.') ?> đ</div>
                                <?php if (($b['trang_thai_thanh_toan'] ?? '') === 'da_thanh_toan'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size:0.7rem;">
                                        <i class="bi bi-check-circle me-1"></i>Đã thanh toán đủ
                                    </span>
                                <?php elseif (($b['trang_thai_thanh_toan'] ?? '') === 'da_dat_coc'): ?>
                                    <span class="badge bg-info-subtle text-info border border-info-subtle" style="font-size:0.7rem;" title="Đã cọc: <?= number_format($b['so_tien_da_coc'] ?? 0, 0, ',', '.') ?> đ">
                                        <i class="bi bi-credit-card me-1"></i>Đã cọc <?= number_format($b['so_tien_da_coc'] ?? 0, 0, ',', '.') ?>đ
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size:0.7rem;">
                                        Chưa thanh toán
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Trạng thái -->
                            <td class="text-center">
                                <?php if ($b['trang_thai'] === 'cho_duyet'): ?>
                                    <span class="badge bg-warning text-dark px-3 py-2" style="font-size: 0.8rem;">
                                        <i class="bi bi-hourglass-split me-1"></i> Chờ duyệt
                                    </span>
                                <?php elseif ($b['trang_thai'] === 'da_duyet'): ?>
                                    <span class="badge bg-success px-3 py-2" style="font-size: 0.8rem;">
                                        <i class="bi bi-check-circle me-1"></i> Đã duyệt
                                    </span>
                                <?php elseif ($b['trang_thai'] === 'yeu_cau_huy'): ?>
                                    <span class="badge bg-danger px-3 py-2" style="font-size: 0.8rem;">
                                        <i class="bi bi-exclamation-octagon-fill me-1"></i> Yêu cầu hủy
                                    </span>
                                    <?php if (!empty($b['ly_do_huy'])): ?>
                                        <div class="text-danger small mt-1 text-wrap text-start mx-auto" style="max-width: 170px; font-size: 0.72rem;">
                                            <i class="bi bi-chat-quote-fill me-1"></i>"<?= htmlspecialchars($b['ly_do_huy']) ?>"
                                        </div>
                                    <?php endif; ?>
                                <?php elseif ($b['trang_thai'] === 'hoan_thanh'): ?>
                                    <span class="badge bg-primary px-3 py-2" style="font-size: 0.8rem;">
                                        <i class="bi bi-patch-check me-1"></i> Hoàn thành
                                    </span>
                                <?php elseif ($b['trang_thai'] === 'tu_choi'): ?>
                                    <span class="badge bg-secondary px-3 py-2" style="font-size: 0.8rem;">
                                        <i class="bi bi-x-circle me-1"></i> Từ chối
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-dark px-3 py-2" style="font-size: 0.8rem;">
                                        <i class="bi bi-slash-circle me-1"></i> Đã hủy
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Thao tác -->
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-1 align-items-center">
                                    <!-- Xem chi tiết -->
                                    <a href="<?= BASE_URL ?>?action=admin-dat-ban-chi-tiet&id=<?= $b['id'] ?>" 
                                       class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1"
                                       title="Xem chi tiết & Quản lý">
                                        <i class="bi bi-eye"></i> Chi tiết
                                    </a>

                                    <!-- Nút duyệt hủy cho đơn yêu cầu hủy -->
                                    <?php if ($b['trang_thai'] === 'yeu_cau_huy'): ?>
                                        <form method="POST" action="<?= BASE_URL ?>?action=admin-dat-ban-cap-nhat" class="d-inline"
                                              onsubmit="return confirm('Xác nhận ĐỒNG Ý HỦY đơn đặt bàn #<?= $b['id'] ?> của khách <?= htmlspecialchars($b['khach_hang']) ?>?');">
                                            <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                            <input type="hidden" name="trang_thai" value="huy">
                                            <input type="hidden" name="redirect" value="list">
                                            <input type="hidden" name="ghi_chu" value="<?= htmlspecialchars($b['ghi_chu'] ?? '') ?>">
                                            <button type="submit" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1" title="Đồng ý hủy đơn">
                                                <i class="bi bi-check-lg"></i> Duyệt hủy
                                            </button>
                                        </form>
                                        <form method="POST" action="<?= BASE_URL ?>?action=admin-dat-ban-cap-nhat" class="d-inline"
                                              onsubmit="return confirm('Từ chối yêu cầu hủy và giữ nguyên đơn đặt bàn #<?= $b['id'] ?>?');">
                                            <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                            <input type="hidden" name="trang_thai" value="<?= !empty($b['banphong_id']) ? 'da_duyet' : 'cho_duyet' ?>">
                                            <input type="hidden" name="redirect" value="list">
                                            <input type="hidden" name="ghi_chu" value="<?= htmlspecialchars($b['ghi_chu'] ?? '') ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" title="Bác bỏ yêu cầu hủy">
                                                <i class="bi bi-arrow-counterclockwise"></i> Giữ đơn
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <!-- Nút xếp bàn & duyệt → đến trang chi tiết -->
                                    <?php if ($b['trang_thai'] === 'cho_duyet'): ?>
                                        <a href="<?= BASE_URL ?>?action=admin-dat-ban-chi-tiet&id=<?= $b['id'] ?>"
                                           class="btn btn-sm btn-warning d-inline-flex align-items-center gap-1"
                                           title="Xếp bàn & Duyệt">
                                            <i class="bi bi-layout-text-sidebar"></i> Xếp bàn
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
