<!-- views/admin/bookings/detail.php -->
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

<!-- ═══ THANH ĐIỀU HƯỚNG & TIÊU ĐỀ ═══ -->
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL ?>?action=admin-dat-ban" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <h4 class="fw-bold mb-0">Đơn Đặt Bàn #<?= $booking['id'] ?></h4>
                <?php if ($booking['trang_thai'] === 'cho_duyet'): ?>
                    <span class="badge bg-warning text-dark px-3 py-1"><i class="bi bi-hourglass-split me-1"></i>Chờ duyệt</span>
                <?php elseif ($booking['trang_thai'] === 'da_duyet'): ?>
                    <span class="badge bg-success px-3 py-1"><i class="bi bi-check-circle me-1"></i>Đã duyệt</span>
                <?php elseif ($booking['trang_thai'] === 'hoan_thanh'): ?>
                    <span class="badge bg-primary px-3 py-1"><i class="bi bi-patch-check me-1"></i>Hoàn thành</span>
                <?php elseif ($booking['trang_thai'] === 'tu_choi'): ?>
                    <span class="badge bg-danger px-3 py-1"><i class="bi bi-x-circle me-1"></i>Từ chối</span>
                <?php else: ?>
                    <span class="badge bg-secondary px-3 py-1"><i class="bi bi-slash-circle me-1"></i>Đã hủy</span>
                <?php endif; ?>

                <?php if (!empty($booking['yeu_cau_phong_rieng'])): ?>
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">
                        <i class="bi bi-door-closed-fill me-1"></i>Yêu cầu phòng VIP
                    </span>
                <?php endif; ?>

                <?php if (empty($booking['banphong_id'])): ?>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                        <i class="bi bi-exclamation-triangle me-1"></i>Chưa xếp bàn
                    </span>
                <?php endif; ?>
            </div>
            <span class="text-muted small">Tạo lúc: <?= date('d/m/Y H:i', strtotime($booking['ngay_tao'])) ?></span>
        </div>
    </div>
</div>

