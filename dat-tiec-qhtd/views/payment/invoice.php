<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Hóa Đơn Thanh Toán - QHT Banquet' ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Times New Roman', Times, serif, sans-serif;
            color: #1e293b;
            padding: 30px 0;
        }
        .invoice-box {
            max-width: 850px;
            margin: auto;
            padding: 40px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border-radius: 8px;
            position: relative;
        }
        .invoice-title {
            font-family: 'Times New Roman', serif;
            letter-spacing: 1px;
            font-weight: 800;
            color: #2C4A35;
        }
        .stamp-box {
            position: absolute;
            right: 50px;
            top: 130px;
            border: 3px solid #16a34a;
            color: #16a34a;
            padding: 6px 18px;
            font-size: 1.1rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            transform: rotate(-8deg);
            border-radius: 6px;
            opacity: 0.85;
            background: rgba(220, 252, 231, 0.4);
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .invoice-box {
                border: none;
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<!-- NÚT ĐIỀU KHIỂN (Ẩn khi in) -->
<div class="container text-center mb-4 no-print">
    <div class="d-inline-flex gap-2 bg-white p-2 rounded-3 shadow-sm border">
        <button type="button" class="btn btn-primary btn-sm px-4 fw-semibold" onclick="window.print()">
            <i class="bi bi-printer-fill me-1"></i>In Hóa Đơn (Print)
        </button>
        <a href="<?= BASE_URL ?>?action=lich-su-dat-ban" class="btn btn-outline-secondary btn-sm px-3">
            <i class="bi bi-arrow-left me-1"></i>Quay lại lịch sử
        </a>
        <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary btn-sm px-3">
            <i class="bi bi-house me-1"></i>Trang chủ
        </a>
    </div>
</div>

<div class="invoice-box">
    
    <!-- DẤU MỘC -->
    <?php if (($booking['trang_thai_thanh_toan'] ?? '') === 'da_thanh_toan'): ?>
        <div class="stamp-box">ĐÃ THANH TOÁN ĐỦ</div>
    <?php elseif (($booking['trang_thai_thanh_toan'] ?? '') === 'da_dat_coc'): ?>
        <div class="stamp-box" style="border-color:#0284c7; color:#0284c7; background:rgba(224,242,254,0.4);">ĐÃ ĐẶT CỌC</div>
    <?php endif; ?>

    <!-- HEADER HÓA ĐƠN -->
    <div class="row align-items-center mb-4 pb-4 border-bottom">
        <div class="col-7">
            <h3 class="invoice-title mb-1">QHT BANQUET</h3>
            <p class="mb-0 text-muted small" style="line-height: 1.5;">
                <strong>Nhà Hàng Tiệc Gia Đình QHT Banquet</strong><br>
                Địa chỉ: 123 Đường ABC, Quận 1, TP. Hồ Chí Minh<br>
                Hotline: 0123 456 789 | Email: info@qhtbanquet.vn<br>
                Mã số thuế: 0108928374
            </p>
        </div>
        <div class="col-5 text-end">
            <h4 class="fw-bold text-dark text-uppercase mb-1" style="letter-spacing: 1px;">HÓA ĐƠN TIỆC</h4>
            <div class="text-muted small">Số: <strong class="text-primary font-monospace">HD-DB<?= str_pad($booking['id'], 5, '0', STR_PAD_LEFT) ?></strong></div>
            <div class="text-muted small">Ngày lập: <?= date('d/m/Y') ?></div>
        </div>
    </div>

    <!-- THÔNG TIN KHÁCH HÀNG & TIỆC -->
    <div class="row mb-4">
        <div class="col-6">
            <h6 class="fw-bold text-uppercase text-secondary small mb-2">Khách Hàng / Đơn Vị Đặt Tiệc:</h6>
            <div class="fw-bold text-dark fs-6"><?= htmlspecialchars($booking['ten_khach'] ?? 'Khách hàng') ?></div>
            <div class="small text-secondary">Số điện thoại: <strong><?= htmlspecialchars($booking['sdt_khach'] ?? $booking['so_dien_thoai'] ?? '') ?></strong></div>
            <div class="small text-secondary">Email: <?= htmlspecialchars($booking['email_khach'] ?? $booking['email'] ?? 'Không có') ?></div>
        </div>
        <div class="col-6 text-end">
            <h6 class="fw-bold text-uppercase text-secondary small mb-2">Thông Tin Tiệc:</h6>
            <div class="small text-secondary">Mã đơn đặt bàn: <strong>#DB<?= $booking['id'] ?></strong></div>
            <div class="small text-secondary">Bàn / Phòng: <strong><?= htmlspecialchars($booking['ten_ban'] ?? 'Sắp xếp theo yêu cầu') ?></strong></div>
            <div class="small text-secondary">Thời gian: <?= date('d/m/Y', strtotime($booking['ngay_dat'])) ?> (Ca <?= $booking['ca'] === 'trua' ? 'Trưa' : 'Tối' ?>)</div>
            <div class="small text-secondary">Số lượng khách: <?= $booking['so_nguoi'] ?> khách</div>
        </div>
    </div>

    <!-- BẢNG CHI TIẾT CÁC KHOẢN TIỀN -->
    <div class="table-responsive mb-4">
        <table class="table table-bordered align-middle">
            <thead class="table-light text-center small text-uppercase fw-bold">
                <tr>
                    <th style="width: 50px;">STT</th>
                    <th>Diễn giải dịch vụ / Ẩm thực</th>
                    <th style="width: 100px;">ĐVT</th>
                    <th style="width: 80px;">SL</th>
                    <th style="width: 130px;" class="text-end">Đơn giá (VNĐ)</th>
                    <th style="width: 140px;" class="text-end">Thành tiền (VNĐ)</th>
                </tr>
            </thead>
            <tbody class="small">
                <?php $stt = 1; ?>

                <!-- Set tiệc -->
                <?php if (!empty($booking['ten_set'])): ?>
                    <tr>
                        <td class="text-center"><?= $stt++ ?></td>
                        <td>
                            <strong><?= htmlspecialchars($booking['ten_set']) ?></strong>
                            <div class="text-muted" style="font-size:0.75rem;">Set thực đơn hoàn chỉnh cho <?= $booking['set_so_nguoi'] ?? $booking['so_nguoi'] ?> người</div>
                        </td>
                        <td class="text-center">Set</td>
                        <td class="text-center">1</td>
                        <td class="text-end"><?= number_format($booking['gia_set_luc_dat'] ?? 0, 0, ',', '.') ?></td>
                        <td class="text-end fw-bold"><?= number_format($booking['gia_set_luc_dat'] ?? 0, 0, ',', '.') ?></td>
                    </tr>
                <?php endif; ?>

                <!-- Món ăn gọi lẻ -->
                <?php if (!empty($dishes)): ?>
                    <?php foreach ($dishes as $d): ?>
                        <tr>
                            <td class="text-center"><?= $stt++ ?></td>
                            <td><?= htmlspecialchars($d['ten_mon']) ?></td>
                            <td class="text-center">Đĩa / Phần</td>
                            <td class="text-center"><?= $d['so_luong'] ?></td>
                            <td class="text-end"><?= number_format($d['don_gia'], 0, ',', '.') ?></td>
                            <td class="text-end fw-bold"><?= number_format($d['don_gia'] * $d['so_luong'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Phụ phí phòng VIP -->
                <?php if (!empty($booking['loai_ban']) && $booking['loai_ban'] === 'phong_rieng' && !empty($booking['phu_phi'])): ?>
                    <tr>
                        <td class="text-center"><?= $stt++ ?></td>
                        <td>Phụ phí dịch vụ không gian VIP riêng tư (<?= htmlspecialchars($booking['ten_ban']) ?>)</td>
                        <td class="text-center">Phòng</td>
                        <td class="text-center">1</td>
                        <td class="text-end"><?= number_format($booking['phu_phi'], 0, ',', '.') ?></td>
                        <td class="text-end fw-bold"><?= number_format($booking['phu_phi'], 0, ',', '.') ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-end fw-bold">Tổng chi phí bữa tiệc:</td>
                    <td class="text-end fw-bold text-dark fs-6"><?= number_format($booking['tong_tien'], 0, ',', '.') ?> đ</td>
                </tr>
                <tr>
                    <td colspan="5" class="text-end text-success fw-bold">Tổng số tiền đã thanh toán / cọc:</td>
                    <td class="text-end fw-bold text-success">- <?= number_format($booking['so_tien_da_tra'] ?? 0, 0, ',', '.') ?> đ</td>
                </tr>
                <tr class="table-light">
                    <td colspan="5" class="text-end fw-bold fs-6">SỐ TIỀN CÒN PHẢI THANH TOÁN:</td>
                    <td class="text-end fw-bold text-danger fs-5">
                        <?= number_format(max(0, $booking['tong_tien'] - ($booking['so_tien_da_tra'] ?? 0)), 0, ',', '.') ?> đ
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- LỊCH SỬ CÁC GIAO DỊCH ĐÃ THU -->
    <?php if (!empty($payments)): ?>
        <div class="mb-4">
            <h6 class="fw-bold text-uppercase text-secondary small mb-2">Lịch Sử Các Đợt Thanh Toán:</h6>
            <div class="table-responsive">
                <table class="table table-sm table-bordered small">
                    <thead class="table-light text-center">
                        <tr>
                            <th>Mã giao dịch</th>
                            <th>Ngày thanh toán</th>
                            <th>Hình thức</th>
                            <th class="text-end">Số tiền</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $p): ?>
                            <tr>
                                <td class="font-monospace text-center"><?= htmlspecialchars($p['ma_giao_dich']) ?></td>
                                <td class="text-center"><?= date('d/m/Y H:i', strtotime($p['ngay_thanh_toan'])) ?></td>
                                <td class="text-center text-capitalize"><?= str_replace('_', ' ', $p['phuong_thuc']) ?></td>
                                <td class="text-end fw-bold text-success">+<?= number_format($p['so_tien'], 0, ',', '.') ?> đ</td>
                                <td class="text-center"><?= $p['trang_thai'] === 'thanh_cong' ? 'Đã thu' : 'Chờ xác nhận' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <!-- CHỮ KÝ VÀ LỜI CẢM ƠN -->
    <div class="row pt-4 text-center">
        <div class="col-6">
            <div class="fw-bold mb-1">KHÁCH HÀNG</div>
            <div class="text-muted small fst-italic mb-5">(Ký, ghi rõ họ tên)</div>
            <div class="fw-semibold"><?= htmlspecialchars($booking['ten_khach'] ?? '') ?></div>
        </div>
        <div class="col-6">
            <div class="fw-bold mb-1">ĐẠI DIỆN NHÀ HÀNG QHT BANQUET</div>
            <div class="text-muted small fst-italic mb-5">(Ký, đóng dấu)</div>
            <div class="fw-semibold">Thu Ngân / Kế Toán Trưởng</div>
        </div>
    </div>

    <div class="text-center mt-5 pt-3 border-top small text-muted fst-italic">
        Kính chúc quý khách có những giây phút sum vầy ấm cúng và trọn vẹn tại QHT Banquet!
    </div>

</div>

</body>
</html>
