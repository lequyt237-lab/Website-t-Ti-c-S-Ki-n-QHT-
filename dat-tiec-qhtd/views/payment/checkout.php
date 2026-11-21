<style>
.checkout-hero {
    background: linear-gradient(135deg, var(--forest-dk) 0%, var(--forest) 100%);
    padding: 110px 0 45px;
    color: var(--white);
    position: relative;
    overflow: hidden;
}
.checkout-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 80% 20%, rgba(212,168,67,0.18), transparent 50%);
}
.payment-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid var(--stone, #e2e8f0);
    box-shadow: 0 4px 20px rgba(0,0,0,0.04);
}
.method-card {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 16px;
    cursor: pointer;
    transition: all 0.25s ease;
    display: flex;
    align-items: center;
    gap: 12px;
    background: #fff;
    margin-bottom: 12px;
}
.method-card:hover {
    border-color: #2C4A35;
    background: #fafaf9;
}
.method-card.active {
    border-color: #2C4A35;
    background: #f0fdf4;
    box-shadow: 0 4px 12px rgba(44, 74, 53, 0.1);
}
.method-card input[type="radio"] {
    width: 18px;
    height: 18px;
    accent-color: #2C4A35;
}
.bank-qr-box {
    background: #f8fafc;
    border: 1.5px dashed #cbd5e1;
    border-radius: 14px;
    padding: 20px;
    text-align: center;
}
.copy-badge {
    cursor: pointer;
    transition: all 0.2s;
}
.copy-badge:hover {
    opacity: 0.85;
    transform: scale(1.03);
}
.type-pill {
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    cursor: pointer;
    text-align: center;
    transition: all 0.25s;
    background: #fff;
}
.type-pill:hover {
    border-color: #2C4A35;
}
.type-pill.active {
    border-color: #2C4A35;
    background: #2C4A35;
    color: #fff;
}
.type-pill.active .text-muted {
    color: rgba(255,255,255,0.8) !important;
}
</style>

<!-- HERO HEADER -->
<section class="checkout-hero">
    <div class="container position-relative" style="z-index: 2;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small text-white-50 mb-2">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-white text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>?action=lich-su-dat-ban" class="text-white text-decoration-none">Lịch sử đặt bàn</a></li>
                <li class="breadcrumb-item active text-warning" aria-current="page">Thanh toán đặt tiệc</li>
            </ol>
        </nav>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-2" style="letter-spacing: 1.2px; font-size: 0.72rem;">
                    <i class="bi bi-shield-check me-1"></i>Cổng Thanh Toán Trực Tuyến An Toàn
                </span>
                <h1 class="h2 fw-bold mb-1">Thanh Toán &amp; Đặt Cọc Tiệc</h1>
                <p class="text-white-50 small mb-0">Hoàn tất đặt cọc hoặc thanh toán để nhà hàng ưu tiên chuẩn bị không gian &amp; nguyên liệu hảo hạng nhất cho bạn.</p>
            </div>
            <div>
                <a href="<?= BASE_URL ?>?action=lich-su-dat-ban" class="btn btn-outline-light btn-sm fw-semibold px-3 py-2">
                    <i class="bi bi-arrow-left me-1"></i>Quay lại lịch sử
                </a>
            </div>
        </div>
    </div>
</section>

<!-- THÔNG BÁO FLASH -->
<div class="container mt-4">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i>
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i>
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
</div>

