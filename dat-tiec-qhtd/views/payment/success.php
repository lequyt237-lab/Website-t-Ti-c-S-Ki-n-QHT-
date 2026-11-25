<style>
.success-hero {
    background: linear-gradient(135deg, var(--forest-dk) 0%, var(--forest) 100%);
    padding: 100px 0 45px;
    color: var(--white);
    position: relative;
    overflow: hidden;
}
.success-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 80% 20%, rgba(212,168,67,0.18), transparent 50%);
}
.receipt-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid var(--stone, #e2e8f0);
    box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    position: relative;
    overflow: hidden;
}
.receipt-header-banner {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border-bottom: 1.5px dashed #86efac;
    padding: 30px 24px;
    text-align: center;
}
.stamp-paid {
    display: inline-block;
    padding: 6px 16px;
    border: 3px solid #16a34a;
    color: #16a34a;
    font-weight: 800;
    font-size: 1rem;
    letter-spacing: 2px;
    text-transform: uppercase;
    transform: rotate(-6deg);
    border-radius: 6px;
    opacity: 0.9;
}
</style>

<!-- SUCCESS HERO -->
<section class="success-hero">
    <div class="container position-relative" style="z-index: 2;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small text-white-50 mb-2">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-white text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>?action=lich-su-dat-ban" class="text-white text-decoration-none">Lịch sử đặt bàn</a></li>
                <li class="breadcrumb-item active text-warning" aria-current="page">Xác nhận thanh toán</li>
            </ol>
        </nav>
        <h1 class="h2 fw-bold mb-1">Xác Nhận Giao Dịch Thành Công</h1>
        <p class="text-white-50 small mb-0">Hệ thống đã ghi nhận khoản thanh toán của bạn cho đơn đặt tiệc tại QHT Banquet.</p>
    </div>
