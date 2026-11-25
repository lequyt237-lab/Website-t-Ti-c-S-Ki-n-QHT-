<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Cổng Thanh Toán Trực Tuyến VNPAY-QR - QHT Banquet' ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --vnpay-blue: #005baa;
            --vnpay-blue-dk: #004280;
            --vnpay-red: #ed1c24;
            --vietqr-teal: #00a896;
            --bg-page: #f4f7fb;
            --text-dark: #0f172a;
        }
        body {
            background-color: var(--bg-page);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .vnpay-header {
            background: #ffffff;
            border-bottom: 3px solid var(--vnpay-blue);
            padding: 14px 0;
            box-shadow: 0 4px 15px rgba(0, 91, 170, 0.08);
        }
        .vnpay-logo-badge {
            font-weight: 800;
            font-size: 1.6rem;
            color: var(--vnpay-red);
            letter-spacing: -0.5px;
            display: inline-flex;
            align-items: center;
        }
        .vnpay-logo-badge span {
            color: var(--vnpay-blue);
        }
        .vnpay-badge-qr {
            background: linear-gradient(135deg, #ed1c24, #f59e0b);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 800;
            padding: 4px 8px;
            border-radius: 6px;
            margin-left: 8px;
            letter-spacing: 0.5px;
        }
        .vietqr-badge {
            background: linear-gradient(135deg, #005baa, #00a896);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 800;
            padding: 4px 8px;
            border-radius: 6px;
            letter-spacing: 0.5px;
        }
        .timer-badge {
            background: #fff;
            border: 1.5px solid #e2e8f0;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #dc2626;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        }
        .gateway-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        .qr-stage-wrapper {
            background: linear-gradient(180deg, #f8fafc 0%, #edf2f7 100%);
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
            padding: 24px 20px;
            position: relative;
            text-align: center;
        }
        .qr-frame {
            background: #ffffff;
            padding: 12px;
            border-radius: 16px;
            display: inline-block;
            box-shadow: 0 8px 24px rgba(0, 91, 170, 0.12);
            border: 2px solid #e0e7ff;
            position: relative;
            overflow: hidden;
        }
        /* Scanline animation */
        .qr-frame::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, #ed1c24, #005baa, transparent);
            box-shadow: 0 0 12px #ed1c24;
            animation: scanLine 3s ease-in-out infinite alternate;
        }
        @keyframes scanLine {
            0% { top: 6%; opacity: 0.2; }
            50% { opacity: 0.9; }
            100% { top: 92%; opacity: 0.2; }
        }
        .btn-vnpay-pay {
            background: linear-gradient(135deg, var(--vnpay-blue) 0%, var(--vnpay-blue-dk) 100%);
            border: none;
            color: #fff;
            font-weight: 700;
            padding: 14px;
            border-radius: 10px;
            transition: all 0.25s ease;
            box-shadow: 0 6px 18px rgba(0, 91, 170, 0.3);
        }
        .btn-vnpay-pay:hover {
            background: linear-gradient(135deg, var(--vnpay-blue-dk) 0%, #002d5a 100%);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(0, 91, 170, 0.4);
        }
        .copy-pill {
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #334155;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .copy-pill:hover {
            background: #eff6ff;
            border-color: var(--vnpay-blue);
            color: var(--vnpay-blue);
        }
        .step-num {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--vnpay-blue);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .bank-brand-pill {
            display: inline-block;
            padding: 4px 10px;
            font-size: 0.72rem;
            font-weight: 700;
            border-radius: 6px;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>

<!-- VNPAY HEADER -->
<header class="vnpay-header sticky-top">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <div class="vnpay-logo-badge">
                VN<span>PAY</span>
                <span class="vnpay-badge-qr">QR</span>
            </div>
            <span class="vietqr-badge ms-1 d-none d-sm-inline">NAPAS 247</span>
            <div class="vr d-none d-md-block mx-2" style="height: 22px;"></div>
            <span class="text-muted small d-none d-md-inline" style="font-size: 0.8rem;">Cổng Thanh Toán Trực Tuyến Quốc Gia</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="timer-badge" id="timerBox">
                <i class="bi bi-stopwatch text-danger"></i>
                <span>Hết hạn sau: </span>
                <span id="countdownTimer" class="font-monospace">15:00</span>
            </div>
            <span class="badge bg-light text-secondary border px-2 py-2 d-none d-lg-inline" style="font-size: 0.75rem;">
                <i class="bi bi-shield-check text-success me-1"></i>SSL 256-Bit
            </span>
        </div>
    </div>
</header>

<!-- MAIN CONTENT -->
<main class="flex-grow-1 my-4 my-md-5">
    <div class="container">
        
        <!-- HÀNG TIÊU ĐỀ -->
        <div class="text-center mb-4">
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="bi bi-qr-code-scan text-primary me-2"></i>Thanh Toán Bằng Mã VNPAY-QR / VietQR
            </h1>
            <p class="text-secondary small mb-0">
                Mở ứng dụng Mobile Banking trên điện thoại của bất kỳ ngân hàng nào để quét mã thanh toán thật trực tiếp.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            
            <!-- ═══ CỘT TRÁI: THÔNG TIN ĐƠN ĐẶT TIỆC ═══ -->
            <div class="col-lg-5 col-xl-5">
                <div class="gateway-card p-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <!-- ĐƠN VỊ THỤ HƯỞNG -->
                        <div class="p-3 rounded-3 mb-3" style="background: linear-gradient(135deg, #f0f7ff, #e6f0fa); border-left: 4px solid var(--vnpay-blue);">
                            <div class="text-muted small text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Đơn vị chấp nhận thanh toán:</div>
                            <h5 class="fw-bold text-dark mb-1">NHÀ HÀNG TIỆC GIA ĐÌNH QHT BANQUET</h5>
                            <div class="small text-secondary" style="font-size: 0.78rem;">
                                <i class="bi bi-geo-alt me-1 text-danger"></i>Hệ thống Nhà Hàng Tiệc Sang Trọng QHT
                            </div>
                        </div>

                        <!-- CHI TIẾT ĐƠN HÀNG -->
                        <div class="small mb-3">
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted"><i class="bi bi-receipt me-1"></i>Mã đơn tiệc:</span>
                                <span class="fw-bold text-dark">#DB<?= str_pad($booking['id'], 4, '0', STR_PAD_LEFT) ?></span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted"><i class="bi bi-person me-1"></i>Khách hàng:</span>
                                <span class="fw-semibold text-dark"><?= htmlspecialchars($booking['ten_khach'] ?? 'Khách đặt bàn') ?></span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted"><i class="bi bi-telephone me-1"></i>Số điện thoại:</span>
                                <span class="fw-semibold text-dark"><?= htmlspecialchars($booking['sdt_khach'] ?? $booking['so_dien_thoai'] ?? '') ?></span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted"><i class="bi bi-calendar-event me-1"></i>Ngày tổ chức:</span>
                                <span class="fw-semibold text-dark">
                                    <?= date('d/m/Y', strtotime($booking['ngay_dat'])) ?> 
                                    (Ca <?= $booking['ca'] === 'trua' ? 'Trưa' : 'Tối' ?>)
                                </span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted"><i class="bi bi-tag me-1"></i>Loại thanh toán:</span>
                                <span class="badge <?= $type === 'toan_bo' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-dark border border-warning-subtle' ?> fw-semibold">
                                    <?= $type === 'toan_bo' ? 'Thanh toán toàn bộ 100%' : 'Đặt cọc tiệc giữ chỗ' ?>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted"><i class="bi bi-upc-scan me-1"></i>Mã giao dịch VNPAY:</span>
                                <span class="fw-bold text-primary font-monospace"><?= htmlspecialchars($code) ?></span>
                            </div>
                        </div>

                        <!-- KHỐI SỐ TIỀN THANH TOÁN -->
                        <div class="p-3 rounded-3 text-center mb-3" style="background: linear-gradient(135deg, #FFF5F5, #FFE8E8); border: 2px dashed #FCA5A5;">
                            <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Số tiền cần thanh toán</div>
                            <div class="fs-2 fw-bold text-danger my-1 font-monospace" id="displayAmountText">
                                <?= number_format($amount, 0, ',', '.') ?> đ
                            </div>
                            <div class="text-muted small" style="font-size: 0.75rem;">
                                Miễn phí phí giao dịch chuyển khoản liên ngân hàng 24/7
                            </div>
                        </div>

                        <!-- THÔNG TIN CHUYỂN KHOẢN ĐỐI SOÁT THỦ CÔNG (NẾU CẦN COPY) -->
                        <div class="p-3 bg-light rounded-3 border small">
                            <div class="fw-bold text-secondary mb-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                                <i class="bi bi-info-circle me-1"></i>THÔNG TIN TÀI KHOẢN THỤ HƯỞNG (ĐỐI SOÁT):
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted">Ngân hàng:</span>
                                <span class="fw-bold text-dark"><?= $bankConfig['bank_name'] ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted">Số tài khoản:</span>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="fw-bold text-danger font-monospace fs-6"><?= $bankConfig['account_no'] ?></span>
                                    <span class="copy-pill" onclick="copyText('<?= $bankConfig['account_no'] ?>', 'Số tài khoản')">
                                        <i class="bi bi-copy"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted">Chủ tài khoản:</span>
                                <span class="fw-bold text-dark"><?= $bankConfig['account_name'] ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Nội dung CK:</span>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="fw-bold text-primary font-monospace"><?= htmlspecialchars($transferContent) ?></span>
                                    <span class="copy-pill" onclick="copyText('<?= $transferContent ?>', 'Nội dung CK')">
                                        <i class="bi bi-copy"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nút quay lại -->
                    <div class="mt-4 pt-3 border-top text-center">
                        <a href="<?= BASE_URL ?>?action=thanh-toan&id=<?= $bookingId ?>" class="text-decoration-none text-muted small fw-semibold">
                            <i class="bi bi-arrow-left me-1"></i>Hủy giao dịch &amp; quay lại chọn phương thức khác
                        </a>
                    </div>
                </div>
            </div>

            <!-- ═══ CỘT PHẢI: KHU VỰC QUÉT MÃ QR THẬT ═══ -->
            <div class="col-lg-7 col-xl-7">
                <div class="gateway-card p-4 p-md-5">
                    
                    <div class="text-center mb-3">
                        <span class="badge bg-danger text-white px-3 py-1 fw-bold text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            <i class="bi bi-lightning-charge-fill me-1"></i>Quét Thật Bằng App Ngân Hàng
                        </span>
                        <h4 class="fw-bold text-dark mb-1">Mã VNPAY-QR / VietQR Napas 24/7</h4>
                        <p class="text-muted small mb-0">Hỗ trợ 100% ứng dụng Ngân hàng (MB, VCB, BIDV, Techcombank...) &amp; Ví MoMo</p>
                    </div>

                    <!-- KHUNG MÃ QR THẬT ĐỘNG -->
                    <div class="qr-stage-wrapper mb-4">
                        <div class="qr-frame mb-3">
                            <img src="<?= $qrUrl ?>" 
                                 id="vnpayQrImg" 
                                 class="img-fluid rounded" 
                                 alt="Mã VNPAY-QR Thật" 
                                 style="width: 260px; height: 260px; object-fit: contain; display: block;"
                                 crossorigin="anonymous">
                        </div>

                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary fw-semibold px-3 py-1" onclick="downloadQr()">
                                <i class="bi bi-download me-1"></i>Tải Mã QR Về Máy
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary fw-semibold px-3 py-1" onclick="reloadQr()">
                                <i class="bi bi-arrow-clockwise me-1"></i>Làm Mới Mã QR
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success fw-semibold px-3 py-1" onclick="copyText('<?= $transferContent ?>', 'Nội dung')">
                                <i class="bi bi-clipboard-check me-1"></i>Sao Chép Nội Dung
                            </button>
                        </div>
                    </div>

                    <!-- 3 BƯỚC HƯỚNG DẪN QUÉT THANH TOÁN -->
                    <div class="mb-4 p-3 bg-light rounded-3 border">
                        <div class="fw-bold text-dark small mb-2 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            <i class="bi bi-phone me-1 text-primary"></i>Hướng dẫn thanh toán nhanh 3 bước:
                        </div>
                        <div class="row g-2 small">
                            <div class="col-md-4">
                                <div class="d-flex align-items-start gap-2">
                                    <div class="step-num">1</div>
                                    <div class="text-secondary" style="font-size: 0.8rem;">
                                        Mở app <strong>Ngân hàng</strong> hoặc <strong>MoMo</strong> trên điện thoại.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-start gap-2">
                                    <div class="step-num">2</div>
                                    <div class="text-secondary" style="font-size: 0.8rem;">
                                        Chọn tính năng <strong>Quét QR / QR Pay</strong> và hướng camera vào mã QR.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-start gap-2">
                                    <div class="step-num">3</div>
                                    <div class="text-secondary" style="font-size: 0.8rem;">
                                        Kiểm tra số tiền &amp; nội dung được tự động điền sẵn, nhấn <strong>Xác nhận</strong>.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FORM XÁC NHẬN ĐÃ CHUYỂN KHOẢN -->
                    <form action="<?= BASE_URL ?>?action=vnpay-callback" method="POST" enctype="multipart/form-data" id="vnpayConfirmForm">
                        <input type="hidden" name="booking_id" value="<?= $bookingId ?>">
                        <input type="hidden" name="amount" value="<?= $amount ?>">
                        <input type="hidden" name="type" value="<?= $type ?>">
                        <input type="hidden" name="code" value="<?= htmlspecialchars($code) ?>">
                        <input type="hidden" name="noi_dung" value="<?= htmlspecialchars($transferContent) ?>">

                        <!-- Tải ảnh biên lai đối soát (tùy chọn) -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark mb-1">
                                <i class="bi bi-image text-primary me-1"></i>Tải lên ảnh chụp màn hình bill chuyển khoản thành công (nếu có):
                            </label>
                            <input type="file" name="anh_bien_lai" class="form-control form-control-sm" accept="image/*">
                            <div class="form-text small" style="font-size: 0.72rem;">
                                Ảnh bill giúp thu ngân nhà hàng đối soát và duyệt đơn tức thì cho quý khách.
                            </div>
                        </div>

                        <!-- NÚT BẤM HOÀN TẤT THANH TOÁN -->
                        <div class="d-grid mb-2">
                            <button type="submit" class="btn btn-vnpay-pay fs-6 shadow">
                                <i class="bi bi-check2-circle me-2 fs-5 align-middle"></i>
                                Tôi Đã Quét Mã &amp; Hoàn Tất Chuyển Tiền
                            </button>
                        </div>

                        <div class="text-center text-muted small" style="font-size: 0.75rem;">
                            <i class="bi bi-shield-lock-fill text-success me-1"></i>Hệ thống tự động bảo vệ giao dịch của bạn theo tiêu chuẩn EMVCo &amp; PCI-DSS.
                        </div>
                    </form>

                </div>
            </div>

        </div>

        <!-- DẢI LOGO CÁC NGÂN HÀNG HỖ TRỢ -->
        <div class="mt-5 pt-4 border-top text-center">
            <div class="text-muted small fw-semibold text-uppercase mb-3" style="font-size: 0.72rem; letter-spacing: 0.8px;">
                Hỗ trợ quét mã thanh toán từ hơn 40+ Ngân Hàng &amp; Ví Điện Tử liên kết Napas 24/7
            </div>
            <div class="d-flex flex-wrap justify-content-center gap-2">
                <span class="bank-brand-pill">MBBank</span>
                <span class="bank-brand-pill">Vietcombank</span>
                <span class="bank-brand-pill">Techcombank</span>
                <span class="bank-brand-pill">BIDV</span>
                <span class="bank-brand-pill">VietinBank</span>
                <span class="bank-brand-pill">Agribank</span>
                <span class="bank-brand-pill">ACB</span>
                <span class="bank-brand-pill">TPBank</span>
                <span class="bank-brand-pill">VPBank</span>
                <span class="bank-brand-pill">MSB</span>
                <span class="bank-brand-pill">HDBank</span>
                <span class="bank-brand-pill">Sacombank</span>
                <span class="bank-brand-pill">MoMo</span>
                <span class="bank-brand-pill">ZaloPay</span>
                <span class="bank-brand-pill">Viettel Money</span>
            </div>
        </div>

    </div>
</main>

<!-- FOOTER -->
<footer class="bg-white py-3 border-top mt-auto">
    <div class="container text-center text-muted small">
        <p class="mb-0">
            © <?= date('Y') ?> Cổng Thanh Toán Trực Tuyến VNPAY-QR &amp; VietQR Napas247 • Nhà Hàng Tiệc QHT Banquet. 
            Mọi giao dịch đều được mã hóa an toàn.
        </p>
    </div>
</footer>

<script>
// Đếm ngược phiên thanh toán 15 phút
let countdownSeconds = 15 * 60;
const timerEl = document.getElementById('countdownTimer');
const timerBox = document.getElementById('timerBox');

const timerInterval = setInterval(function() {
    countdownSeconds--;
    if (countdownSeconds <= 0) {
        clearInterval(timerInterval);
        timerEl.innerText = "Hết hạn";
        alert("Phiên thanh toán đã hết hạn (15 phút). Vui lòng tải lại trang để lấy mã mới!");
        window.location.href = "<?= BASE_URL ?>?action=thanh-toan&id=<?= $bookingId ?>";
        return;
    }
    const m = Math.floor(countdownSeconds / 60);
    const s = countdownSeconds % 60;
    timerEl.innerText = (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;

    // Cảnh báo đỏ khi dưới 2 phút
    if (countdownSeconds <= 120) {
        timerBox.style.borderColor = '#dc2626';
        timerBox.style.background = '#fff5f5';
    }
}, 1000);

// Sao chép nội dung
function copyText(text, label) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Đã sao chép ' + label + ': ' + text);
        }).catch(() => {
            prompt('Sao chép ' + label + ':', text);
        });
    } else {
        prompt('Sao chép ' + label + ':', text);
    }
}

// Tải ảnh mã QR về máy
function downloadQr() {
    const qrImg = document.getElementById('vnpayQrImg');
    const a = document.createElement('a');
    a.href = qrImg.src;
    a.download = 'VNPAY-QR-DB<?= $booking['id'] ?>.png';
    a.target = '_blank';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

// Làm mới mã QR
function reloadQr() {
    const qrImg = document.getElementById('vnpayQrImg');
    const currentSrc = qrImg.src.split('&t=')[0];
    qrImg.src = currentSrc + '&t=' + new Date().getTime();
}
</script>
</body>
</html>