<!-- ═══ CẢNH BÁO YÊU CẦU HỦY ĐƠN TỪ KHÁCH ═══ -->
<?php if ($booking['trang_thai'] === 'yeu_cau_huy'): ?>
    <div class="alert alert-danger border-danger shadow-sm mb-4 rounded-3 p-3" role="alert">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-start gap-3">
                <i class="bi bi-exclamation-octagon-fill fs-2 text-danger"></i>
                <div>
                    <h6 class="alert-heading fw-bold mb-1 text-danger">Khách hàng gửi yêu cầu HỦY ĐƠN ĐẶT BÀN này!</h6>
                    <p class="mb-0 small text-dark">
                        <strong>Lý do xin hủy:</strong> <em>"<?= htmlspecialchars($booking['ly_do_huy'] ?? 'Khách không cung cấp lý do cụ thể') ?>"</em>
                    </p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <form method="POST" action="<?= BASE_URL ?>?action=admin-dat-ban-cap-nhat" onsubmit="return confirm('Bạn có chắc chắn muốn ĐỒNG Ý HỦY đơn đặt bàn này?');">
                    <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                    <input type="hidden" name="trang_thai" value="huy">
                    <input type="hidden" name="redirect" value="detail">
                    <input type="hidden" name="ghi_chu" value="<?= htmlspecialchars($booking['ghi_chu'] ?? '') ?>">
                    <button type="submit" class="btn btn-danger fw-bold shadow-sm">
                        <i class="bi bi-check-lg me-1"></i>Đồng ý duyệt hủy
                    </button>
                </form>
                <form method="POST" action="<?= BASE_URL ?>?action=admin-dat-ban-cap-nhat" onsubmit="return confirm('Từ chối yêu cầu hủy và giữ nguyên đơn?');">
                    <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                    <input type="hidden" name="trang_thai" value="<?= !empty($booking['banphong_id']) ? 'da_duyet' : 'cho_duyet' ?>">
                    <input type="hidden" name="redirect" value="detail">
                    <input type="hidden" name="ghi_chu" value="<?= htmlspecialchars($booking['ghi_chu'] ?? '') ?>">
                    <button type="submit" class="btn btn-outline-secondary fw-semibold">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Bác bỏ yêu cầu
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- ═══ CẢNH BÁO TRÙNG LỊCH BÀN (NẾU CÓ) ═══ -->
<?php if ($conflictBooking): ?>
    <div class="alert alert-warning border-warning shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-start gap-3">
            <i class="bi bi-exclamation-triangle-fill fs-3 text-warning mt-1"></i>
            <div>
                <h6 class="alert-heading fw-bold mb-1">Cảnh Báo Trùng Lịch Bàn!</h6>
                <p class="mb-0 small">
                    Bàn/phòng <strong><?= htmlspecialchars($booking['ten_ban'] ?? '') ?></strong> vào ngày
                    <strong><?= date('d/m/Y', strtotime($booking['ngay_dat'])) ?></strong>
                    (Ca <?= $booking['ca'] === 'trua' ? 'Trưa' : 'Tối' ?>)
                    hiện <strong>ĐÃ ĐƯỢC DUYỆT</strong> cho đơn khác (Mã đơn:
                    <a href="<?= BASE_URL ?>?action=admin-dat-ban-chi-tiet&id=<?= $conflictBooking['id'] ?>" class="alert-link fw-bold">#<?= $conflictBooking['id'] ?></a>).
                    Vui lòng xếp bàn khác cho khách hoặc đổi ca trước khi duyệt.
                </p>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- ═══ CỘT TRÁI: THÔNG TIN ĐẶT BÀN ═══ -->
    <div class="col-lg-8">

        <!-- 1. THÔNG TIN KHÁCH HÀNG -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 px-4 border-0">
                <h5 class="card-title mb-0 fw-bold fs-6">
                    <i class="bi bi-person-lines-fill text-primary me-2"></i>Thông Tin Khách Hàng &amp; Tiệc Đặt
                </h5>
            </div>
            <div class="card-body px-4 pb-4 pt-2">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="text-muted small text-uppercase fw-semibold mb-1">Khách hàng</div>
                        <div class="fw-bold fs-6 text-dark"><?= htmlspecialchars($booking['khach_hang']) ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small text-uppercase fw-semibold mb-1">Số điện thoại</div>
                        <div class="fw-bold fs-6">
                            <a href="tel:<?= htmlspecialchars($booking['so_dien_thoai']) ?>" class="text-primary text-decoration-none">
                                <i class="bi bi-telephone-outbound me-1"></i><?= htmlspecialchars($booking['so_dien_thoai']) ?>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small text-uppercase fw-semibold mb-1">Email</div>
                        <div class="text-secondary"><?= htmlspecialchars($booking['email'] ?? 'Không có') ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small text-uppercase fw-semibold mb-1">Loại dịp tiệc</div>
                        <?php if (!empty($booking['ten_dip'])): ?>
                            <div class="fw-semibold text-danger">
                                <i class="bi bi-balloon-heart me-1"></i><?= htmlspecialchars($booking['ten_dip']) ?>
                            </div>
                        <?php else: ?>
                            <div class="text-muted fst-italic small">Khách không chỉ định dịp cụ thể</div>
                        <?php endif; ?>
                    </div>

                    <div class="col-12"><hr class="my-1 opacity-25"></div>

                    <!-- Yêu cầu không gian -->
                    <div class="col-sm-6">
                        <div class="text-muted small text-uppercase fw-semibold mb-1">Yêu cầu không gian</div>
                        <?php if (!empty($booking['yeu_cau_phong_rieng'])): ?>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-warning text-dark px-3 py-2 fs-6">
                                    <i class="bi bi-door-closed-fill me-1"></i> Có yêu cầu Phòng Riêng (VIP)
                                </span>
                            </div>
                            <div class="small text-muted mt-1">Khách mong muốn không gian riêng tư cho tiệc</div>
                        <?php else: ?>
                            <span class="badge bg-light text-secondary border px-3 py-2">
                                <i class="bi bi-people me-1"></i> Bàn sảnh chung (không yêu cầu phòng riêng)
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Bàn/Phòng được xếp -->
                    <div class="col-sm-6">
                        <div class="text-muted small text-uppercase fw-semibold mb-1">Bàn / Phòng được xếp</div>
                        <?php if (!empty($booking['ten_ban'])): ?>
                            <div class="fw-bold fs-6 text-dark d-flex align-items-center gap-2 flex-wrap">
                                <?= htmlspecialchars($booking['ten_ban']) ?>
                                <?php if (!empty($booking['so_luong_ban_ghep']) && $booking['so_luong_ban_ghep'] > 1): ?>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                        <i class="bi bi-intersect me-1"></i>Ghép <?= $booking['so_luong_ban_ghep'] ?> bàn sảnh
                                    </span>
                                <?php elseif ($booking['loai_ban'] === 'phong_rieng' || $booking['loai_ban'] === 'phong_vip'): ?>
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">VIP <?= $booking['phu_phi'] > 0 ? '(+' . number_format($booking['phu_phi'], 0, ',', '.') . ' đ)' : '' ?></span>
                                <?php else: ?>
                                    <span class="badge bg-light text-secondary border">Bàn thường</span>
                                <?php endif; ?>
                            </div>
                            <div class="small text-muted mt-1">
                                <i class="bi bi-people-fill text-secondary me-1"></i>Tổng sức chứa: <strong><?= $booking['suc_chua'] ?> người</strong>
                                <?php if ($booking['so_nguoi'] > $booking['suc_chua']): ?>
                                    <span class="text-danger fw-semibold ms-1">(Thiếu <?= $booking['so_nguoi'] - $booking['suc_chua'] ?> chỗ - hãy ghép thêm bàn)</span>
                                <?php else: ?>
                                    <span class="text-success fw-semibold ms-1">(Đủ chỗ đón <?= $booking['so_nguoi'] ?> khách)</span>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="p-2 rounded border border-dashed border-warning bg-warning-subtle">
                                <span class="text-warning fw-semibold small">
                                    <i class="bi bi-clock-history me-1"></i>Chưa được xếp bàn — Admin cần xếp bàn hoặc ghép bàn ở form bên phải
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-sm-6">
                        <div class="text-muted small text-uppercase fw-semibold mb-1">Thời gian tổ chức</div>
                        <div class="fw-bold fs-6 text-primary">
                            <i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y', strtotime($booking['ngay_dat'])) ?>
                            <?php if (!empty($booking['gio_den'])): ?>
                                <span class="badge bg-danger ms-2 fs-6 py-1 px-2"><i class="bi bi-clock me-1"></i><?= date('H:i', strtotime($booking['gio_den'])) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="small fw-semibold mt-1">
                            <?php if ($booking['ca'] === 'trua'): ?>
                                <span class="badge bg-warning-subtle text-dark border border-warning-subtle">☀️ Ca Trưa (11:00 – 14:00)</span>
                            <?php else: ?>
                                <span class="badge bg-dark-subtle text-dark border border-dark-subtle">🌙 Ca Tối (17:00 – 21:30)</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small text-uppercase fw-semibold mb-1">Số lượng khách</div>
                        <div class="fw-bold fs-6 text-dark d-flex align-items-center gap-2">
                            <?= $booking['so_nguoi'] ?> người
                            <?php if (!empty($booking['suc_chua']) && $booking['so_nguoi'] > $booking['suc_chua']): ?>
                                <span class="badge bg-danger"><i class="bi bi-exclamation-triangle"></i> Vượt sức chứa</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small text-uppercase fw-semibold mb-1">Yêu cầu / Ghi chú của khách</div>
                        <div class="p-3 bg-light rounded border text-secondary small fst-italic">
                            <?= !empty($booking['ghi_chu']) ? nl2br(htmlspecialchars($booking['ghi_chu'])) : 'Không có yêu cầu đặc biệt nào từ khách hàng.' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. THỰC ĐƠN & MÓN GỌI THÊM -->
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-white py-3 px-4 border-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold fs-6">
                    <i class="bi bi-egg-fried text-primary me-2"></i>Thực Đơn &amp; Món Ăn Kèm
                </h5>
                <?php if (!empty($booking['ten_set'])): ?>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                        <i class="bi bi-box2-heart me-1"></i>Set: <?= htmlspecialchars($booking['ten_set']) ?>
                    </span>
                <?php endif; ?>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light small text-muted text-uppercase">
                        <tr>
                            <th class="ps-4">Hạng mục</th>
                            <th>Phân loại</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-end">Đơn giá</th>
                            <th class="text-end pe-4">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($booking['ten_set'])): ?>
                            <tr>
                                <td class="ps-4">
                                    <strong class="text-dark"><?= htmlspecialchars($booking['ten_set']) ?></strong>
                                    <div class="small text-muted">Set tiệc trọn gói theo dịp</div>
                                </td>
                                <td><span class="badge bg-primary-subtle text-primary">Set Menu</span></td>
                                <td class="text-center fw-bold">1 bàn</td>
                                <td class="text-end"><?= number_format($booking['gia_set_luc_dat'] ?? 0, 0, ',', '.') ?> đ</td>
                                <td class="text-end pe-4 fw-bold"><?= number_format($booking['gia_set_luc_dat'] ?? 0, 0, ',', '.') ?> đ</td>
                            </tr>
                        <?php endif; ?>

                        <?php if (!empty($booking['ten_ban']) && $booking['loai_ban'] === 'phong_rieng' && $booking['phu_phi'] > 0): ?>
                            <tr>
                                <td class="ps-4">
                                    <strong class="text-dark">Phụ phí phòng VIP (<?= htmlspecialchars($booking['ten_ban']) ?>)</strong>
                                    <div class="small text-muted">Phí không gian riêng tư</div>
                                </td>
                                <td><span class="badge bg-warning-subtle text-warning">Phụ phí</span></td>
                                <td class="text-center fw-bold">1</td>
                                <td class="text-end"><?= number_format($booking['phu_phi'], 0, ',', '.') ?> đ</td>
                                <td class="text-end pe-4 fw-bold"><?= number_format($booking['phu_phi'], 0, ',', '.') ?> đ</td>
                            </tr>
                        <?php endif; ?>

                        <?php if (!empty($extraDishes)): ?>
                            <?php foreach ($extraDishes as $dish): ?>
                                <tr>
                                    <td class="ps-4"><strong class="text-dark"><?= htmlspecialchars($dish['ten_mon']) ?></strong></td>
                                    <td><span class="badge bg-light text-secondary border"><?= htmlspecialchars($dish['nhom_mon'] ?? 'Món lẻ') ?></span></td>
                                    <td class="text-center fw-bold"><?= $dish['so_luong'] ?></td>
                                    <td class="text-end"><?= number_format($dish['don_gia'], 0, ',', '.') ?> đ</td>
                                    <td class="text-end pe-4 fw-bold"><?= number_format($dish['don_gia'] * $dish['so_luong'], 0, ',', '.') ?> đ</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if (empty($booking['ten_set']) && empty($extraDishes) && (empty($booking['ten_ban']) || $booking['loai_ban'] !== 'phong_rieng' || $booking['phu_phi'] <= 0)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4 small">
                                    <i class="bi bi-info-circle me-1"></i>Khách chỉ đặt chỗ, gọi món trực tiếp tại nhà hàng.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold ps-4 text-uppercase">Tổng Cộng Tạm Tính:</td>
                            <td class="text-end pe-4 fw-bold fs-5" style="color: #C4603C;">
                                <?= number_format($booking['tong_tien'], 0, ',', '.') ?> đ
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- ═══ CỘT PHẢI: FORM XẾP BÀN & CẬP NHẬT TRẠNG THÁI ═══ -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm sticky-top" style="border-radius: 12px; top: 90px;">
            <div class="card-header bg-white py-3 px-4 border-0">
                <h5 class="card-title mb-0 fw-bold fs-6">
                    <i class="bi bi-gear-fill text-primary me-2"></i>Xếp Bàn &amp; Xử Lý Đơn
                </h5>
            </div>
            <div class="card-body px-4 pt-2 pb-4">
                <form method="POST" action="<?= BASE_URL ?>?action=admin-dat-ban-cap-nhat">
                    <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                    <input type="hidden" name="redirect" value="detail">

                    <!-- XẾP BÀN & GHÉP BÀN (Admin chọn) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted text-uppercase d-flex justify-content-between align-items-center mb-2">
                            <span><i class="bi bi-door-open-fill me-1 text-primary"></i>Xếp Bàn / Ghép Bàn</span>
                            <?php if (!empty($booking['yeu_cau_phong_rieng'])): ?>
                                <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i>Khách yêu cầu VIP</span>
                            <?php endif; ?>
                        </label>

                        <!-- Chọn chế độ: Ghép bàn sảnh hoặc Chọn phòng VIP -->
                        <?php
                        $isVipCurrent = in_array($booking['loai_ban'] ?? '', ['phong_vip', 'phong_rieng']);
                        $initialMode = (!empty($booking['yeu_cau_phong_rieng']) || $isVipCurrent) ? 'vip' : 'sanh';
                        ?>
                        <div class="btn-group w-100 mb-3 shadow-xs" role="group">
                            <input type="radio" class="btn-check" name="ban_mode_radio" id="modeSanh" value="sanh" <?= $initialMode === 'sanh' ? 'checked' : '' ?> onchange="toggleBanMode('sanh')">
                            <label class="btn btn-outline-primary btn-sm py-2 fw-semibold" for="modeSanh">
                                <i class="bi bi-grid-3x3-gap-fill me-1"></i> Ghép Bàn Sảnh
                            </label>

                            <input type="radio" class="btn-check" name="ban_mode_radio" id="modeVip" value="vip" <?= $initialMode === 'vip' ? 'checked' : '' ?> onchange="toggleBanMode('vip')">
                            <label class="btn btn-outline-warning btn-sm py-2 fw-semibold text-dark" for="modeVip">
                                <i class="bi bi-door-closed-fill me-1 text-warning"></i> Phòng VIP
                            </label>
                        </div>
                        <input type="hidden" name="ban_option" id="banOptionInput" value="<?= $initialMode === 'vip' ? 'phong_vip' : 'ghep_ban' ?>">

                        <!-- KHU VỰC 1: GHÉP BÀN SẢNH (CHECKBOXES) -->
                        <div id="wrapperGhepBan" style="<?= $initialMode === 'sanh' ? '' : 'display:none;' ?>">
                            <div class="small fw-semibold text-secondary mb-2 d-flex justify-content-between align-items-center">
                                <span>Tích chọn các bàn sảnh để ghép:</span>
                                <span class="text-muted" style="font-size:0.75rem;">Yêu cầu: <strong><?= $booking['so_nguoi'] ?></strong> khách</span>
                            </div>

                            <div class="list-group mb-2 border rounded-3 p-2 bg-light" style="max-height: 220px; overflow-y: auto;">
                                <?php if (empty($banSanhList)): ?>
                                    <div class="text-muted small py-2 text-center">Không có bàn sảnh nào.</div>
                                <?php else: ?>
                                    <?php foreach ($banSanhList as $t): 
                                        $isOccupied = isset($occupiedTableMap[$t['id']]);
                                        $isChecked  = in_array($t['id'], $assignedTableIds ?? []);
                                    ?>
                                        <label class="list-group-item list-group-item-action d-flex align-items-center justify-content-between p-2 rounded mb-1 border-0 <?= $isOccupied ? 'opacity-50 bg-light' : 'bg-white shadow-xs' ?>" style="cursor: <?= $isOccupied ? 'not-allowed' : 'pointer' ?>;">
                                            <div class="d-flex align-items-center gap-2">
                                                <input class="form-check-input ban-sanh-checkbox m-0" type="checkbox" name="ban_ids[]" value="<?= $t['id'] ?>"
                                                       data-capacity="<?= $t['suc_chua'] ?>"
                                                       data-name="<?= htmlspecialchars($t['ten']) ?>"
                                                       <?= $isChecked ? 'checked' : '' ?>
                                                       <?= $isOccupied ? 'disabled' : '' ?>
                                                       onchange="recalculateCombinedTables()">
                                                <span class="fw-semibold text-dark small <?= $isChecked ? 'text-primary' : '' ?>"><?= htmlspecialchars($t['ten']) ?></span>
                                            </div>
                                            <div>
                                                <?php if ($isOccupied): ?>
                                                    <span class="badge bg-danger-subtle text-danger" style="font-size: 0.7rem;">🔒 Bận (Đơn #<?= $occupiedTableMap[$t['id']] ?>)</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.72rem;"><?= $t['suc_chua'] ?> người</span>
                                                <?php endif; ?>
                                            </div>
                                        </label>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <!-- Thống kê ghép bàn theo thời gian thực -->
                            <div class="p-2 rounded border bg-white small mb-2 shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-muted">Đã chọn ghép:</span>
                                    <strong id="selectedTableNamesText" class="text-primary text-end text-truncate ms-2" style="max-width: 170px;">Chưa chọn</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Tổng sức chứa:</span>
                                    <div>
                                        <strong id="combinedCapacityText" class="fs-6 text-dark">0</strong> / <?= $booking['so_nguoi'] ?> khách
                                        <span id="capacityIndicatorBadge" class="badge bg-secondary ms-1">Chưa chọn</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- KHU VỰC 2: CHỌN PHÒNG VIP (SELECT) -->
                        <div id="wrapperPhongVIP" style="<?= $initialMode === 'vip' ? '' : 'display:none;' ?>">
                            <div class="small fw-semibold text-secondary mb-2">Chọn phòng riêng VIP cho khách:</div>
                            <select name="banphong_vip_id" id="banphongVipSelect" class="form-select">
                                <option value="">— Chưa chọn phòng VIP —</option>
                                <?php foreach ($phongVIPList as $t): 
                                    $isOccupied = isset($occupiedTableMap[$t['id']]);
                                    $isSel      = in_array($t['id'], $assignedTableIds ?? []);
                                    $feeText    = $t['phu_phi'] > 0 ? ' (+'.number_format($t['phu_phi'],0,',','.').'đ)' : '';
                                ?>
                                    <option value="<?= $t['id'] ?>" <?= $isSel ? 'selected' : '' ?> <?= $isOccupied ? 'disabled' : '' ?>>
                                        🏠 <?= htmlspecialchars($t['ten']) ?> (<?= $t['suc_chua'] ?> người<?= $feeText ?>) <?= $isOccupied ? '— [BẬN ĐƠN #'.$occupiedTableMap[$t['id']].']' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-text small text-muted mt-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Tích chọn <strong>nhiều bàn sảnh cùng lúc</strong> để ghép bàn đáp ứng đoàn đông khách.
                        </div>
                    </div>

                    <hr class="my-3 opacity-25">

                    <!-- TRẠNG THÁI XỬ LÝ -->
                    <label class="form-label fw-semibold small text-muted text-uppercase mb-2">Chọn trạng thái xử lý</label>
                    <div class="d-flex flex-column gap-2 mb-3">

                        <?php if ($booking['trang_thai'] === 'yeu_cau_huy'): ?>
                            <label class="form-check p-3 border rounded-3 d-flex align-items-center justify-content-between border-danger bg-danger-subtle" style="cursor: pointer;">
                                <div>
                                    <input class="form-check-input me-2" type="radio" name="trang_thai" value="yeu_cau_huy" checked>
                                    <span class="fw-semibold text-danger">Đang yêu cầu hủy</span>
                                    <div class="text-danger small ps-4">Lý do: "<?= htmlspecialchars($booking['ly_do_huy'] ?? 'Khách xin hủy') ?>"</div>
                                </div>
                                <i class="bi bi-exclamation-octagon-fill text-danger fs-5"></i>
                            </label>
                        <?php endif; ?>

                        <label class="form-check p-3 border rounded-3 d-flex align-items-center justify-content-between <?= $booking['trang_thai'] === 'cho_duyet' ? 'border-warning bg-warning-subtle' : '' ?>" style="cursor: pointer;">
                            <div>
                                <input class="form-check-input me-2" type="radio" name="trang_thai" value="cho_duyet" <?= $booking['trang_thai'] === 'cho_duyet' ? 'checked' : '' ?>>
                                <span class="fw-semibold text-dark">Chờ duyệt</span>
                                <div class="text-muted small ps-4">Giữ nguyên, chờ kiểm tra thêm</div>
                            </div>
                            <i class="bi bi-hourglass-split text-warning fs-5"></i>
                        </label>

                        <label class="form-check p-3 border rounded-3 d-flex align-items-center justify-content-between <?= $booking['trang_thai'] === 'da_duyet' ? 'border-success bg-success-subtle' : '' ?>" style="cursor: pointer;">
                            <div>
                                <input class="form-check-input me-2" type="radio" name="trang_thai" value="da_duyet" <?= $booking['trang_thai'] === 'da_duyet' ? 'checked' : '' ?>>
                                <span class="fw-semibold text-success">Duyệt đặt bàn</span>
                                <div class="text-muted small ps-4">Xác nhận giữ bàn, chuẩn bị tiệc</div>
                            </div>
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                        </label>

                        <label class="form-check p-3 border rounded-3 d-flex align-items-center justify-content-between <?= $booking['trang_thai'] === 'hoan_thanh' ? 'border-primary bg-primary-subtle' : '' ?>" style="cursor: pointer;">
                            <div>
                                <input class="form-check-input me-2" type="radio" name="trang_thai" value="hoan_thanh" <?= $booking['trang_thai'] === 'hoan_thanh' ? 'checked' : '' ?>>
                                <span class="fw-semibold text-primary">Hoàn thành</span>
                                <div class="text-muted small ps-4">Tiệc đã diễn ra, khách thanh toán</div>
                            </div>
                            <i class="bi bi-patch-check-fill text-primary fs-5"></i>
                        </label>

                        <label class="form-check p-3 border rounded-3 d-flex align-items-center justify-content-between <?= $booking['trang_thai'] === 'tu_choi' ? 'border-danger bg-danger-subtle' : '' ?>" style="cursor: pointer;">
                            <div>
                                <input class="form-check-input me-2" type="radio" name="trang_thai" value="tu_choi" <?= $booking['trang_thai'] === 'tu_choi' ? 'checked' : '' ?>>
                                <span class="fw-semibold text-danger">Từ chối đơn</span>
                                <div class="text-muted small ps-4">Hết bàn hoặc không đáp ứng được</div>
                            </div>
                            <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                        </label>

                        <label class="form-check p-3 border rounded-3 d-flex align-items-center justify-content-between <?= $booking['trang_thai'] === 'huy' ? 'border-secondary bg-secondary-subtle' : '' ?>" style="cursor: pointer;">
                            <div>
                                <input class="form-check-input me-2" type="radio" name="trang_thai" value="huy" <?= $booking['trang_thai'] === 'huy' ? 'checked' : '' ?>>
                                <span class="fw-semibold text-secondary">Hủy bỏ đơn</span>
                                <div class="text-muted small ps-4">Khách yêu cầu hủy hoặc quá hạn</div>
                            </div>
                            <i class="bi bi-slash-circle-fill text-secondary fs-5"></i>
                        </label>
                    </div>

                    <!-- Ghi chú nội bộ -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted text-uppercase">Ghi chú quản trị / Lý do</label>
                        <textarea name="ghi_chu" class="form-control" rows="3"
                                  placeholder="Nhập ghi chú hoặc lý do nếu từ chối/hủy đơn..."><?= htmlspecialchars($booking['ghi_chu'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold d-flex align-items-center justify-content-center gap-2 shadow-sm">
                        <i class="bi bi-save2"></i> Lưu Cập Nhật
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleBanMode(mode) {
    const wrapperSanh = document.getElementById('wrapperGhepBan');
    const wrapperVip  = document.getElementById('wrapperPhongVIP');
    const optionInput = document.getElementById('banOptionInput');

    if (mode === 'vip') {
        wrapperSanh.style.display = 'none';
        wrapperVip.style.display  = 'block';
        optionInput.value = 'phong_vip';
    } else {
        wrapperSanh.style.display = 'block';
        wrapperVip.style.display  = 'none';
        optionInput.value = 'ghep_ban';
        recalculateCombinedTables();
    }
}

function recalculateCombinedTables() {
    const checkboxes = document.querySelectorAll('.ban-sanh-checkbox:checked');
    const names = [];
    let totalCapacity = 0;
    const requiredGuests = <?= (int)($booking['so_nguoi'] ?? 0) ?>;

    checkboxes.forEach(cb => {
        names.push(cb.dataset.name);
        totalCapacity += parseInt(cb.dataset.capacity || 0);
    });

    const namesEl = document.getElementById('selectedTableNamesText');
    const capEl   = document.getElementById('combinedCapacityText');
    const badgeEl = document.getElementById('capacityIndicatorBadge');

    if (!namesEl || !capEl || !badgeEl) return;

    if (checkboxes.length === 0) {
        namesEl.textContent = 'Chưa chọn';
        capEl.textContent   = '0';
        badgeEl.className   = 'badge bg-secondary ms-1';
        badgeEl.textContent = 'Chưa chọn';
    } else {
        namesEl.textContent = names.join(' + ');
        capEl.textContent   = totalCapacity;

        if (totalCapacity >= requiredGuests) {
            badgeEl.className   = 'badge bg-success ms-1';
            badgeEl.innerHTML   = '<i class="bi bi-check-lg"></i> Đủ chỗ';
        } else {
            badgeEl.className   = 'badge bg-warning text-dark ms-1';
            badgeEl.innerHTML   = '⚠️ Thiếu chỗ';
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    recalculateCombinedTables();
});
</script>