<div class="container my-4 pb-5">
    <div class="row g-4">
        
        <!-- ═══ CỘT TRÁI: TÓM TẮT ĐƠN TIỆC ═══ -->
        <div class="col-lg-5 col-xl-5">
            <div class="payment-card p-4 sticky-top" style="top: 90px; z-index: 10;">
                <div class="d-flex justify-content-between align-items-center pb-3 mb-3 border-bottom">
                    <div>
                        <span class="text-muted small">Đơn đặt tiệc:</span>
                        <h5 class="fw-bold text-dark mb-0">#DB<?= str_pad($booking['id'], 4, '0', STR_PAD_LEFT) ?></h5>
                    </div>
                    <?php
                    $payStatusMap = [
                        'chua_thanh_toan' => ['class' => 'bg-danger-subtle text-danger border-danger-subtle', 'text' => 'Chưa thanh toán'],
                        'da_dat_coc'      => ['class' => 'bg-info-subtle text-info border-info-subtle',         'text' => 'Đã đặt cọc'],
                        'da_thanh_toan'   => ['class' => 'bg-success-subtle text-success border-success-subtle', 'text' => 'Đã thanh toán đủ'],
                    ];
                    $pSt = $payStatusMap[$booking['trang_thai_thanh_toan'] ?? 'chua_thanh_toan'] ?? $payStatusMap['chua_thanh_toan'];
                    ?>
                    <span class="badge border <?= $pSt['class'] ?> px-3 py-2 fw-semibold">
                        <i class="bi bi-credit-card me-1"></i><?= $pSt['text'] ?>
                    </span>
                </div>

                <!-- Thông tin khách & tiệc -->
                <div class="small mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted"><i class="bi bi-person me-1"></i>Khách hàng:</span>
                        <span class="fw-bold text-dark"><?= htmlspecialchars($booking['ten_khach'] ?? 'Khách đặt bàn') ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted"><i class="bi bi-telephone me-1"></i>Số điện thoại:</span>
                        <span class="fw-semibold text-dark"><?= htmlspecialchars($booking['sdt_khach'] ?? $booking['so_dien_thoai'] ?? '') ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted"><i class="bi bi-geo-alt me-1"></i>Bàn / Phòng:</span>
                        <span class="fw-semibold text-dark">
                            <?= !empty($booking['ten_ban']) ? htmlspecialchars($booking['ten_ban']) : '<span class="text-secondary fst-italic">Nhà hàng xếp bàn</span>' ?>
                            <?php if (!empty($booking['yeu_cau_phong_rieng'])): ?>
                                <span class="badge bg-warning-subtle text-warning">VIP</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted"><i class="bi bi-calendar3 me-1"></i>Ngày &amp; Giờ tiệc:</span>
                        <span class="fw-semibold text-dark">
                            <?= date('d/m/Y', strtotime($booking['ngay_dat'])) ?> 
                            (<?= !empty($booking['gio_den']) ? date('H:i', strtotime($booking['gio_den'])) : ($booking['ca'] === 'trua' ? '11:30' : '18:30') ?> - Ca <?= $booking['ca'] === 'trua' ? 'Trưa' : 'Tối' ?>)
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted"><i class="bi bi-people me-1"></i>Số lượng khách:</span>
                        <span class="fw-bold text-dark"><?= $booking['so_nguoi'] ?> người</span>
                    </div>
                    <?php if (!empty($booking['ten_dip'])): ?>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted"><i class="bi bi-balloon-heart me-1"></i>Dịp tổ chức:</span>
                            <span class="fw-semibold text-primary"><?= htmlspecialchars($booking['ten_dip']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Chi tiết thực đơn & món ăn -->
                <div class="border-top pt-3 mb-3">
                    <div class="fw-bold text-secondary small text-uppercase mb-2" style="font-size:0.75rem; letter-spacing:0.5px;">
                        <i class="bi bi-journal-text me-1"></i>Chi tiết thực đơn tiệc:
                    </div>
                    
                    <?php if (!empty($booking['ten_set'])): ?>
                        <div class="p-2 rounded bg-light border small mb-2 d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-stars text-warning me-1"></i>
                                <span class="fw-bold text-dark"><?= htmlspecialchars($booking['ten_set']) ?></span>
                            </div>
                            <span class="fw-bold text-danger"><?= number_format($booking['gia_set_luc_dat'] ?? 0, 0, ',', '.') ?> đ</span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($dishes)): ?>
                        <div class="small">
                            <?php foreach ($dishes as $d): ?>
                                <div class="d-flex justify-content-between py-1 border-bottom-dashed text-secondary">
                                    <span><?= htmlspecialchars($d['ten_mon']) ?> <span class="badge bg-light text-muted">x<?= $d['so_luong'] ?></span></span>
                                    <span class="fw-semibold"><?= number_format($d['don_gia'] * $d['so_luong'], 0, ',', '.') ?> đ</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($booking['loai_ban']) && $booking['loai_ban'] === 'phong_rieng' && !empty($booking['phu_phi'])): ?>
                        <div class="d-flex justify-content-between py-1 small text-secondary mt-1">
                            <span><i class="bi bi-star-fill text-warning me-1"></i>Phụ phí phòng VIP riêng tư:</span>
                            <span class="fw-semibold"><?= number_format($booking['phu_phi'], 0, ',', '.') ?> đ</span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- BẢNG TỔNG KẾT TIỀN -->
                <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #FFF9F3, #FFF0E5); border: 1.5px dashed var(--terra, #C4603C);">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-secondary small">Tổng chi phí tiệc:</span>
                        <span class="fw-bold text-dark fs-6"><?= number_format($tongTien, 0, ',', '.') ?> đ</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-secondary small">Đã thanh toán / cọc:</span>
                        <span class="fw-bold text-success fs-6">- <?= number_format($daTra, 0, ',', '.') ?> đ</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                        <span class="fw-bold text-dark">Còn lại cần thanh toán:</span>
                        <span class="fw-bold text-danger fs-4" id="displayRemainingAmount"><?= number_format($conLai, 0, ',', '.') ?> đ</span>
                    </div>
                </div>

                <!-- Lịch sử các lần thanh toán trước -->
                <?php if (!empty($payments)): ?>
                    <div class="mt-3 pt-3 border-top">
                        <div class="small fw-bold text-muted mb-2"><i class="bi bi-clock-history me-1"></i>Lịch sử giao dịch của đơn này:</div>
                        <div class="list-group list-group-flush small">
                            <?php foreach ($payments as $p): ?>
                                <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($p['ma_giao_dich']) ?></div>
                                        <div class="text-muted" style="font-size:0.75rem;">
                                            <?= date('d/m/Y H:i', strtotime($p['ngay_thanh_toan'])) ?> • 
                                            <span class="text-capitalize"><?= str_replace('_', ' ', $p['phuong_thuc']) ?></span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-success">+<?= number_format($p['so_tien'], 0, ',', '.') ?> đ</div>
                                        <span class="badge <?= $p['trang_thai'] === 'thanh_cong' ? 'bg-success' : 'bg-warning text-dark' ?>" style="font-size:0.65rem;">
                                            <?= $p['trang_thai'] === 'thanh_cong' ? 'Thành công' : 'Chờ xác nhận' ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <!-- ═══ CỘT PHẢI: FORM CHỌN THANH TOÁN ═══ -->
        <div class="col-lg-7 col-xl-7">
            <div class="payment-card p-4 p-md-5">
                
                <h4 class="fw-bold text-dark mb-1">
                    <i class="bi bi-credit-card-2-front-fill text-success me-2"></i>Chọn Hình Thức &amp; Phương Thức
                </h4>
                <p class="text-muted small mb-4">Vui lòng chọn số tiền muốn thanh toán và hình thức tiện lợi nhất cho bạn.</p>

                <?php if ($conLai <= 0): ?>
                    <!-- Đơn đã thanh toán đủ 100% -->
                    <div class="text-center py-5">
                        <div class="p-3 d-inline-block rounded-circle bg-success-subtle text-success mb-3">
                            <i class="bi bi-check2-circle fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-success">Đơn Tiệc Đã Được Thanh Toán Đủ!</h4>
                        <p class="text-muted small mb-4" style="max-width: 480px; margin: 0 auto;">
                            Đơn hàng #DB<?= $booking['id'] ?> của quý khách đã hoàn tất thanh toán 100% chi phí. 
                            Nhà hàng đã sẵn sàng đón tiếp quý khách chu đáo nhất!
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="<?= BASE_URL ?>?action=hoa-don&booking_id=<?= $booking['id'] ?>" class="btn btn-primary-q fw-bold px-4 py-2">
                                <i class="bi bi-receipt me-1"></i>Xem &amp; In Hóa Đơn
                            </a>
                            <a href="<?= BASE_URL ?>?action=lich-su-dat-ban" class="btn btn-outline-secondary fw-semibold px-4 py-2">
                                <i class="bi bi-arrow-left me-1"></i>Lịch sử đặt bàn
                            </a>
                        </div>
                    </div>
                <?php else: ?>

                    <form action="<?= BASE_URL ?>?action=xu-ly-thanh-toan" method="POST" enctype="multipart/form-data" id="checkoutForm">
                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                        <input type="hidden" name="loai_thanh_toan" id="loai_thanh_toan" value="<?= $daTra > 0 ? 'toan_bo' : 'dat_coc' ?>">
                        <input type="hidden" name="so_tien" id="so_tien_input" value="<?= $daTra > 0 ? $conLai : $suggestedDeposit ?>">

                        <!-- 1. CHỌN SỐ TIỀN THANH TOÁN -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase" style="letter-spacing: 0.5px;">
                                1. Lựa chọn số tiền thanh toán:
                            </label>
                            
                            <div class="row g-2">
                                <?php if ($daTra == 0): ?>
                                    <!-- Option 1: Đặt cọc giữ chỗ 30% -->
                                    <div class="col-md-4">
                                        <div class="type-pill active" onclick="selectPaymentType('dat_coc', <?= $suggestedDeposit ?>)" id="pill_dat_coc">
                                            <div class="fw-bold fs-6">Đặt Cọc (30%)</div>
                                            <div class="fw-bold text-danger fs-5 my-1"><?= number_format($suggestedDeposit, 0, ',', '.') ?> đ</div>
                                            <div class="text-muted small" style="font-size:0.75rem;">Giữ chỗ &amp; chuẩn bị tiệc</div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Option 2: Thanh toán toàn bộ / số tiền còn lại -->
                                <div class="<?= $daTra == 0 ? 'col-md-4' : 'col-md-6' ?>">
                                    <div class="type-pill <?= $daTra > 0 ? 'active' : '' ?>" onclick="selectPaymentType('toan_bo', <?= $conLai ?>)" id="pill_toan_bo">
                                        <div class="fw-bold fs-6"><?= $daTra > 0 ? 'Thanh toán còn lại' : 'Toàn bộ 100%' ?></div>
                                        <div class="fw-bold text-danger fs-5 my-1"><?= number_format($conLai, 0, ',', '.') ?> đ</div>
                                        <div class="text-muted small" style="font-size:0.75rem;">Thanh toán hết số tiền</div>
                                    </div>
                                </div>

                                <!-- Option 3: Tự nhập số tiền -->
                                <div class="<?= $daTra == 0 ? 'col-md-4' : 'col-md-6' ?>">
                                    <div class="type-pill" onclick="selectPaymentType('tuy_chon', 0)" id="pill_tuy_chon">
                                        <div class="fw-bold fs-6">Tùy Chọn Khác</div>
                                        <div class="fw-bold text-secondary fs-5 my-1">Tự nhập tiền</div>
                                        <div class="text-muted small" style="font-size:0.75rem;">Nhập số tiền bạn muốn</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Input số tiền tùy chọn (mặc định ẩn) -->
                            <div id="customAmountBox" class="mt-3 p-3 bg-light rounded-3 border" style="display: none;">
                                <label class="form-label small fw-semibold text-dark">Nhập số tiền bạn muốn chuyển (VNĐ):</label>
                                <div class="input-group">
                                    <input type="number" id="customAmountInput" class="form-control fw-bold fs-5 text-danger" 
                                           placeholder="Ví dụ: 1000000" min="50000" max="<?= $conLai ?>" step="10000">
                                    <span class="input-group-text bg-white fw-bold">VNĐ</span>
                                </div>
                                <div class="form-text small">Số tiền tối thiểu 50.000đ và không vượt quá số tiền còn lại (<?= number_format($conLai, 0, ',', '.') ?> đ).</div>
                            </div>
                        </div>

                        <!-- 2. CHỌN PHƯƠNG THỨC THANH TOÁN -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase" style="letter-spacing: 0.5px;">
                                2. Lựa chọn phương thức thanh toán:
                            </label>

                            <!-- Method 1: Chuyển khoản QR Napas247 (Mặc định) -->
                            <label class="method-card active" id="method_chuyen_khoan">
                                <input type="radio" name="phuong_thuc" value="chuyen_khoan" checked onchange="selectMethod('chuyen_khoan')">
                                <div class="p-2 rounded bg-success-subtle text-success fs-4">
                                    <i class="bi bi-qr-code-scan"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-dark">Chuyển khoản Ngân hàng (Quét mã VietQR 24/7)</div>
                                    <div class="text-muted small">Hỗ trợ tất cả app ngân hàng (MB, Vietcombank, BIDV, Techcombank...). Xử lý tự động nhanh chóng.</div>
                                </div>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Khuyên dùng</span>
                            </label>

                            <!-- Method 2: Cổng thanh toán VNPAY -->
                            <label class="method-card" id="method_vnpay">
                                <input type="radio" name="phuong_thuc" value="vnpay" onchange="selectMethod('vnpay')">
                                <div class="p-2 rounded bg-primary-subtle text-primary fs-4">
                                    <i class="bi bi-credit-card"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-dark">Cổng thanh toán trực tuyến VNPAY</div>
                                    <div class="text-muted small">Thanh toán qua thẻ ATM nội địa, Internet Banking, VNPAY-QR hoặc thẻ Quốc tế.</div>
                                </div>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">Online</span>
                            </label>

                            <!-- Method 3: Ví MoMo -->
                            <label class="method-card" id="method_momo">
                                <input type="radio" name="phuong_thuc" value="momo" onchange="selectMethod('momo')">
                                <div class="p-2 rounded bg-danger-subtle text-danger fs-4">
                                    <i class="bi bi-wallet2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-dark">Ví điện tử MoMo</div>
                                    <div class="text-muted small">Quét mã QR MoMo thanh toán tiện lợi và an toàn bằng ứng dụng MoMo trên điện thoại.</div>
                                </div>
                            </label>

                            <!-- Method 4: Tiền mặt tại quầy -->
                            <label class="method-card" id="method_tien_mat">
                                <input type="radio" name="phuong_thuc" value="tien_mat" onchange="selectMethod('tien_mat')">
                                <div class="p-2 rounded bg-secondary-subtle text-secondary fs-4">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-dark">Thanh toán tại quầy nhà hàng (Tiền mặt / Quẹt thẻ POS)</div>
                                    <div class="text-muted small">Bạn có thể đến trực tiếp nhà hàng QHT Banquet để thanh toán cọc hoặc thanh toán khi dùng tiệc.</div>
                                </div>
                            </label>
                        </div>

                        <!-- 3. KHỐI HIỂN THỊ VIETQR VÀ THÔNG TIN TÀI KHOẢN (Cho method chuyen_khoan) -->
                        <div id="section_chuyen_khoan" class="bank-qr-box mb-4">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="bi bi-qr-code text-success me-1"></i>Quét Mã VietQR Chuyển Khoản Nhanh 24/7
                            </h6>

                            <div class="row align-items-center g-3">
                                <div class="col-sm-5 text-center">
                                    <div class="bg-white p-2 rounded-3 shadow-sm d-inline-block border">
                                        <img id="qrImage" src="" alt="VietQR" class="img-fluid rounded" style="max-width: 190px;">
                                    </div>
                                    <div class="text-muted small mt-2" style="font-size: 0.72rem;">
                                        <i class="bi bi-camera me-1"></i>Mở app ngân hàng bất kỳ để quét
                                    </div>
                                </div>
                                <div class="col-sm-7 text-start">
                                    <div class="p-3 bg-white rounded-3 border small">
                                        <div class="mb-2">
                                            <div class="text-muted">Ngân hàng thụ hưởng:</div>
                                            <div class="fw-bold text-dark"><?= $bankConfig['bank_name'] ?></div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="text-muted">Số tài khoản:</div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold text-danger fs-6" id="bankAccNo"><?= $bankConfig['account_no'] ?></span>
                                                <span class="badge bg-light text-dark border copy-badge" onclick="copyText('<?= $bankConfig['account_no'] ?>', 'Số tài khoản')">
                                                    <i class="bi bi-copy me-1"></i>Copy
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="text-muted">Tên chủ tài khoản:</div>
                                            <div class="fw-bold text-dark"><?= $bankConfig['account_name'] ?></div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="text-muted">Số tiền chuyển:</div>
                                            <div class="fw-bold text-danger fs-6" id="qrDisplayAmount">0 đ</div>
                                        </div>
                                        <div>
                                            <div class="text-muted">Nội dung chuyển khoản (Bắt buộc):</div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold text-primary font-monospace" id="transferContentText"><?= $transferContent ?></span>
                                                <span class="badge bg-light text-dark border copy-badge" onclick="copyText('<?= $transferContent ?>', 'Nội dung')">
                                                    <i class="bi bi-copy me-1"></i>Copy
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload bill xác thực nếu có -->
                            <div class="mt-3 text-start bg-white p-3 rounded-3 border">
                                <label class="form-label small fw-semibold text-dark mb-1">
                                    <i class="bi bi-image text-primary me-1"></i>Tải lên ảnh chụp màn hình hóa đơn / bill chuyển khoản (nếu có):
                                </label>
                                <input type="file" name="anh_bien_lai" class="form-control form-control-sm" accept="image/*">
                                <div class="form-text small" style="font-size:0.75rem;">Giúp lễ tân nhà hàng đối soát và duyệt đơn nhanh hơn cho bạn.</div>
                            </div>
                        </div>

                        <!-- KHỐI HƯỚNG DẪN TIỀN MẶT (Cho method tien_mat) -->
                        <div id="section_tien_mat" class="p-3 bg-light rounded-3 border mb-4" style="display: none;">
                            <h6 class="fw-bold text-dark mb-2"><i class="bi bi-geo-alt-fill text-danger me-1"></i>Địa chỉ tiếp nhận thanh toán:</h6>
                            <p class="small text-secondary mb-2">
                                <strong>Nhà hàng tiệc gia đình QHT Banquet</strong><br>
                                Địa chỉ: 123 Đường ABC, Quận 1, TP. Hồ Chí Minh<br>
                                Hotline lễ tân: <strong>0123 456 789</strong> (Mở cửa 10:00 - 22:00 hàng ngày)
                            </p>
                            <div class="small text-muted fst-italic">
                                Quý khách vui lòng đọc mã đơn <strong>#DB<?= $booking['id'] ?></strong> hoặc SĐT <strong><?= htmlspecialchars($booking['so_dien_thoai'] ?? '') ?></strong> tại quầy thu ngân.
                            </div>
                        </div>

                        <!-- Ghi chú thanh toán -->
                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-dark">Lời nhắn hoặc ghi chú thanh toán (nếu có):</label>
                            <input type="text" name="noi_dung" class="form-control" placeholder="Ghi chú thêm cho thu ngân hoặc kế toán..." value="<?= htmlspecialchars($transferContent) ?>">
                        </div>

                        <!-- NÚT BẤM HOÀN TẤT THANH TOÁN -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary-q py-3 fw-bold fs-6 shadow" id="btnSubmitPayment">
                                <i class="bi bi-shield-lock-fill me-2"></i>
                                Xác Nhận Thanh Toán <span id="submitAmountText">0 đ</span>
                            </button>
                        </div>

                        <div class="text-center mt-3 small text-muted">
                            <i class="bi bi-lock me-1"></i>Giao dịch được mã hóa và bảo mật an toàn theo tiêu chuẩn bảo mật thanh toán.
                        </div>

                    </form>

                <?php endif; ?>

            </div>
        </div>

    </div>