</section>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="receipt-card mb-4">
                
                <!-- BANNER THÀNH CÔNG -->
                <div class="receipt-header-banner">
                    <div class="d-inline-flex p-3 rounded-circle bg-success text-white shadow-sm mb-3">
                        <i class="bi bi-check-lg" style="font-size: 2.5rem; line-height: 1;"></i>
                    </div>
                    <h3 class="fw-bold text-success mb-1">Giao Dịch Thành Công!</h3>
                    <p class="text-muted small mb-3">Cảm ơn quý khách đã tin tưởng và lựa chọn nhà hàng tiệc gia đình QHT Banquet.</p>
                    <div class="stamp-paid">
                        <?= $payment['trang_thai'] === 'thanh_cong' ? 'ĐÃ GHI NHẬN' : 'CHỜ XÁC NHẬN' ?>
                    </div>
                </div>

                <!-- NỘI DUNG BIÊN NHẬN -->
                <div class="p-4 p-md-5">
                    
                    <div class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
                        <div>
                            <span class="text-muted small">Mã giao dịch:</span>
                            <div class="fw-bold text-primary fs-5 font-monospace"><?= htmlspecialchars($payment['ma_giao_dich']) ?></div>
                        </div>
                        <div class="text-end">
                            <span class="text-muted small">Thời gian giao dịch:</span>
                            <div class="fw-semibold text-secondary small"><?= date('d/m/Y H:i:s', strtotime($payment['ngay_thanh_toan'])) ?></div>
                        </div>
                    </div>

                    <!-- THÔNG TIN ĐƠN ĐẶT TIỆC -->
                    <div class="bg-light p-3 rounded-3 border mb-4">
                        <h6 class="fw-bold text-uppercase text-secondary mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            <i class="bi bi-receipt me-1"></i>Chi tiết đơn tiệc liên quan:
                        </h6>
                        <div class="row g-2 small">
                            <div class="col-sm-6">
                                <div><span class="text-muted">Mã đơn đặt tiệc:</span> <strong>#DB<?= str_pad($payment['datban_id'], 4, '0', STR_PAD_LEFT) ?></strong></div>
                                <div><span class="text-muted">Khách hàng:</span> <strong><?= htmlspecialchars($payment['ten_khach'] ?? 'Khách') ?></strong></div>
                                <div><span class="text-muted">Số điện thoại:</span> <?= htmlspecialchars($payment['sdt_khach'] ?? '') ?></div>
                            </div>
                            <div class="col-sm-6">
                                <div><span class="text-muted">Bàn/Phòng:</span> <strong><?= htmlspecialchars($payment['ten_ban'] ?? 'Nhà hàng xếp bàn') ?></strong></div>
                                <div><span class="text-muted">Ngày tiệc:</span> <?= date('d/m/Y', strtotime($payment['ngay_dat'])) ?> (Ca <?= $payment['ca'] === 'trua' ? 'Trưa' : 'Tối' ?>)</div>
                                <div><span class="text-muted">Số khách:</span> <?= $payment['so_nguoi'] ?> người</div>
                            </div>
                        </div>
                    </div>

                    <!-- CHI TIẾT THANH TOÁN -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-secondary">Loại thanh toán:</span>
                            <span class="fw-bold text-dark">
                                <?php
                                $typeMap = [
                                    'dat_coc'          => 'Đặt cọc giữ chỗ',
                                    'toan_bo'          => 'Thanh toán toàn bộ 100%',
                                    'thanh_toan_them'  => 'Thanh toán thêm chi phí',
                                ];
                                echo $typeMap[$payment['loai_thanh_toan']] ?? 'Thanh toán';
                                ?>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-secondary">Phương thức thanh toán:</span>
                            <span class="fw-bold text-dark text-capitalize">
                                <?php
                                $pmMap = [
                                    'chuyen_khoan' => '<i class="bi bi-qr-code text-success me-1"></i>Chuyển khoản VietQR 24/7',
                                    'vnpay'        => '<i class="bi bi-qr-code text-primary me-1"></i>Cổng VNPAY-QR (Quét mã App Ngân Hàng)',
                                    'momo'         => '<i class="bi bi-wallet2 text-danger me-1"></i>Ví điện tử MoMo',
                                    'tien_mat'     => '<i class="bi bi-cash-stack text-secondary me-1"></i>Tiền mặt tại quầy',
                                ];
                                echo $pmMap[$payment['phuong_thuc']] ?? $payment['phuong_thuc'];
                                ?>
                            </span>
                        </div>
                        <?php if (!empty($payment['noi_dung'])): ?>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-secondary">Nội dung ghi chú:</span>
                                <span class="fw-semibold text-dark"><?= htmlspecialchars($payment['noi_dung']) ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-secondary">Trạng thái:</span>
                            <span class="badge <?= $payment['trang_thai'] === 'thanh_cong' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                <?= $payment['trang_thai'] === 'thanh_cong' ? 'Đã thanh toán thành công' : 'Chờ đối soát xác nhận' ?>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between py-3 border-bottom fs-5">
                            <span class="fw-bold text-dark">Số tiền đã giao dịch:</span>
                            <span class="fw-bold text-danger fs-4"><?= number_format($payment['so_tien'], 0, ',', '.') ?> đ</span>
                        </div>
                    </div>

                    <!-- TỔNG KẾT TÌNH TRẠNG ĐƠN -->
                    <div class="p-3 rounded-3 bg-light border small text-secondary mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Tổng chi phí đơn tiệc:</span>
                            <span class="fw-bold text-dark"><?= number_format($payment['tong_tien'], 0, ',', '.') ?> đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Tổng số tiền đã trả đến nay:</span>
                            <span class="fw-bold text-success"><?= number_format($payment['so_tien_da_tra'] ?? $payment['so_tien'], 0, ',', '.') ?> đ</span>
                        </div>
                        <?php 
                        $conLai = max(0, $payment['tong_tien'] - ($payment['so_tien_da_tra'] ?? $payment['so_tien'])); 
                        ?>
                        <div class="d-flex justify-content-between pt-1 border-top fw-bold">
                            <span>Số tiền còn lại phải thanh toán:</span>
                            <span class="text-danger"><?= number_format($conLai, 0, ',', '.') ?> đ</span>
                        </div>
                    </div>

                    <!-- CÁC NÚT ĐIỀU HƯỚNG -->
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <a href="<?= BASE_URL ?>?action=hoa-don&booking_id=<?= $payment['datban_id'] ?>" class="btn btn-primary-q fw-bold px-4 py-2">
                            <i class="bi bi-printer me-1"></i>Xem &amp; In Hóa Đơn
                        </a>
                        <a href="<?= BASE_URL ?>?action=lich-su-dat-ban" class="btn btn-outline-dark fw-semibold px-4 py-2">
                            <i class="bi bi-clock-history me-1"></i>Lịch sử đặt bàn
                        </a>
                        <a href="<?= BASE_URL ?>" class="btn btn-light border px-4 py-2 text-secondary">
                            <i class="bi bi-house me-1"></i>Trang chủ
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
