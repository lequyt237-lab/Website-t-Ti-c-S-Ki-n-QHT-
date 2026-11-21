<?php
// views/admin/ban_phong.php
$loaiLabels = [
    'ban_thuong' => 'Bàn thường',
    'phong_vip'  => 'Phòng VIP',
];
$statusColors = [
    'da_duyet'   => ['bg' => '#d4edda', 'color' => '#155724', 'icon' => 'bi-check-circle-fill', 'label' => 'Đã duyệt'],
    'cho_duyet'  => ['bg' => '#fff3cd', 'color' => '#856404', 'icon' => 'bi-hourglass-split',   'label' => 'Chờ duyệt'],
    'yeu_cau_huy'=> ['bg' => '#f8d7da', 'color' => '#721c24', 'icon' => 'bi-x-octagon-fill',    'label' => 'Y/C hủy'],
];
$caLabels = ['trua' => '☀️ Trưa', 'toi' => '🌙 Tối'];
?>

<style>
.bp-card        { border-radius: 12px; }
.badge-ban      { background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7; }
.badge-phong    { background:#e3f2fd; color:#1565c0; border:1px solid #90caf9; }
.bp-badge       { font-size:.75rem; padding:3px 10px; border-radius:20px; font-weight:600; }
.bp-table th    { background:#f8f9fa; font-size:.8rem; text-transform:uppercase; letter-spacing:.5px; color:#6c757d; white-space:nowrap; }
.form-add-card  { background:linear-gradient(135deg,#f8fffe,#f0f7ff); border:1.5px solid #d0e8ff; border-radius:14px; }
.phu-phi-col    { font-weight:600; color:#c0392b; }
.booking-slot   { display:inline-flex; align-items:center; gap:5px; padding:3px 8px; border-radius:8px; font-size:.74rem; font-weight:600; margin:2px 2px 2px 0; white-space:nowrap; }
.slot-occupied  { background:#ffeaa7; color:#6c5a00; border:1px solid #ffd700; }
.slot-approved  { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
.slot-pending   { background:#fff3cd; color:#856404; border:1px solid #ffc107; }
.slot-cancel    { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
.avail-badge    { background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7; font-size:.74rem; padding:3px 10px; border-radius:20px; font-weight:600; }
</style>

<div class="container-fluid px-4 py-3">

    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 fw-bold"><i class="bi bi-door-open-fill me-2 text-primary"></i>Quản lý Bàn & Phòng</h4>
            <p class="text-muted mb-0 small">Thêm, xem và xóa bàn thường / phòng VIP — kèm trạng thái đặt chỗ hiện tại</p>
        </div>
        <span class="badge bg-primary fs-6 px-3 py-2"><?= count($banPhongList) ?> bàn/phòng</span>
    </div>

    <!-- Flash messages -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="row g-4">

        <!-- ═══ DANH SÁCH BÀN/PHÒNG ═══ -->
        <div class="col-lg-8">
            <div class="card bp-card shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-list-ul me-2"></i>Danh sách & Trạng thái đặt chỗ</h6>
                    <span class="text-muted small"><i class="bi bi-clock me-1"></i>Chỉ hiển thị đơn từ hôm nay trở đi</span>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($banPhongList)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1"></i>
                            <p class="mt-2">Chưa có bàn/phòng nào.</p>
                        </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 bp-table">
                            <thead>
                                <tr>
                                    <th class="ps-3" style="width:30px">#</th>
                                    <th>Tên</th>
                                    <th>Loại</th>
                                    <th>Sức chứa</th>
                                    <th>Phụ phí</th>
                                    <th>Đơn đang đặt <span class="text-primary">(từ hôm nay)</span></th>
                                    <th class="text-center pe-3">Xóa</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($banPhongList as $i => $bp):
                                $myBookings = $bookingsByTable[$bp['id']] ?? [];
                            ?>
                                <tr>
                                    <td class="ps-3 text-muted small"><?= $i + 1 ?></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($bp['ten']) ?></td>
                                    <td>
                                        <span class="bp-badge <?= $bp['loai'] === 'phong_vip' ? 'badge-phong' : 'badge-ban' ?>">
                                            <?= $loaiLabels[$bp['loai']] ?? $bp['loai'] ?>
                                        </span>
                                    </td>
                                    <td class="text-nowrap"><i class="bi bi-people-fill text-secondary me-1"></i><?= $bp['suc_chua'] ?> ng</td>
                                    <td class="phu-phi-col">
                                        <?= $bp['phu_phi'] > 0
                                            ? number_format($bp['phu_phi'], 0, ',', '.') . 'đ'
                                            : '<span class="text-muted fw-normal small">Miễn phí</span>'
                                        ?>
                                    </td>

                                    <!-- CỘT TRẠNG THÁI ĐẶT CHỖ -->
                                    <td style="min-width:200px; max-width:300px;">
                                        <?php if (empty($myBookings)): ?>
                                            <span class="avail-badge"><i class="bi bi-check2-circle me-1"></i>Trống – Có thể xếp</span>
                                        <?php else: ?>
                                            <?php foreach ($myBookings as $bk): ?>
                                                <?php
                                                $slotClass = match($bk['trang_thai']) {
                                                    'da_duyet'    => 'slot-approved',
                                                    'cho_duyet'   => 'slot-pending',
                                                    'yeu_cau_huy' => 'slot-cancel',
                                                    default       => 'slot-occupied',
                                                };
                                                $icon = match($bk['trang_thai']) {
                                                    'da_duyet'    => '✅',
                                                    'cho_duyet'   => '⏳',
                                                    'yeu_cau_huy' => '❌',
                                                    default       => '📌',
                                                };
                                                $ngay  = date('d/m/Y', strtotime($bk['ngay_dat']));
                                                $ca    = $caLabels[$bk['ca']] ?? $bk['ca'];
                                                $label = $statusColors[$bk['trang_thai']]['label'] ?? $bk['trang_thai'];
                                                ?>
                                                <a href="<?= BASE_URL ?>?action=admin-dat-ban-chi-tiet&id=<?= $bk['booking_id'] ?>"
                                                   class="booking-slot <?= $slotClass ?>" title="<?= htmlspecialchars($bk['ho_ten']) ?> | <?= $bk['so_nguoi'] ?> người">
                                                    <?= $icon ?> #<?= $bk['booking_id'] ?> <?= $ngay ?> <?= $ca ?>
                                                    <span style="opacity:.7">(<?= $label ?>)</span>
                                                </a>
                                            <?php endforeach; ?>
                                            <?php if (count($myBookings) > 1): ?>
                                                <div class="text-muted" style="font-size:.7rem; margin-top:2px;">
                                                    <i class="bi bi-info-circle me-1"></i><?= count($myBookings) ?> đơn đang chờ xử lý
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center pe-3">
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-bp"
                                            data-id="<?= $bp['id'] ?>"
                                            data-ten="<?= htmlspecialchars($bp['ten']) ?>">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Chú thích màu -->
                <div class="card-footer bg-white border-top py-2 px-3 d-flex flex-wrap gap-3 small text-muted">
                    <span><span class="booking-slot slot-approved me-1">✅</span>Đã duyệt — bàn đang được giữ</span>
                    <span><span class="booking-slot slot-pending me-1">⏳</span>Chờ duyệt — đơn chưa xác nhận</span>
                    <span><span class="booking-slot slot-cancel me-1">❌</span>Yêu cầu hủy — đang xử lý</span>
                    <span><span class="avail-badge me-1"><i class="bi bi-check2-circle"></i></span>Trống, có thể xếp</span>
                </div>
            </div>
        </div>

        <!-- ═══ FORM THÊM MỚI + THỐNG KÊ ═══ -->
        <div class="col-lg-4">
            <div class="form-add-card p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-plus-circle-fill me-2 text-success"></i>Thêm Bàn / Phòng Mới</h6>
                <form method="POST" action="<?= BASE_URL ?>?action=admin-ban-phong-store">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Tên bàn/phòng <span class="text-danger">*</span></label>
                        <input type="text" name="ten" class="form-control" placeholder="VD: Bàn C1, Phòng Hồng Ngọc..." required maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Loại <span class="text-danger">*</span></label>
                        <select name="loai" class="form-select" id="selectLoai">
                            <option value="ban_thuong">🪑 Bàn thường</option>
                            <option value="phong_vip">🏮 Phòng VIP</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Sức chứa (người) <span class="text-danger">*</span></label>
                        <input type="number" name="suc_chua" class="form-control" min="1" max="1000" placeholder="VD: 10" required>
                    </div>
                    <div class="mb-3" id="phuPhiRow">
                        <label class="form-label fw-semibold small">Phụ phí (VNĐ)</label>
                        <input type="number" name="phu_phi" class="form-control" min="0" step="1000" value="0" placeholder="0 = Miễn phí">
                        <div class="form-text">Áp dụng cho Phòng VIP có phụ phí riêng</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Mô tả (tùy chọn)</label>
                        <textarea name="mo_ta" class="form-control" rows="2" placeholder="Mô tả ngắn về bàn/phòng..." maxlength="255"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-semibold">
                        <i class="bi bi-plus-lg me-2"></i>Thêm vào hệ thống
                    </button>
                </form>
            </div>

            <!-- Thống kê nhỏ -->
            <?php
            $countBan    = count(array_filter($banPhongList, fn($b) => $b['loai'] === 'ban_thuong'));
            $countPhong  = count(array_filter($banPhongList, fn($b) => $b['loai'] === 'phong_vip'));
            $countBusy   = count(array_filter($banPhongList, fn($b) => !empty($bookingsByTable[$b['id']])));
            $countFree   = count($banPhongList) - $countBusy;
            ?>
            <div class="row g-2 mt-2">
                <div class="col-6">
                    <div class="card text-center py-3 border-success border-opacity-50">
                        <div class="fs-3 fw-bold text-success"><?= $countFree ?></div>
                        <div class="small text-muted">Đang trống</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card text-center py-3 border-warning border-opacity-75">
                        <div class="fs-3 fw-bold text-warning"><?= $countBusy ?></div>
                        <div class="small text-muted">Đang có đơn</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card text-center py-2 border-0 bg-light">
                        <div class="fs-5 fw-bold text-secondary"><?= $countBan ?></div>
                        <div class="small text-muted">Bàn thường</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card text-center py-2 border-0 bg-light">
                        <div class="fs-5 fw-bold text-primary"><?= $countPhong ?></div>
                        <div class="small text-muted">Phòng VIP</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="modalDeleteBP" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc muốn xóa <strong id="tenBPConfirm"></strong> khỏi hệ thống?
                <br><small class="text-muted">Lưu ý: Không thể xóa nếu bàn/phòng đang có đơn hoạt động.</small>
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form method="POST" action="<?= BASE_URL ?>?action=admin-ban-phong-delete">
                    <input type="hidden" name="id" id="deleteBPId">
                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash3 me-1"></i>Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('selectLoai').addEventListener('change', function() {
    document.getElementById('phuPhiRow').style.opacity = this.value === 'phong_vip' ? '1' : '0.6';
});

document.querySelectorAll('.btn-delete-bp').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('deleteBPId').value = this.dataset.id;
        document.getElementById('tenBPConfirm').textContent = this.dataset.ten;
        new bootstrap.Modal(document.getElementById('modalDeleteBP')).show();
    });
});
</script>
