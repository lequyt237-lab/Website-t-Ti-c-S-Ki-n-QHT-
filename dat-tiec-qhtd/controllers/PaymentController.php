<?php

class PaymentController
{
    private $paymentModel;
    private $bookingModel;

    public function __construct()
    {
        $this->paymentModel = new PaymentModel();
        $this->bookingModel = new BookingModel();
    }

    /**
     * Màn hình Thanh toán đơn đặt bàn
     */
    public function checkout()
    {
        $bookingId = (int)($_GET['id'] ?? $_SESSION['last_booking_id'] ?? 0);

        if ($bookingId <= 0) {
            $_SESSION['error'] = 'Không tìm thấy thông tin đơn đặt bàn để thanh toán.';
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        $userId = isset($_SESSION['user']) && ($_SESSION['user']['vai_tro'] ?? '') !== 'admin'
            ? $_SESSION['user']['id']
            : null;

        $booking = $this->bookingModel->getBookingDetail($bookingId, $userId);

        if (!$booking) {
            // Kiểm tra trường hợp khách vãng lai tra cứu bằng SĐT gần đây
            $booking = $this->bookingModel->getBookingDetail($bookingId);
        }

        if (!$booking) {
            $_SESSION['error'] = 'Đơn đặt bàn không tồn tại hoặc bạn không có quyền xem.';
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        if (in_array($booking['trang_thai'], ['da_huy', 'tu_choi'])) {
            $_SESSION['error'] = 'Đơn đặt bàn này đã bị hủy hoặc từ chối, không thể thực hiện thanh toán.';
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        // Chặn nếu đơn đang có 1 giao dịch chờ xác nhận duyệt để tránh khách bị spam gửi trùng
        $pendingPayment = $this->paymentModel->getPendingPaymentByBookingId($bookingId);
        if ($pendingPayment) {
            $loaiTxt = $pendingPayment['loai_thanh_toan'] === 'dat_coc' ? 'tiền cọc' : 'thanh toán';
            $_SESSION['error'] = "Đơn đặt bàn này đang có giao dịch {$pendingPayment['ma_giao_dich']} ($loaiTxt) đang chờ nhà hàng xác nhận duyệt.";
            header('Location: ' . BASE_URL . '?action=thanh-toan-thanh-cong&code=' . urlencode($pendingPayment['ma_giao_dich']));
            exit;
        }

        // Danh sách món ăn kèm theo đơn
        $dishes = $this->bookingModel->getOrderDishes($bookingId);

        // Lịch sử các lần thanh toán trước của đơn này
        $payments = $this->paymentModel->getPaymentsByBookingId($bookingId);

        // Tính toán các khoản tiền
        $tongTien = (float)$booking['tong_tien'];
        $daTra    = (float)($booking['so_tien_da_tra'] ?? 0);
        $conLai   = max(0, $tongTien - $daTra);

        // Số tiền đặt cọc:
        // 1. Nếu Admin đã yêu cầu mức cọc cụ thể
        if (!empty($booking['tien_coc']) && (float)$booking['tien_coc'] > 0) {
            $suggestedDeposit = (float)$booking['tien_coc'];
        } elseif ($tongTien > 0) {
            // Mặc định 30% tổng tiền (tối thiểu 200.000đ)
            $suggestedDeposit = ceil(($tongTien * 0.3) / 10000) * 10000;
            if ($suggestedDeposit < 200000) {
                $suggestedDeposit = 200000;
            } elseif ($suggestedDeposit > $conLai && $conLai > 0) {
                $suggestedDeposit = $conLai;
            }
        } else {
            // Đơn chưa chọn món ăn (tổng tiền = 0đ): Đặt cọc giữ bàn tiêu chuẩn 200.000đ
            $suggestedDeposit = 200000;
        }

        // Cấu hình thanh toán ngân hàng
        $bankConfig = [
            'bank_id'      => 'MB',              // MBBank (Ngân hàng Quân Đội)
            'bank_bin'     => '970422',          // Mã BIN MBBank
            'bank_name'    => 'MBBank (Ngân hàng TMCP Quân Đội)',
            'account_no'   => '0988888888',
            'account_name' => 'NHA HANG QHT BANQUET',
            'branch'       => 'Chi nhánh Sở Giao Dịch Hà Nội',
        ];

        // Cú pháp nội dung chuyển khoản chuẩn
        $sdt = $booking['sdt_khach'] ?? $booking['so_dien_thoai'] ?? '';
        $transferContent = 'QHT DB' . $booking['id'] . ($sdt ? ' ' . preg_replace('/[^0-9]/', '', $sdt) : '');

        $title = "Thanh Toán Đơn Tiệc #DB" . str_pad($booking['id'], 4, '0', STR_PAD_LEFT) . " - QHT Banquet";
        $view  = 'payment/checkout';
        require_once PATH_VIEW . 'main.php';
    }

    /**
     * Xử lý xác nhận thanh toán từ trang checkout (Chỉ qua VNPAY-QR)
     */
    public function process()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        $bookingId = (int)($_POST['booking_id'] ?? 0);
        if ($bookingId <= 0) {
            $_SESSION['error'] = 'Đơn đặt bàn không hợp lệ.';
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        $booking = $this->bookingModel->getBookingDetail($bookingId);
        if (!$booking) {
            $_SESSION['error'] = 'Không tìm thấy đơn đặt bàn.';
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        if (in_array($booking['trang_thai'], ['da_huy', 'tu_choi'])) {
            $_SESSION['error'] = 'Đơn đặt bàn này đã bị hủy hoặc từ chối, không thể thanh toán.';
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        // Chặn nếu đơn đang có 1 giao dịch chờ xác nhận duyệt
        $pendingPayment = $this->paymentModel->getPendingPaymentByBookingId($bookingId);
        if ($pendingPayment) {
            $_SESSION['error'] = 'Đơn đặt bàn đang có giao dịch chờ xác nhận duyệt, vui lòng không gửi lại nhiều lần.';
            header('Location: ' . BASE_URL . '?action=thanh-toan-thanh-cong&code=' . urlencode($pendingPayment['ma_giao_dich']));
            exit;
        }

        $tongTien = (float)$booking['tong_tien'];
        $daTra    = (float)($booking['so_tien_da_tra'] ?? 0);
        $conLai   = max(0, $tongTien - $daTra);

        $paymentType  = trim($_POST['loai_thanh_toan'] ?? 'dat_coc');
        $customAmount = (float)($_POST['so_tien'] ?? 0);

        // Xác định số tiền thanh toán thực tế
        $amount = 0;
        if ($paymentType === 'toan_bo') {
            $amount = $conLai > 0 ? $conLai : ($customAmount > 0 ? $customAmount : 200000);
        } elseif ($paymentType === 'dat_coc') {
            if (!empty($booking['tien_coc']) && (float)$booking['tien_coc'] > 0) {
                $amount = (float)$booking['tien_coc'];
            } elseif ($tongTien > 0) {
                $suggestedDeposit = ceil(($tongTien * 0.3) / 10000) * 10000;
                $amount = max(200000, min($suggestedDeposit, $conLai > 0 ? $conLai : $suggestedDeposit));
            } else {
                $amount = 200000; // Mặc định cọc giữ bàn 200.000đ khi chưa chốt món
            }
        } else {
            $amount = $customAmount;
        }

        // Fallback: Nếu amount vẫn <= 0, lấy số tiền từ input hoặc 200.000đ
        if ($amount <= 0 && $customAmount > 0) {
            $amount = $customAmount;
        }
        if ($amount <= 0) {
            $amount = 200000;
        }

        // Tạo mã giao dịch
        $maGiaoDich = $this->paymentModel->generateTransactionCode($bookingId);
        $noiDung    = trim($_POST['noi_dung'] ?? ('Thanh toan don tiec #DB' . $bookingId));

        // Lưu thông tin phiên giao dịch để đối chiếu khi VNPay Sandbox trả về
        $_SESSION['vnpay_pending'][$maGiaoDich] = [
            'booking_id' => $bookingId,
            'amount'     => $amount,
            'type'       => $paymentType,
            'noi_dung'   => $noiDung,
        ];

        // Nếu đã cấu hình VNP_TMN_CODE và VNP_HASH_SECRET -> chuyển thẳng sang cổng VNPay Sandbox chính thức
        if (defined('VNP_TMN_CODE') && VNP_TMN_CODE !== '' && defined('VNP_HASH_SECRET') && VNP_HASH_SECRET !== '') {
            $vnpUrl = $this->buildVnpaySandboxUrl($maGiaoDich, $amount, $noiDung);
            header('Location: ' . $vnpUrl);
            exit;
        }

        // Fallback về màn hình mô phỏng nội bộ nếu chưa cấu hình khóa VNPay Sandbox
        header("Location: " . BASE_URL . "?action=vnpay-gateway&booking_id=$bookingId&amount=$amount&type=$paymentType&code=$maGiaoDich");
        exit;
    }

    /**
     * Tạo URL chuyển hướng sang cổng thanh toán VNPay Sandbox (chuẩn HMAC-SHA512 v2.1.0)
     */
    private function buildVnpaySandboxUrl($txnRef, $amount, $orderInfo)
    {
        $tz = new DateTimeZone('Asia/Ho_Chi_Minh');
        $now = new DateTime('now', $tz);
        $expire = (clone $now)->modify('+15 minutes');

        $ipAddr = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        if ($ipAddr === '::1') {
            $ipAddr = '127.0.0.1';
        }

        $cleanOrderInfo = preg_replace('/[^a-zA-Z0-9 #\-_.:]/', '', $orderInfo);
        if ($cleanOrderInfo === '') {
            $cleanOrderInfo = 'Thanh toan don tiec ' . $txnRef;
        }

        $inputData = [
            'vnp_Version'    => '2.1.0',
            'vnp_TmnCode'    => VNP_TMN_CODE,
            'vnp_Amount'     => (int)round($amount) * 100,
            'vnp_Command'    => 'pay',
            'vnp_CreateDate' => $now->format('YmdHis'),
            'vnp_CurrCode'   => 'VND',
            'vnp_IpAddr'     => $ipAddr,
            'vnp_Locale'     => 'vn',
            'vnp_OrderInfo'  => $cleanOrderInfo,
            'vnp_OrderType'  => 'other',
            'vnp_ReturnUrl'  => VNP_RETURN_URL,
            'vnp_TxnRef'     => $txnRef,
            'vnp_ExpireDate' => $expire->format('YmdHis'),
        ];

        ksort($inputData);
        $query = '';
        $hashdata = '';
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i === 1) {
                $hashdata .= '&' . urlencode($key) . '=' . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . '=' . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . '=' . urlencode($value) . '&';
        }

        $vnpSecureHash = hash_hmac('sha512', $hashdata, VNP_HASH_SECRET);
        return VNP_URL . '?' . $query . 'vnp_SecureHash=' . $vnpSecureHash;
    }

    /**
     * Màn hình Cổng thanh toán VNPAY-QR Quét Mã Thật chuyên nghiệp
     */
    public function vnpayGateway()
    {
        $bookingId = (int)($_GET['booking_id'] ?? 0);
        $amount    = (float)($_GET['amount'] ?? 0);
        $type      = trim($_GET['type'] ?? 'dat_coc');
        $code      = trim($_GET['code'] ?? '');

        if ($bookingId <= 0) {
            $_SESSION['error'] = 'Thông tin giao dịch không hợp lệ.';
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        $booking = $this->bookingModel->getBookingDetail($bookingId);
        if (!$booking) {
            $_SESSION['error'] = 'Không tìm thấy đơn đặt bàn.';
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        if (in_array($booking['trang_thai'], ['da_huy', 'tu_choi'])) {
            $_SESSION['error'] = 'Đơn đặt bàn này đã bị hủy hoặc từ chối, không thể thanh toán.';
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        // Chặn nếu đơn đang có 1 giao dịch chờ xác nhận duyệt
        $pendingPayment = $this->paymentModel->getPendingPaymentByBookingId($bookingId);
        if ($pendingPayment) {
            $_SESSION['error'] = 'Đơn đặt bàn đang có giao dịch chờ xác nhận duyệt, vui lòng không gửi lại nhiều lần.';
            header('Location: ' . BASE_URL . '?action=thanh-toan-thanh-cong&code=' . urlencode($pendingPayment['ma_giao_dich']));
            exit;
        }

        if ($amount <= 0) {
            $amount = 200000;
        }

        if (empty($code)) {
            $code = $this->paymentModel->generateTransactionCode($bookingId);
        }

        // Cấu hình ngân hàng thanh toán chuẩn
        $bankConfig = [
            'bank_id'      => 'MB',              // MBBank (Ngân hàng Quân Đội)
            'bank_bin'     => '970422',          // Mã BIN MBBank
            'bank_name'    => 'MBBank (Ngân hàng TMCP Quân Đội)',
            'account_no'   => '0988888888',
            'account_name' => 'NHA HANG QHT BANQUET',
            'branch'       => 'Sở Giao Dịch Hà Nội',
        ];

        // Cú pháp nội dung chuyển khoản chuẩn
        $sdt = $booking['sdt_khach'] ?? $booking['so_dien_thoai'] ?? '';
        $transferContent = 'QHT DB' . $booking['id'] . ($sdt ? ' ' . preg_replace('/[^0-9]/', '', $sdt) : '');

        // Chuỗi payload EMVCo Napas247 VietQR chuẩn quốc tế
        $emvcoPayload = $this->buildVietQrPayload(
            $bankConfig['bank_bin'],
            $bankConfig['account_no'],
            $amount,
            $transferContent,
            $bankConfig['account_name']
        );

        // Đường dẫn ảnh VietQR chính thức
        $qrUrl = "https://img.vietqr.io/image/{$bankConfig['bank_bin']}-{$bankConfig['account_no']}-compact2.png?amount={$amount}&addInfo=" . urlencode($transferContent) . "&accountName=" . urlencode($bankConfig['account_name']);

        // Fallback qua dịch vụ QR Server phụ phòng trường hợp máy client bị chặn domain img.vietqr.io
        $qrFallbackUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($emvcoPayload);

        $title = "Cổng Thanh Toán Trực Tuyến VNPAY-QR - QHT Banquet";
        // Render trực tiếp view gateway không qua layout main để giống hệt cổng VNPAY-QR thực tế
        require_once PATH_VIEW . 'payment/vnpay_gateway.php';
    }

    /**
     * Hàm sinh chuỗi EMVCo Napas247 / VNPAY-QR chuẩn quốc tế
     */
    private function buildVietQrPayload($bankBin, $accountNo, $amount, $content, $accountName = 'NHA HANG QHT BANQUET')
    {
        $sub00 = '00' . str_pad(strlen($bankBin), 2, '0', STR_PAD_LEFT) . $bankBin;
        $sub01 = '01' . str_pad(strlen($accountNo), 2, '0', STR_PAD_LEFT) . $accountNo;
        $beneficiary = $sub00 . $sub01;

        $tag38_00 = '0010A000000727';
        $tag38_01 = '01' . str_pad(strlen($beneficiary), 2, '0', STR_PAD_LEFT) . $beneficiary;
        $tag38_02 = '0208QRIBFTC';
        $tag38_val = $tag38_00 . $tag38_01 . $tag38_02;
        $tag38 = '38' . str_pad(strlen($tag38_val), 2, '0', STR_PAD_LEFT) . $tag38_val;

        $raw = '000201';
        $raw .= ((int)$amount > 0) ? '010211' : '010212';
        $raw .= $tag38;
        $raw .= '52040000';
        $raw .= '5303704';

        if ((int)$amount > 0) {
            $amtStr = (string)((int)$amount);
            $raw .= '54' . str_pad(strlen($amtStr), 2, '0', STR_PAD_LEFT) . $amtStr;
        }

        $raw .= '5802VN';

        if (!empty($accountName)) {
            $nameStr = strtoupper(substr($accountName, 0, 25));
            $raw .= '59' . str_pad(strlen($nameStr), 2, '0', STR_PAD_LEFT) . $nameStr;
        }

        $raw .= '6006HANOI';

        if (!empty($content)) {
            $cleanContent = preg_replace('/[^a-zA-Z0-9 ]/', '', $content);
            $cleanContent = substr($cleanContent, 0, 25);
            $sub62_08 = '08' . str_pad(strlen($cleanContent), 2, '0', STR_PAD_LEFT) . $cleanContent;
            $raw .= '62' . str_pad(strlen($sub62_08), 2, '0', STR_PAD_LEFT) . $sub62_08;
        }

        $raw .= '6304';
        // Tính CRC16-CCITT
        $crc = 0xFFFF;
        for ($c = 0; $c < strlen($raw); $c++) {
            $crc ^= (ord($raw[$c]) << 8);
            for ($i = 0; $i < 8; $i++) {
                if ($crc & 0x8000) {
                    $crc = (($crc << 1) ^ 0x1021) & 0xFFFF;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }
        $crcHex = strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
        return $raw . $crcHex;
    }

    /**
     * Xử lý xác nhận khi khách thanh toán xong (hỗ trợ cả VNPay Sandbox return & fallback nội bộ)
     */
    public function vnpayCallback()
    {
        // 1. Trường hợp trả về từ cổng VNPay Sandbox chính thức
        if (isset($_GET['vnp_SecureHash'])) {
            $vnpSecureHash = $_GET['vnp_SecureHash'];
            $inputData = [];
            foreach ($_GET as $key => $value) {
                if (substr($key, 0, 4) === 'vnp_') {
                    $inputData[$key] = $value;
                }
            }
            unset($inputData['vnp_SecureHash'], $inputData['vnp_SecureHashType']);
            ksort($inputData);

            $hashData = '';
            $i = 0;
            foreach ($inputData as $key => $value) {
                if ($i === 1) {
                    $hashData .= '&' . urlencode($key) . '=' . urlencode($value);
                } else {
                    $hashData .= urlencode($key) . '=' . urlencode($value);
                    $i = 1;
                }
            }

            $expectedHash = hash_hmac('sha512', $hashData, VNP_HASH_SECRET);
            $code         = trim($_GET['vnp_TxnRef'] ?? '');
            $amount       = ((float)($_GET['vnp_Amount'] ?? 0)) / 100;
            $responseCode = $_GET['vnp_ResponseCode'] ?? '';
            $pending      = $_SESSION['vnpay_pending'][$code] ?? null;

            $bookingId = (int)($pending['booking_id'] ?? 0);
            if ($bookingId <= 0 && preg_match('/-DB(\d+)-/', $code, $m)) {
                $bookingId = (int)$m[1];
            }
            $type    = $pending['type'] ?? 'dat_coc';
            $noiDung = $pending['noi_dung'] ?? ($_GET['vnp_OrderInfo'] ?? ('Thanh toan VNPay Sandbox cho don #DB' . $bookingId));

            if (!hash_equals($expectedHash, $vnpSecureHash)) {
                $_SESSION['error'] = 'Chữ ký phản hồi từ VNPay Sandbox không hợp lệ.';
                header('Location: ' . BASE_URL . ($bookingId > 0 ? "?action=thanh-toan&id=$bookingId" : '?action=lich-su-dat-ban'));
                exit;
            }

            if ($responseCode !== '00') {
                $_SESSION['error'] = "Giao dịch VNPay Sandbox chưa hoàn tất hoặc đã bị hủy (Mã lỗi: $responseCode).";
                header('Location: ' . BASE_URL . ($bookingId > 0 ? "?action=thanh-toan&id=$bookingId" : '?action=lich-su-dat-ban'));
                exit;
            }

            if ($bookingId <= 0 || $amount <= 0 || empty($code)) {
                $_SESSION['error'] = 'Dữ liệu đơn đặt bàn từ VNPay Sandbox không hợp lệ.';
                header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
                exit;
            }

            // Tránh ghi trùng nếu người dùng F5 trang callback
            if ($this->paymentModel->getPaymentByCode($code)) {
                unset($_SESSION['vnpay_pending'][$code]);
                header("Location: " . BASE_URL . "?action=thanh-toan-thanh-cong&code=" . urlencode($code));
                exit;
            }

            $booking = $this->bookingModel->getBookingDetail($bookingId);
            if (!$booking) {
                $_SESSION['error'] = 'Không tìm thấy đơn đặt bàn tương ứng.';
                header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
                exit;
            }

            $userId = isset($_SESSION['user']) ? (int)$_SESSION['user']['id'] : ($booking['nguoidung_id'] ?? null);
            $vnpTransNo = trim($_GET['vnp_TransactionNo'] ?? '');

            $res = $this->paymentModel->recordPayment([
                'datban_id'       => $bookingId,
                'nguoidung_id'    => $userId,
                'ma_giao_dich'    => $code,
                'so_tien'         => $amount,
                'loai_thanh_toan' => $type,
                'phuong_thuc'     => 'vnpay',
                'trang_thai'      => 'thanh_cong',
                'noi_dung'        => $noiDung,
                'anh_bien_lai'    => null,
                'ghi_chu_admin'   => 'Thanh toán thành công qua VNPay Sandbox' . ($vnpTransNo ? " (Mã GD VNPAY: $vnpTransNo)" : ''),
            ]);

            unset($_SESSION['vnpay_pending'][$code]);

            if ($res) {
                $_SESSION['success'] = "Thanh toán qua VNPay Sandbox thành công! Nhà hàng đã ghi nhận đơn tiệc của bạn.";
                header("Location: " . BASE_URL . "?action=thanh-toan-thanh-cong&code=" . urlencode($code));
                exit;
            }

            $_SESSION['error'] = "Không thể lưu giao dịch VNPay Sandbox.";
            header("Location: " . BASE_URL . "?action=thanh-toan&id=$bookingId");
            exit;
        }

        // 2. Trường hợp fallback xác nhận từ màn hình nội bộ vnpay_gateway.php
        $data         = !empty($_POST) ? $_POST : $_GET;
        $bookingId    = (int)($data['booking_id'] ?? 0);
        $amount       = (float)($data['amount'] ?? 0);
        $code         = trim($data['code'] ?? '');
        $type         = trim($data['type'] ?? 'dat_coc');
        $noiDung      = trim($data['noi_dung'] ?? ("Thanh toan VNPAY-QR cho don #DB" . $bookingId));

        if ($bookingId <= 0 || $amount <= 0 || empty($code)) {
            $_SESSION['error'] = 'Dữ liệu phản hồi từ cổng thanh toán không hợp lệ.';
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        $booking = $this->bookingModel->getBookingDetail($bookingId);
        if (!$booking) {
            $_SESSION['error'] = 'Không tìm thấy đơn đặt bàn tương ứng.';
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        // Nhận ảnh biên lai nếu khách tải lên kèm
        $anhBienLai = null;
        if (!empty($_FILES['anh_bien_lai']['name'])) {
            try {
                $paymentUploadDir = PATH_ASSETS_UPLOADS . 'payments';
                if (!is_dir($paymentUploadDir)) {
                    mkdir($paymentUploadDir, 0777, true);
                }
                $anhBienLai = upload_file('payments', $_FILES['anh_bien_lai']);
            } catch (Exception $e) {
                error_log("Upload bill error on vnpayCallback: " . $e->getMessage());
            }
        }

        $userId = isset($_SESSION['user']) ? (int)$_SESSION['user']['id'] : ($booking['nguoidung_id'] ?? null);

        $res = $this->paymentModel->recordPayment([
            'datban_id'       => $bookingId,
            'nguoidung_id'    => $userId,
            'ma_giao_dich'    => $code,
            'so_tien'         => $amount,
            'loai_thanh_toan' => $type,
            'phuong_thuc'     => 'vnpay',
            'trang_thai'      => 'thanh_cong',
            'noi_dung'        => $noiDung,
            'anh_bien_lai'    => $anhBienLai,
            'ghi_chu_admin'   => 'Khách quét mã VNPAY-QR Napas247 thành công từ ứng dụng Mobile Banking',
        ]);

        if ($res) {
            $_SESSION['success'] = "Thanh toán qua cổng VNPAY-QR thành công! Nhà hàng đã ghi nhận đơn tiệc của bạn.";
            header("Location: " . BASE_URL . "?action=thanh-toan-thanh-cong&code=$code");
            exit;
        }

        $_SESSION['error'] = "Giao dịch thanh toán VNPAY không thành công hoặc đã bị hủy bỏ.";
        header("Location: " . BASE_URL . "?action=thanh-toan&id=$bookingId");
        exit;
    }

    /**
     * Màn hình thông báo thanh toán thành công
     */
    public function success()
    {
        $code = trim($_GET['code'] ?? '');
        if (empty($code)) {
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        $payment = $this->paymentModel->getPaymentByCode($code);
        if (!$payment) {
            $_SESSION['error'] = 'Không tìm thấy thông tin giao dịch.';
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        $title = "Xác Nhận Thanh Toán Thành Công - QHT Banquet";
        $view  = 'payment/success';
        require_once PATH_VIEW . 'main.php';
    }

    /**
     * Màn hình xem và in Hóa Đơn điện tử
     */
    public function invoice()
    {
        $paymentId = (int)($_GET['payment_id'] ?? 0);
        $bookingId = (int)($_GET['booking_id'] ?? 0);

        $payment = null;
        if ($paymentId > 0) {
            $payment = $this->paymentModel->getPaymentById($paymentId);
            if ($payment) {
                $bookingId = $payment['datban_id'];
            }
        }

        if ($bookingId <= 0) {
            $_SESSION['error'] = 'Không tìm thấy đơn để xuất hóa đơn.';
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        $booking = $this->bookingModel->getBookingDetail($bookingId);
        if (!$booking) {
            $_SESSION['error'] = 'Đơn đặt bàn không tồn tại.';
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        $dishes = $this->bookingModel->getOrderDishes($bookingId);
        $payments = $this->paymentModel->getPaymentsByBookingId($bookingId);

        $title = "Hóa Đơn Thanh Toán #DB" . str_pad($booking['id'], 4, '0', STR_PAD_LEFT) . " - QHT Banquet";
        require_once PATH_VIEW . 'payment/invoice.php';
    }
}