</div>

<script>
let currentAmount = <?= $daTra > 0 ? $conLai : $suggestedDeposit ?>;
const bookingId = <?= $booking['id'] ?>;
const transferContent = "<?= addslashes($transferContent) ?>";
const bankId = "MB";
const bankAcc = "0988888888";
const bankName = "NHA HANG QHT BANQUET";

function formatCurrency(number) {
    return new Intl.NumberFormat('vi-VN').format(number) + ' đ';
}

function updateQR(amount) {
    currentAmount = amount;
    document.getElementById('so_tien_input').value = amount;
    document.getElementById('qrDisplayAmount').innerText = formatCurrency(amount);
    document.getElementById('submitAmountText').innerText = formatCurrency(amount);

    // Cập nhật URL ảnh VietQR
    const encodedContent = encodeURIComponent(transferContent);
    const encodedName = encodeURIComponent(bankName);
    const qrUrl = `https://img.vietqr.io/image/${bankId}-${bankAcc}-compact2.png?amount=${amount}&addInfo=${encodedContent}&accountName=${encodedName}`;
    document.getElementById('qrImage').src = qrUrl;
}

function selectPaymentType(type, amount) {
    document.querySelectorAll('.type-pill').forEach(el => el.classList.remove('active'));
    document.getElementById('loai_thanh_toan').value = type;

    const customBox = document.getElementById('customAmountBox');

    if (type === 'tuy_chon') {
        document.getElementById('pill_tuy_chon').classList.add('active');
        customBox.style.display = 'block';
        const customVal = parseFloat(document.getElementById('customAmountInput').value) || 0;
        if (customVal > 0) {
            updateQR(customVal);
        }
    } else {
        customBox.style.display = 'none';
        if (type === 'dat_coc') {
            document.getElementById('pill_dat_coc')?.classList.add('active');
        } else if (type === 'toan_bo') {
            document.getElementById('pill_toan_bo')?.classList.add('active');
        }
        updateQR(amount);
    }
}

