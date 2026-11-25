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
.vnpay-method-box {
    border: 2px solid #005baa;
    background: linear-gradient(135deg, #f0f7ff, #ffffff);
    border-radius: 14px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 4px 14px rgba(0, 91, 170, 0.08);
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
    border-color: #005baa;
}
.type-pill.active {
    border-color: #005baa;
    background: #005baa;
    color: #fff;
}
.type-pill.active .text-muted, .type-pill.active .text-danger, .type-pill.active .text-secondary {
    color: #fff !important;
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
                    <i class="bi bi-shield-check me-1"></i>Cổng Thanh Toán VNPAY Trực Tuyến An Toàn
                </span>
                <h1 class="h2 fw-bold mb-1">Thanh Toán &amp; Đặt Cọc Tiệc</h1>
                <p class="text-white-50 small mb-0">Hoàn tất đặt cọc hoặc thanh toán qua Cổng VNPAY-QR để nhà hàng chuẩn bị tiệc hoàn hảo nhất cho bạn.</p>
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

                    <?php if (empty($booking['ten_set']) && empty($dishes)): ?>
                        <div class="alert alert-light border small py-2 text-muted mb-0">
                            <i class="bi bi-info-circle me-1"></i>Khách chưa chọn món (Đặt cọc giữ bàn trước, gọi món tại nhà hàng).
                        </div>
                    <?php endif; ?>
                </div>

                <!-- BẢNG TỔNG KẾT TIỀN -->
                <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #FFF9F3, #FFF0E5); border: 1.5px dashed var(--terra, #C4603C);">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-secondary small">Tổng chi phí tiệc:</span>
                        <span class="fw-bold text-dark fs-6">
                            <?= $tongTien > 0 ? number_format($tongTien, 0, ',', '.') . ' đ' : '<span class="text-muted fst-italic">Chưa chốt món</span>' ?>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-secondary small">Đã thanh toán / cọc:</span>
                        <span class="fw-bold text-success fs-6">- <?= number_format($daTra, 0, ',', '.') ?> đ</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                        <span class="fw-bold text-dark">
                            <?= $tongTien > 0 ? 'Còn lại cần thanh toán:' : 'Số tiền cọc đề xuất:' ?>
                        </span>
                        <span class="fw-bold text-danger fs-4" id="displayRemainingAmount">
                            <?= number_format($tongTien > 0 ? $conLai : $suggestedDeposit, 0, ',', '.') ?> đ
                        </span>
                    </div>
                </div>

                <!-- Lịch sử các lần thanh toán trước -->
                <?php if (!empty($payments)): ?>
                    <div class="mt-3 pt-3 border-top">
                        <div class="small fw-bold text-muted mb-2"><i class="bi bi-clock-history me-1"></i>Lịch sử giao dịch:</div>
                        <div class="list-group list-group-flush small">
                            <?php foreach ($payments as $p): ?>
                                <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($p['ma_giao_dich']) ?></div>
                                        <div class="text-muted" style="font-size:0.75rem;">
                                            <?= date('d/m/Y H:i', strtotime($p['ngay_thanh_toan'])) ?>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-success">+<?= number_format($p['so_tien'], 0, ',', '.') ?> đ</div>
                                        <span class="badge bg-success" style="font-size:0.65rem;">Thành công</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <!-- ═══ CỘT PHẢI: FORM THANH TOÁN QUA VNPAY-QR ═══ -->
        <div class="col-lg-7 col-xl-7">
            <div class="payment-card p-4 p-md-5">
                
                <div class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">
                            <i class="bi bi-qr-code-scan text-primary me-2"></i>Thanh Toán Trực Tuyến VNPAY-QR
                        </h4>
                        <p class="text-muted small mb-0">Cổng thanh toán chính thức • Quét mã QR thật bằng mọi App Ngân Hàng &amp; MoMo</p>
                    </div>
                    <span class="badge bg-danger text-white px-3 py-2 fw-bold text-uppercase" style="letter-spacing: 0.8px; font-size: 0.72rem;">
                        VNPAY-QR
                    </span>
                </div>

                <?php if ($tongTien > 0 && $conLai <= 0): ?>
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

                    <form action="<?= BASE_URL ?>?action=xu-ly-thanh-toan" method="POST" id="checkoutForm">
                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                        <input type="hidden" name="phuong_thuc" value="vnpay">
                        <input type="hidden" name="loai_thanh_toan" id="loai_thanh_toan" value="<?= $daTra > 0 ? 'toan_bo' : 'dat_coc' ?>">
                        <input type="hidden" name="so_tien" id="so_tien_input" value="<?= $suggestedDeposit ?>">

                        <?php if ($tongTien <= 0): ?>
                            <!-- Thông báo đơn chưa chọn món -->
                            <div class="alert alert-info border-0 shadow-sm small mb-4 d-flex align-items-start gap-2">
                                <i class="bi bi-info-circle-fill fs-5 mt-1 text-info flex-shrink-0"></i>
                                <div>
                                    <strong>Đơn đặt bàn chưa chọn món ăn:</strong> 
                                    Quý khách vui lòng đặt cọc giữ bàn trước (gợi ý từ <strong>200.000đ</strong>) để nhà hàng ưu tiên sắp xếp bàn tiệc đẹp nhất cho bạn. 
                                    Khoản tiền cọc này sẽ được trừ trực tiếp vào hóa đơn khi quý khách dùng bữa tại nhà hàng.
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- 1. CHỌN SỐ TIỀN THANH TOÁN -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase" style="letter-spacing: 0.5px;">
                                1. Lựa chọn số tiền đặt cọc / thanh toán:
                            </label>
                            
                            <div class="row g-2">
                                <?php if ($tongTien > 0): ?>
                                    <!-- Khi đơn đã có tổng tiền món -->
                                    <?php if ($daTra == 0): ?>
                                        <div class="col-md-4">
                                            <div class="type-pill active" onclick="selectPaymentType('dat_coc', <?= $suggestedDeposit ?>)" id="pill_dat_coc">
                                                <div class="fw-bold fs-6">Đặt Cọc (30%)</div>
                                                <div class="fw-bold text-danger fs-5 my-1"><?= number_format($suggestedDeposit, 0, ',', '.') ?> đ</div>
                                                <div class="text-muted small" style="font-size:0.75rem;">Giữ chỗ &amp; chuẩn bị tiệc</div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="<?= $daTra == 0 ? 'col-md-4' : 'col-md-6' ?>">
                                        <div class="type-pill <?= $daTra > 0 ? 'active' : '' ?>" onclick="selectPaymentType('toan_bo', <?= $conLai ?>)" id="pill_toan_bo">
                                            <div class="fw-bold fs-6"><?= $daTra > 0 ? 'Thanh toán còn lại' : 'Toàn bộ 100%' ?></div>
                                            <div class="fw-bold text-danger fs-5 my-1"><?= number_format($conLai, 0, ',', '.') ?> đ</div>
                                            <div class="text-muted small" style="font-size:0.75rem;">Thanh toán hết chi phí</div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <!-- Khi đơn chưa chọn món: Gợi ý các mức cọc giữ bàn -->
                                    <div class="col-md-4">
                                        <div class="type-pill active" onclick="selectPaymentType('dat_coc', 200000)" id="pill_dat_coc">
                                            <div class="fw-bold fs-6">Cọc Tiêu Chuẩn</div>
                                            <div class="fw-bold text-danger fs-5 my-1">200.000 đ</div>
                                            <div class="text-muted small" style="font-size:0.75rem;">Giữ bàn chu đáo</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="type-pill" onclick="selectPaymentType('dat_coc', 500000)" id="pill_dat_coc_vip">
                                            <div class="fw-bold fs-6">Cọc Ưu Tiên (VIP)</div>
                                            <div class="fw-bold text-danger fs-5 my-1">500.000 đ</div>
                                            <div class="text-muted small" style="font-size:0.75rem;">Ưu tiên vị trí đẹp nhất</div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Option Tự nhập số tiền -->
                                <div class="<?= ($tongTien > 0 && $daTra > 0) ? 'col-md-6' : 'col-md-4' ?>">
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
                                           placeholder="Ví dụ: 300000" min="50000" step="10000" value="<?= $suggestedDeposit ?>">
                                    <span class="input-group-text bg-white fw-bold">VNĐ</span>
                                </div>
                                <div class="form-text small">Số tiền tối thiểu 50.000đ.</div>
                            </div>
                        </div>

                        <!-- 2. PHƯƠNG THỨC THANH TOÁN DUY NHẤT: VNPAY-QR -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase" style="letter-spacing: 0.5px;">
                                2. Phương thức thanh toán:
                            </label>

                            <div class="vnpay-method-box">
                                <div class="p-2 rounded bg-white shadow-sm border text-danger fs-3">
                                    <i class="bi bi-qr-code-scan"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="fw-bold text-dark fs-6">Cổng Thanh Toán Trực Tuyến VNPAY-QR</div>
                                        <span class="badge bg-danger text-white" style="font-size: 0.65rem;">QUÉT MÃ THẬT</span>
                                    </div>
                                    <div class="text-muted small mt-1">
                                        Hệ thống sẽ sinh mã QR Napas247 thật. Bạn dùng app Mobile Banking (MB, VCB, BIDV, Techcombank, Agribank...) hoặc ví MoMo để quét thanh toán tức thì.
                                    </div>
                                </div>
                                <div class="text-success fs-4">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Lời nhắn hoặc ghi chú -->
                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-dark">Lời nhắn hoặc ghi chú thanh toán (nếu có):</label>
                            <input type="text" name="noi_dung" class="form-control" placeholder="Ghi chú thêm cho thu ngân hoặc kế toán..." value="<?= htmlspecialchars($transferContent) ?>">
                        </div>

                        <!-- NÚT BẤM HOÀN TẤT THANH TOÁN -->
                        <div class="d-grid mb-2">
                            <button type="submit" class="btn btn-primary-q py-3 fw-bold fs-5 shadow" id="btnSubmitPayment">
                                <i class="bi bi-qr-code-scan me-2"></i>
                                Tiếp Tục Đến Cổng VNPAY-QR (<span id="submitAmountText"><?= number_format($suggestedDeposit, 0, ',', '.') ?> đ</span>)
                            </button>
                        </div>

                        <div class="text-center text-muted small mt-2">
                            <i class="bi bi-shield-lock-fill text-success me-1"></i>Chuyển đến màn hình Cổng VNPAY-QR chuyên dụng có đếm ngược 15 phút và mã QR thật.
                        </div>

                    </form>

                <?php endif; ?>

            </div>
        </div>

    </div>
</div>

<script>
let currentAmount = <?= $suggestedDeposit ?>;
const tongTien = <?= $tongTien ?>;
const conLai = <?= $conLai ?>;

function formatCurrency(number) {
    return new Intl.NumberFormat('vi-VN').format(number) + ' đ';
}

function selectPaymentType(type, amount) {
    document.querySelectorAll('.type-pill').forEach(el => el.classList.remove('active'));
    document.getElementById('loai_thanh_toan').value = type;

    const customBox = document.getElementById('customAmountBox');

    if (type === 'tuy_chon') {
        document.getElementById('pill_tuy_chon').classList.add('active');
        customBox.style.display = 'block';
        const customVal = parseFloat(document.getElementById('customAmountInput').value) || 200000;
        updateAmount(customVal);
    } else {
        customBox.style.display = 'none';
        if (amount === 500000 && document.getElementById('pill_dat_coc_vip')) {
            document.getElementById('pill_dat_coc_vip').classList.add('active');
        } else if (type === 'dat_coc') {
            document.getElementById('pill_dat_coc')?.classList.add('active');
        } else if (type === 'toan_bo') {
            document.getElementById('pill_toan_bo')?.classList.add('active');
        }
        updateAmount(amount);
    }
}

function updateAmount(amount) {
    if (amount <= 0) amount = 200000;
    currentAmount = amount;
    document.getElementById('so_tien_input').value = amount;
    document.getElementById('submitAmountText').innerText = formatCurrency(amount);
}

document.getElementById('customAmountInput')?.addEventListener('input', function(e) {
    let val = parseFloat(e.target.value) || 0;
    if (val < 0) val = 0;
    updateAmount(val);
});
</script>
