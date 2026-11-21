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
                            <div class="fw-bold fs-6 text-dark d-flex align-items-center gap-2">
                                <?= htmlspecialchars($booking['ten_ban']) ?>
                                <?php if ($booking['loai_ban'] === 'phong_rieng'): ?>
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">VIP <?= $booking['phu_phi'] > 0 ? '(+' . number_format($booking['phu_phi'], 0, ',', '.') . ' đ)' : '' ?></span>
                                <?php else: ?>
                                    <span class="badge bg-light text-secondary border">Bàn thường</span>
                                <?php endif; ?>
                            </div>
                            <div class="small text-muted">Sức chứa: <?= $booking['suc_chua'] ?> người</div>
                        <?php else: ?>
                            <div class="p-2 rounded border border-dashed border-warning bg-warning-subtle">
                                <span class="text-warning fw-semibold small">
                                    <i class="bi bi-clock-history me-1"></i>Chưa được xếp bàn — Admin cần xếp bàn ở form bên phải
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

        <!-- 2. THỰC ĐƠN & MÓN ĂN CHI TIẾT -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-white py-3 px-4 border-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <h5 class="card-title mb-0 fw-bold fs-6">
                        <i class="bi bi-egg-fried text-primary me-2"></i>Thực Đơn &amp; Món Ăn Chi Tiết
                    </h5>
                    <?php if (!empty($booking['ten_set'])): ?>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle py-1 px-2">
                            <i class="bi bi-box2-heart me-1"></i>Set: <?= htmlspecialchars($booking['ten_set']) ?>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($extraDishes)): ?>
                        <span class="badge bg-warning-subtle text-dark border border-warning-subtle py-1 px-2 fw-bold">
                            <i class="bi bi-basket-fill text-warning me-1"></i>Có <?= count($extraDishes) ?> món gọi thêm ngoài
                        </span>
                    <?php endif; ?>
                </div>

                <a href="<?= BASE_URL ?>?action=dat-mon&booking_id=<?= $booking['id'] ?>" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 shadow-sm" style="border-radius: 6px; font-size: 0.8rem;">
                    <i class="bi bi-pencil-square"></i> Thêm / Sửa món cho khách
                </a>
            </div>

            <div class="card-body p-4 pt-2">

                <!-- ── PHẦN A: SET TIỆC (NẾU CÓ) ── -->
                <?php if (!empty($booking['ten_set'])): ?>
                    <div class="p-3 mb-4 rounded-3 border" style="background: #F8FAFC; border-color: #E2E8F0 !important;">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary text-white px-2 py-1" style="font-size: 0.78rem;">
                                    <i class="bi bi-box2-heart-fill me-1"></i>SET MENU
                                </span>
                                <h6 class="mb-0 fw-bold text-dark fs-6"><?= htmlspecialchars($booking['ten_set']) ?></h6>
                                <span class="badge bg-light text-secondary border small">1 bàn (<?= $booking['so_nguoi'] ?> khách)</span>
                            </div>
                            <div class="fw-bold fs-6 text-primary">
                                <?= number_format($setPrice, 0, ',', '.') ?> đ
                            </div>
                        </div>

                        <!-- Danh sách món chi tiết trong Set Tiệc -->
                        <div class="mt-3 pt-2 border-top">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="small fw-bold text-uppercase text-muted" style="letter-spacing: 0.5px; font-size: 0.75rem;">
                                    <i class="bi bi-list-check text-primary me-1"></i>Chi tiết các món trong Set này (Bếp chuẩn bị):
                                </span>
                                <span class="badge bg-white text-secondary border small">
                                    <?= count($setDishes) ?> món trong set
                                </span>
                            </div>

                            <?php if (!empty($setDishes)): ?>
                                <?php
                                $groupedSet = [];
                                foreach ($setDishes as $sd) {
                                    $groupedSet[$sd['nhom_mon'] ?? 'Món chính'][] = $sd;
                                }
                                ?>
                                <div class="row g-2">
                                    <?php foreach ($groupedSet as $nhom => $mons): ?>
                                        <div class="col-12 col-md-6">
                                            <div class="p-2 bg-white rounded border small h-100">
                                                <div class="fw-bold text-success mb-1" style="font-size: 0.78rem;">
                                                    <i class="bi bi-tag-fill me-1"></i><?= htmlspecialchars($nhom) ?>:
                                                </div>
                                                <ul class="mb-0 ps-3 text-secondary" style="line-height: 1.6;">
                                                    <?php foreach ($mons as $m): ?>
                                                        <li>
                                                            <strong class="text-dark"><?= htmlspecialchars($m['ten']) ?></strong>
                                                            <?php if (!empty($m['mo_ta'])): ?>
                                                                <span class="text-muted" style="font-size: 0.72rem;">(<?= htmlspecialchars(mb_strimwidth($m['mo_ta'], 0, 45, '...')) ?>)</span>
                                                            <?php endif; ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="small text-muted py-2 fst-italic">
                                    <i class="bi bi-info-circle me-1"></i>Chưa cập nhật danh sách món cụ thể cho set tiệc này trong hệ thống.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="p-3 mb-4 rounded-3 border bg-light text-muted small">
                        <i class="bi bi-info-circle me-1 text-primary"></i><strong>Hình thức gọi món:</strong> Khách không chọn Set tiệc trọn gói mà chọn món theo yêu cầu.
                    </div>
                <?php endif; ?>

                <!-- ── PHẦN B: MÓN ĂN GỌI THÊM NGOÀI SET (MÓN LẺ) ── -->
                <div class="mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.92rem;">
                            <i class="bi bi-basket2-fill text-warning me-2"></i>Món Gọi Thêm Ngoài Set (Món lẻ)
                        </h6>
                        <?php if (!empty($extraDishes)): ?>
                            <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 0.78rem;">
                                <?= count($extraDishes) ?> món gọi thêm • <?= number_format($totalExtraPrice, 0, ',', '.') ?> đ
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($extraDishes)): ?>
                        <div class="table-responsive rounded-3 border">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light small text-muted text-uppercase" style="font-size: 0.75rem;">
                                    <tr>
                                        <th class="ps-3" style="width: 45px;">#</th>
                                        <th>Tên món ăn gọi thêm</th>
                                        <th>Phân loại</th>
                                        <th class="text-center" style="width: 100px;">Số lượng</th>
                                        <th class="text-end" style="width: 130px;">Đơn giá</th>
                                        <th class="text-end pe-3" style="width: 140px;">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $stt = 1; foreach ($extraDishes as $dish): ?>
                                        <tr>
                                            <td class="ps-3 text-muted fw-bold"><?= $stt++ ?></td>
                                            <td>
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($dish['ten_mon']) ?></div>
                                                <?php if (!empty($dish['mota_mon'])): ?>
                                                    <div class="text-muted" style="font-size: 0.75rem;">
                                                        <?= htmlspecialchars(mb_strimwidth($dish['mota_mon'], 0, 60, '...')) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-secondary border">
                                                    <?= htmlspecialchars($dish['nhom_mon'] ?? 'Món lẻ') ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning-subtle text-dark border border-warning-subtle fw-bold px-2 py-1">
                                                    x<?= $dish['so_luong'] ?>
                                                </span>
                                            </td>
                                            <td class="text-end text-muted small">
                                                <?= number_format($dish['don_gia'], 0, ',', '.') ?> đ
                                            </td>
                                            <td class="text-end pe-3 fw-bold text-danger">
                                                <?= number_format($dish['don_gia'] * $dish['so_luong'], 0, ',', '.') ?> đ
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold ps-3 text-uppercase small text-secondary">
                                            Tổng tiền các món gọi thêm:
                                        </td>
                                        <td class="text-end pe-3 fw-bold text-danger">
                                            <?= number_format($totalExtraPrice, 0, ',', '.') ?> đ
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="p-3 rounded-3 border bg-light text-muted small d-flex align-items-center justify-content-between">
                            <div>
                                <i class="bi bi-dash-circle me-1 text-secondary"></i>
                                <span>Khách <strong>không gọi thêm món ngoài</strong> set tiệc.</span>
                            </div>
                            <a href="<?= BASE_URL ?>?action=dat-mon&booking_id=<?= $booking['id'] ?>" class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size: 0.75rem;">
                                <i class="bi bi-plus-lg me-1"></i>Thêm món lẻ nếu khách yêu cầu
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- ── PHẦN C: BẢNG TỔNG KẾT CHI PHÍ ĐƠN ĐẶT ── -->
                <div class="p-3 rounded-3 border" style="background: #FFFDF9; border-color: #FEE2E2 !important;">
                    <h6 class="fw-bold mb-3 text-dark" style="font-size: 0.88rem;">
                        <i class="bi bi-receipt-cutoff text-danger me-2"></i>Bảng Tổng Hợp Chi Phí Đơn Đặt
                    </h6>
                    <div class="d-flex justify-content-between py-1 small border-bottom">
                        <span class="text-secondary">
                            <i class="bi bi-box2 me-1"></i>Tiền Set Tiệc:
                            <?php if (!empty($booking['ten_set'])): ?>
                                <span class="fw-semibold text-dark">(<?= htmlspecialchars($booking['ten_set']) ?>)</span>
                            <?php endif; ?>
                        </span>
                        <strong class="text-dark"><?= number_format($setPrice, 0, ',', '.') ?> đ</strong>
                    </div>

                    <div class="d-flex justify-content-between py-1 small border-bottom">
                        <span class="text-secondary">
                            <i class="bi bi-cart-plus me-1 text-warning"></i>Tiền Món Gọi Thêm Ngoài:
                            <?php if (!empty($extraDishes)): ?>
                                <span class="fw-semibold text-dark">(<?= count($extraDishes) ?> món)</span>
                            <?php endif; ?>
                        </span>
                        <strong class="<?= $totalExtraPrice > 0 ? 'text-danger' : 'text-dark' ?>">
                            <?= number_format($totalExtraPrice, 0, ',', '.') ?> đ
                        </strong>
                    </div>

                    <?php if (!empty($booking['ten_ban']) && $booking['loai_ban'] === 'phong_rieng' && $vipFee > 0): ?>
                        <div class="d-flex justify-content-between py-1 small border-bottom">
                            <span class="text-secondary">
                                <i class="bi bi-door-open me-1 text-primary"></i>Phụ phí phòng VIP (<?= htmlspecialchars($booking['ten_ban']) ?>):
                            </span>
                            <strong class="text-dark"><?= number_format($vipFee, 0, ',', '.') ?> đ</strong>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center pt-3 mt-1">
                        <div>
                            <span class="fw-bold text-uppercase fs-6 text-dark">Tổng Cộng Tạm Tính:</span>
                            <div class="text-muted" style="font-size: 0.72rem;">Đã bao gồm Set menu + Món gọi thêm + Phụ phí phòng (nếu có)</div>
                        </div>
                        <div class="text-end">
                            <span class="fw-bold fs-4" style="color: #C4603C;">
                                <?= number_format($booking['tong_tien'], 0, ',', '.') ?> đ
                            </span>
                        </div>
                    </div>
                </div>

                <!-- ── PHẦN D: QUẢN LÝ THANH TOÁN & THU TIỀN TRỰC TIẾP ── -->
                <div class="mt-4 p-4 rounded-3 border bg-white shadow-sm" style="border-top: 3px solid #16a34a !important;">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <h6 class="fw-bold text-dark mb-0 fs-6">
                            <i class="bi bi-cash-stack text-success me-2"></i>Tình Trạng Thanh Toán &amp; Quản Lý Thu Tiền
                        </h6>
                        <div class="d-flex align-items-center gap-2">
                            <?php if (($booking['trang_thai_thanh_toan'] ?? '') === 'da_thanh_toan'): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 fw-bold">
                                    <i class="bi bi-check-circle me-1"></i>Đã thanh toán đủ (100%)
                                </span>
                            <?php elseif (($booking['trang_thai_thanh_toan'] ?? '') === 'da_dat_coc'): ?>
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-1 fw-bold">
                                    <i class="bi bi-credit-card me-1"></i>Đã đặt cọc tiệc
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 fw-bold">
                                    <i class="bi bi-clock-history me-1"></i>Chưa thanh toán
                                </span>
                            <?php endif; ?>

                            <a href="<?= BASE_URL ?>?action=hoa-don&booking_id=<?= $booking['id'] ?>" target="_blank" class="btn btn-outline-secondary btn-sm fw-semibold">
                                <i class="bi bi-printer me-1"></i>In Hóa Đơn
                            </a>
                        </div>
                    </div>

                    <!-- 3 thẻ thống kê tiền đơn này -->
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <div class="p-2 rounded bg-light border text-center">
                                <div class="text-muted" style="font-size:0.75rem;">Tổng tiền tiệc:</div>
                                <div class="fw-bold text-dark"><?= number_format($booking['tong_tien'], 0, ',', '.') ?> đ</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded bg-success-subtle border border-success-subtle text-center">
                                <div class="text-success small fw-semibold" style="font-size:0.75rem;">Đã thực thu:</div>
                                <div class="fw-bold text-success"><?= number_format($daTra, 0, ',', '.') ?> đ</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded bg-danger-subtle border border-danger-subtle text-center">
                                <div class="text-danger small fw-semibold" style="font-size:0.75rem;">Còn lại phải thu:</div>
                                <div class="fw-bold text-danger"><?= number_format($conLai, 0, ',', '.') ?> đ</div>
                            </div>
                        </div>
                    </div>

                    <!-- Lịch sử các lần khách thanh toán -->
                    <div class="mb-3">
                        <div class="fw-bold text-secondary small text-uppercase mb-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                            Lịch sử các giao dịch ghi nhận (<?= count($payments) ?>):
                        </div>
                        <?php if (empty($payments)): ?>
                            <div class="text-muted small fst-italic p-2 bg-light rounded text-center">
                                Chưa có giao dịch nào được ghi nhận cho đơn này.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle mb-0 small">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Mã GD</th>
                                            <th>Phương thức</th>
                                            <th class="text-end">Số tiền</th>
                                            <th>Thời gian</th>
                                            <th class="text-center">Trạng thái</th>
                                            <th class="text-end">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($payments as $p): ?>
                                            <tr>
                                                <td class="font-monospace fw-bold text-primary">
                                                    <?= htmlspecialchars($p['ma_giao_dich']) ?>
                                                    <?php if (!empty($p['anh_bien_lai'])): ?>
                                                        <a href="<?= BASE_ASSETS_UPLOADS . htmlspecialchars($p['anh_bien_lai']) ?>" target="_blank" class="badge bg-info text-white text-decoration-none">Bill</a>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="text-capitalize"><?= str_replace('_', ' ', $p['phuong_thuc']) ?></span>
                                                    <span class="badge bg-light text-muted border"><?= $p['loai_thanh_toan'] === 'dat_coc' ? 'Cọc' : 'Toàn bộ' ?></span>
                                                </td>
                                                <td class="text-end fw-bold text-success">+<?= number_format($p['so_tien'], 0, ',', '.') ?> đ</td>
                                                <td class="text-muted"><?= date('d/m/Y H:i', strtotime($p['ngay_thanh_toan'])) ?></td>
                                                <td class="text-center">
                                                    <span class="badge <?= $p['trang_thai'] === 'thanh_cong' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                                        <?= $p['trang_thai'] === 'thanh_cong' ? 'Thành công' : 'Chờ xác nhận' ?>
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <?php if ($p['trang_thai'] === 'cho_xac_nhan'): ?>
                                                        <form method="POST" action="<?= BASE_URL ?>?action=admin-thanh-toan-cap-nhat" style="display:inline;">
                                                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                                            <input type="hidden" name="trang_thai" value="thanh_cong">
                                                            <input type="hidden" name="redirect" value="detail">
                                                            <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                                            <button type="submit" class="btn btn-xs btn-success py-0 px-2 fw-semibold" title="Xác nhận đã nhận tiền">
                                                                <i class="bi bi-check-lg"></i> Duyệt
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Form thu tiền trực tiếp tại quầy dành cho thu ngân -->
                    <?php if ($conLai > 0): ?>
                        <div class="p-3 rounded-3 bg-light border">
                            <h6 class="fw-bold text-dark small mb-2"><i class="bi bi-plus-circle me-1 text-primary"></i>Thu ngân ghi nhận thu tiền tại quầy / chuyển khoản:</h6>
                            <form method="POST" action="<?= BASE_URL ?>?action=admin-thu-tien" class="row g-2 align-items-end">
                                <input type="hidden" name="datban_id" value="<?= $booking['id'] ?>">

                                <div class="col-md-3">
                                    <label class="form-label small text-muted mb-1">Số tiền thu (VNĐ):</label>
                                    <input type="number" name="so_tien" class="form-control form-control-sm fw-bold text-danger" 
                                           value="<?= $conLai ?>" min="1000" max="<?= $conLai ?>" required>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label small text-muted mb-1">Phương thức:</label>
                                    <select name="phuong_thuc" class="form-select form-select-sm">
                                        <option value="tien_mat">Tiền mặt tại quầy</option>
                                        <option value="chuyen_khoan">Chuyển khoản VietQR</option>
                                        <option value="vnpay">Quẹt thẻ POS / VNPAY</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label small text-muted mb-1">Loại thu:</label>
                                    <select name="loai_thanh_toan" class="form-select form-select-sm">
                                        <option value="<?= $daTra > 0 ? 'toan_bo' : 'dat_coc' ?>"><?= $daTra > 0 ? 'Thanh toán còn lại' : 'Thu tiền cọc' ?></option>
                                        <option value="toan_bo">Thanh toán toàn bộ</option>
                                        <option value="thanh_toan_them">Thu tiền thêm</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-success btn-sm w-100 fw-bold">
                                        <i class="bi bi-check2-circle me-1"></i>Xác nhận thu
                                    </button>
                                </div>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success py-2 px-3 small mb-0 border-0">
                            <i class="bi bi-check-circle-fill me-1"></i> Đơn đặt bàn này đã được thanh toán đủ 100% chi phí.
                        </div>
                    <?php endif; ?>

                </div>

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

                    <!-- XẾP BÀN / PHÒNG (Admin chọn) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted text-uppercase">
                            <i class="bi bi-door-open me-1 text-primary"></i>Xếp Bàn / Phòng Cho Khách
                            <?php if (!empty($booking['yeu_cau_phong_rieng'])): ?>
                                <span class="badge bg-warning text-dark ms-1">⚠ Yêu cầu phòng VIP</span>
                            <?php endif; ?>
                        </label>
                        <select name="banphong_id" class="form-select">
                            <option value="">— Chưa xếp bàn / Chưa chọn —</option>
                            <?php if (!empty($allTables)): ?>
                                <?php
                                // Nhóm: Phòng riêng trước, bàn sảnh sau
                                $phongRieng = array_filter($allTables, fn($t) => $t['loai'] === 'phong_rieng');
                                $banSanh    = array_filter($allTables, fn($t) => $t['loai'] !== 'phong_rieng');
                                ?>
                                <?php if (!empty($phongRieng)): ?>
                                    <optgroup label="🏠 Phòng Riêng (VIP)">
                                        <?php foreach ($phongRieng as $t): ?>
                                            <?php
                                            $sel  = ($booking['banphong_id'] == $t['id']) ? 'selected' : '';
                                            $fee  = $t['phu_phi'] > 0 ? ' — Phụ phí: ' . number_format($t['phu_phi'], 0, ',', '.') . 'đ' : '';
                                            ?>
                                            <option value="<?= $t['id'] ?>" <?= $sel ?>>
                                                <?= htmlspecialchars($t['ten']) ?> (<?= $t['suc_chua'] ?> người<?= $fee ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endif; ?>
                                <?php if (!empty($banSanh)): ?>
                                    <optgroup label="🪑 Bàn Sảnh Chung">
                                        <?php foreach ($banSanh as $t): ?>
                                            <?php $sel = ($booking['banphong_id'] == $t['id']) ? 'selected' : ''; ?>
                                            <option value="<?= $t['id'] ?>" <?= $sel ?>>
                                                <?= htmlspecialchars($t['ten']) ?> (<?= $t['suc_chua'] ?> người)
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endif; ?>
                            <?php endif; ?>
                        </select>
                        <div class="form-text small text-muted mt-1">
                            <i class="bi bi-info-circle me-1"></i>
                            Bắt buộc chọn bàn/phòng trước khi <strong>Duyệt đặt bàn</strong>. Phụ phí phòng VIP sẽ tự cộng vào tổng tiền.
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