document.getElementById('customAmountInput')?.addEventListener('input', function(e) {
    let val = parseFloat(e.target.value) || 0;
    const maxVal = <?= $conLai ?>;
    if (val > maxVal) {
        val = maxVal;
        e.target.value = maxVal;
    }
    updateQR(val);
});

function selectMethod(method) {
    document.querySelectorAll('.method-card').forEach(el => el.classList.remove('active'));
    const activeCard = document.getElementById('method_' + method);
    if (activeCard) {
        activeCard.classList.add('active');
    }

    const qrSection = document.getElementById('section_chuyen_khoan');
    const cashSection = document.getElementById('section_tien_mat');
    const btnSubmit = document.getElementById('btnSubmitPayment');

    if (method === 'chuyen_khoan') {
        qrSection.style.display = 'block';
        cashSection.style.display = 'none';
        btnSubmit.innerHTML = `<i class="bi bi-check-circle-fill me-2"></i>Tôi Đã Chuyển Khoản <span id="submitAmountText">${formatCurrency(currentAmount)}</span>`;
    } else if (method === 'vnpay') {
        qrSection.style.display = 'none';
        cashSection.style.display = 'none';
        btnSubmit.innerHTML = `<i class="bi bi-credit-card-2-front-fill me-2"></i>Tiếp Tục Đến Cổng VNPAY <span id="submitAmountText">${formatCurrency(currentAmount)}</span>`;
    } else if (method === 'momo') {
        qrSection.style.display = 'none';
        cashSection.style.display = 'none';
        btnSubmit.innerHTML = `<i class="bi bi-wallet2 me-2"></i>Thanh Toán Qua MoMo <span id="submitAmountText">${formatCurrency(currentAmount)}</span>`;
    } else if (method === 'tien_mat') {
        qrSection.style.display = 'none';
        cashSection.style.display = 'block';
        btnSubmit.innerHTML = `<i class="bi bi-geo-alt-fill me-2"></i>Xác Nhận Hẹn Thanh Toán Tại Quầy`;
    }
}

function copyText(text, label) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Đã sao chép ' + label + ': ' + text);
    }).catch(err => {
        prompt('Sao chép thủ công ' + label + ':', text);
    });
}

// Khởi tạo QR ban đầu
updateQR(currentAmount);
</script>
