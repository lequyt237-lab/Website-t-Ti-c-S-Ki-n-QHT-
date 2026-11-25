<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Cổng Thanh Toán Trực Tuyến VNPAY' ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #1e293b;
        }
        .vnpay-header {
            background: #ffffff;
            border-bottom: 2px solid #005baa;
            padding: 16px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .vnpay-logo {
            font-weight: 800;
            font-size: 1.6rem;
            color: #ed1c24;
            letter-spacing: -0.5px;
        }
        .vnpay-logo span {
            color: #005baa;
        }
        .vnpay-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
        }
        .bank-badge {
            cursor: pointer;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 12px;
            text-align: center;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
            background: #f8fafc;
        }
        .bank-badge:hover, .bank-badge.active {
            border-color: #005baa;
            background: #eff6ff;
            color: #005baa;
        }
        .btn-vnpay-pay {
            background: linear-gradient(135deg, #005baa, #0077d8);
            border: none;
            color: #fff;
            font-weight: 700;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.25s;
        }
        .btn-vnpay-pay:hover {
            background: linear-gradient(135deg, #004b8c, #005baa);
            color: #fff;
            transform: translateY(-1px);
        }
        .card-preview {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            border-radius: 12px;
            color: #fff;
            padding: 20px;
            position: relative;
            box-shadow: 0 10px 25px rgba(30, 58, 138, 0.25);
            margin-bottom: 24px;
        }
    </style>
</head>
<body>

<!-- VNPAY HEADER -->
<header class="vnpay-header">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <div class="vnpay-logo">
                VN<span>PAY</span>
                <span class="badge bg-danger ms-2" style="font-size: 0.65rem; vertical-align: middle;">SANDBOX TEST</span>
            </div>
            <div class="vr d-none d-sm-block" style="height: 24px;"></div>
            <span class="text-muted small d-none d-sm-inline">Cổng thanh toán điện tử an toàn</span>
        </div>
        <div class="text-end">
            <span class="badge bg-light text-secondary border">
                <i class="bi bi-shield-lock-fill text-success me-1"></i>SSL 256-Bit
            </span>
        </div>
    </div>
</header>

<div class="container my-4 my-md-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- THÔNG TIN ĐƠN HÀNG VNPAY -->
            <div class="vnpay-card p-4 mb-4">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <div class="text-muted small text-uppercase fw-semibold mb-1">Đơn vị chấp nhận thanh toán:</div>
                        <h5 class="fw-bold text-dark mb-2">NHÀ HÀNG TIỆC GIA ĐÌNH QHT BANQUET</h5>
                        <div class="small text-secondary">
                            Mã giao dịch VNPAY: <strong class="text-primary"><?= htmlspecialchars($code) ?></strong><br>
                            Mã đơn tiệc: <strong>#DB<?= $booking['id'] ?></strong> (<?= htmlspecialchars($booking['ten_khach'] ?? 'Khách') ?>)
                        </div>
                    </div>
                    <div class="col-md-5 text-md-end mt-3 mt-md-0 pt-3 pt-md-0 border-top border-md-0">
                        <div class="text-muted small">Số tiền thanh toán:</div>
                        <div class="fs-2 fw-bold text-danger"><?= number_format($amount, 0, ',', '.') ?> đ</div>
                        <div class="badge bg-info-subtle text-info border border-info-subtle">
                            <?= $type === 'toan_bo' ? 'Thanh toán toàn bộ' : 'Đặt cọc tiệc' ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FORM THANH TOÁN THẺ NỘI ĐỊA TEST -->
            <div class="vnpay-card p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-credit-card-2-back text-primary me-2"></i>Thanh toán qua Thẻ ATM / Tài khoản Nội địa
                    </h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="fillTestCard()">
                        <i class="bi bi-lightning-charge-fill me-1"></i>Tự động điền thẻ TEST
                    </button>
                </div>

                <p class="text-muted small mb-4">
                    Chọn ngân hàng phát hành thẻ của bạn (môi trường mô phỏng thử nghiệm):
                </p>

                <!-- Lựa chọn ngân hàng -->
                <div class="row g-2 mb-4">
                    <div class="col-3 col-sm-2">
                        <div class="bank-badge active" onclick="selectBank('NCB')">NCB</div>
                    </div>
                    <div class="col-3 col-sm-2">
                        <div class="bank-badge" onclick="selectBank('VCB')">Vietcombank</div>
                    </div>
                    <div class="col-3 col-sm-2">
                        <div class="bank-badge" onclick="selectBank('MB')">MBBank</div>
                    </div>
                    <div class="col-3 col-sm-2">
                        <div class="bank-badge" onclick="selectBank('TCB')">Techcombank</div>
                    </div>
                    <div class="col-3 col-sm-2">
                        <div class="bank-badge" onclick="selectBank('CTG')">VietinBank</div>
                    </div>
                    <div class="col-3 col-sm-2">
                        <div class="bank-badge" onclick="selectBank('BIDV')">BIDV</div>
                    </div>
                </div>

                <!-- Thẻ mô phỏng visual -->
                <div class="card-preview">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold fs-5" id="cardBankLogo">NCB Bank</span>
                        <i class="bi bi-wifi fs-4"></i>
                    </div>
                    <div class="fs-4 font-monospace tracking-wide mb-3" id="displayCardNumber">9704 1985 2619 1432 198</div>
                    <div class="d-flex justify-content-between text-uppercase small">
                        <div>
                            <div style="font-size: 0.65rem; opacity: 0.8;">Chủ thẻ</div>
                            <div class="fw-bold" id="displayName">NGUYEN VAN A</div>
                        </div>
                        <div>
                            <div style="font-size: 0.65rem; opacity: 0.8;">Hạn thẻ</div>
                            <div class="fw-bold" id="displayExpiry">07/15</div>
                        </div>
                    </div>
                </div>

                <!-- Form thông tin thẻ -->
                <form action="<?= BASE_URL ?>?action=vnpay-callback" method="POST" id="vnpayForm">
                    <input type="hidden" name="action" value="vnpay-callback">
                    <input type="hidden" name="booking_id" value="<?= $bookingId ?>">
                    <input type="hidden" name="amount" value="<?= $amount ?>">
                    <input type="hidden" name="type" value="<?= $type ?>">
                    <input type="hidden" name="code" value="<?= htmlspecialchars($code) ?>">
                    <input type="hidden" name="bank_code" id="bank_code" value="NCB">
                    <input type="hidden" name="response_code" value="00">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Số thẻ ATM / Số tài khoản:</label>
                        <input type="text" class="form-control form-control-lg font-monospace" id="cardNumberInput" 
                               value="9704198526191432198" required>
                        <div class="form-text small">Thẻ test sandbox VNPAY: <code>9704198526191432198</code></div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label small fw-bold">Tên in trên thẻ (không dấu):</label>
                            <input type="text" class="form-control form-control-lg text-uppercase" id="cardNameInput" 
                                   value="NGUYEN VAN A" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">Ngày phát hành (MM/YY):</label>
                            <input type="text" class="form-control form-control-lg font-monospace" id="cardExpiryInput" 
                                   value="07/15" placeholder="MM/YY" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Mã OTP xác thực (Mặc định: 123456):</label>
                        <input type="text" class="form-control form-control-lg font-monospace text-center" 
                               value="123456" maxlength="6" style="letter-spacing: 6px; font-size: 1.3rem;">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-vnpay-pay fs-6 shadow">
                            <i class="bi bi-lock-fill me-2"></i>Xác Nhận &amp; Thanh Toán <?= number_format($amount, 0, ',', '.') ?> đ
                        </button>
                        <a href="<?= BASE_URL ?>?action=thanh-toan&id=<?= $bookingId ?>" class="btn btn-link text-muted text-decoration-none small">
                            <i class="bi bi-x-circle me-1"></i>Hủy bỏ giao dịch và quay lại nhà hàng
                        </a>
                    </div>
                </form>

            </div>

            <div class="text-center text-muted small mt-4">
                © <?= date('Y') ?> Cổng thanh toán điện tử VNPAY. Môi trường Giả lập &amp; Đào tạo (Sandbox Environment).
            </div>

        </div>
    </div>
</div>

<script>
function selectBank(code) {
    document.querySelectorAll('.bank-badge').forEach(b => b.classList.remove('active'));
    event.currentTarget.classList.add('active');
    document.getElementById('bank_code').value = code;
    document.getElementById('cardBankLogo').innerText = code + ' Bank';
}

function fillTestCard() {
    document.getElementById('cardNumberInput').value = '9704198526191432198';
    document.getElementById('cardNameInput').value = 'NGUYEN VAN A';
    document.getElementById('cardExpiryInput').value = '07/15';
    document.getElementById('displayCardNumber').innerText = '9704 1985 2619 1432 198';
    document.getElementById('displayName').innerText = 'NGUYEN VAN A';
    document.getElementById('displayExpiry').innerText = '07/15';
}
</script>
</body>
</html>
