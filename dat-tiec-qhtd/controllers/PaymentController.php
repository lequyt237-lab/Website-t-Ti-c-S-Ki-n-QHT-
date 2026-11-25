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

        if ($booking['trang_thai'] !== 'da_duyet') {
            $_SESSION['error'] = 'Đơn đặt bàn chưa được quản trị viên duyệt bàn. Vui lòng chờ nhà hàng xác nhận duyệt đơn trước khi thanh toán.';
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        if ((float)$booking['tong_tien'] <= 0) {
            $_SESSION['error'] = 'Đơn đặt bàn chưa có thông tin chi phí / món ăn. Vui lòng đặt món trước khi thanh toán.';
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

        // Số tiền đặt cọc: Ưu tiên mức tiền cọc do Admin yêu cầu ($booking['tien_coc'])
        if (!empty($booking['tien_coc']) && (float)$booking['tien_coc'] > 0) {
            $suggestedDeposit = min((float)$booking['tien_coc'], $conLai);
        } else {
            // Mặc định 30% tổng tiền (tối thiểu 200.000đ)
            $suggestedDeposit = ceil(($tongTien * 0.3) / 10000) * 10000;
            if ($suggestedDeposit < 200000 && $tongTien >= 200000) {
                $suggestedDeposit = 200000;
            } elseif ($suggestedDeposit > $conLai) {
                $suggestedDeposit = $conLai;
            }
        }

        // Cấu hình thanh toán ngân hàng
        $bankConfig = [
            'bank_id'      => 'MB',              // MBBank
            'bank_name'    => 'MBBank (Ngân hàng Quân Đội)',
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
     * Xử lý xác nhận thanh toán từ trang checkout
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

        if ($booking['trang_thai'] !== 'da_duyet' || (float)$booking['tong_tien'] <= 0) {
            $_SESSION['error'] = 'Đơn đặt bàn chưa đủ điều kiện thanh toán (chưa được duyệt hoặc tổng tiền bằng 0).';
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

        $paymentType   = trim($_POST['loai_thanh_toan'] ?? 'dat_coc');
        $paymentMethod = trim($_POST['phuong_thuc'] ?? 'chuyen_khoan');
        // so_tien_input là field ẩn được JS cập nhật cho cả dat_coc / toan_bo / tuy_chon
        $customAmount  = (float)($_POST['so_tien'] ?? 0);

        // Xác định số tiền thanh toán thực tế
        $amount = 0;
        if ($paymentType === 'toan_bo') {
            $amount = $conLai;
        } elseif ($paymentType === 'dat_coc') {
            if (!empty($booking['tien_coc']) && (float)$booking['tien_coc'] > 0) {
                $suggestedDeposit = (float)$booking['tien_coc'];
            } else {
                $suggestedDeposit = ceil(($tongTien * 0.3) / 10000) * 10000;
                if ($suggestedDeposit < 200000 && $tongTien >= 200000) {
                    $suggestedDeposit = 200000;
                }
            }
            $amount = min($suggestedDeposit, $conLai);
        } else {
            // tuy_chon: lấy từ hidden field so_tien_input (được JS set)
            $amount = $customAmount;
        }

        // Fallback: nếu amount vẫn 0 nhưng có so_tien trong POST thì dùng
        if ($amount <= 0 && $customAmount > 0) {
            $amount = $customAmount;
        }

        if ($amount <= 0) {
            $_SESSION['error'] = 'Số tiền thanh toán phải lớn hơn 0. Vui lòng nhập số tiền tùy chọn hoặc chọn hình thức khác.';
            header("Location: " . BASE_URL . "?action=thanh-toan&id=$bookingId");
            exit;
        }

        if ($amount > $conLai && $conLai > 0) {
            $amount = $conLai;
        }

        // Tạo mã giao dịch
        $maGiaoDich = $this->paymentModel->generateTransactionCode($bookingId);

        // Xử lý theo từng phương thức thanh toán:
        // 1. CỔNG VNPAY-QR → Chuyển sang màn hình Cổng thanh toán VNPAY-QR Quét Mã Thật
        if ($paymentMethod === 'vnpay') {
            header("Location: " . BASE_URL . "?action=vnpay-gateway&booking_id=$bookingId&amount=$amount&type=$paymentType&code=$maGiaoDich");
            exit;
        }

        // 2. VÍ MOMO → xử lý ghi nhận ngay (mô phỏng), trạng thái cho_xac_nhan
        if ($paymentMethod === 'momo') {
            $userId = isset($_SESSION['user']) ? (int)$_SESSION['user']['id'] : ($booking['nguoidung_id'] ?? null);
            $res = $this->paymentModel->recordPayment([
                'datban_id'       => $bookingId,
                'nguoidung_id'    => $userId,
                'ma_giao_dich'    => $maGiaoDich,
                'so_tien'         => $amount,
                'loai_thanh_toan' => $paymentType,
                'phuong_thuc'     => 'momo',
                'trang_thai'      => 'cho_xac_nhan',
                'noi_dung'        => trim($_POST['noi_dung'] ?? ("Thanh toan MoMo don #DB$bookingId")),
                'ghi_chu_admin'   => 'Khách xác nhận đã chuyển qua ví MoMo - chờ đối soát',
            ]);
            if ($res) {
                $_SESSION['success'] = 'Yêu cầu thanh toán MoMo đã được ghi nhận. Vui lòng chờ nhà hàng xác nhận!';
                header("Location: " . BASE_URL . "?action=thanh-toan-thanh-cong&code=$maGiaoDich");
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi ghi nhận thanh toán MoMo. Vui lòng thử lại.';
                header("Location: " . BASE_URL . "?action=thanh-toan&id=$bookingId");
            }
            exit;
        }

        // 2. CHUYỂN KHOẢN NGÂN HÀNG
        $anhBienLai = null;
        if (!empty($_FILES['anh_bien_lai']['name'])) {
            try {
                // Tạo thư mục nếu chưa có
                $paymentUploadDir = PATH_ASSETS_UPLOADS . 'payments';
                if (!is_dir($paymentUploadDir)) {
                    mkdir($paymentUploadDir, 0777, true);
                }
                $anhBienLai = upload_file('payments', $_FILES['anh_bien_lai']);
            } catch (Exception $e) {
                // Nếu upload lỗi thì vẫn cho phép tiếp tục
                error_log("Upload bill error: " . $e->getMessage());
            }
        }

        $noiDung = trim($_POST['noi_dung'] ?? ("Thanh toan don tiec #DB" . $bookingId));
        $userId = isset($_SESSION['user']) ? (int)$_SESSION['user']['id'] : ($booking['nguoidung_id'] ?? null);

        // Trạng thái:
        // - Chuyển khoản → chờ admin đối soát bill
        // - Tiền mặt     → chờ admin xác nhận tại quầy
        // - VNPAY/MoMo   → đã xử lý riêng bên trên, không rơi vào đây
        $trangThai = in_array($paymentMethod, ['tien_mat', 'chuyen_khoan']) ? 'cho_xac_nhan' : 'thanh_cong';

        $res = $this->paymentModel->recordPayment([
            'datban_id'       => $bookingId,
            'nguoidung_id'    => $userId,
            'ma_giao_dich'    => $maGiaoDich,
            'so_tien'         => $amount,
            'loai_thanh_toan' => $paymentType,
            'phuong_thuc'     => $paymentMethod,
            'trang_thai'      => $trangThai,
            'noi_dung'        => $noiDung,
            'anh_bien_lai'    => $anhBienLai,
            'ghi_chu_admin'   => $paymentMethod === 'tien_mat' ? 'Khách hẹn thanh toán tiền mặt tại quầy khi đến tiệc' : 'Khách xác nhận đã chuyển khoản thành công',
        ]);

        if ($res) {
            $_SESSION['success'] = 'Giao dịch thanh toán đã được ghi nhận thành công!';
            header("Location: " . BASE_URL . "?action=thanh-toan-thanh-cong&code=$maGiaoDich");
            exit;
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra trong quá trình ghi nhận thanh toán. Vui lòng thử lại hoặc liên hệ hotline.';
            header("Location: " . BASE_URL . "?action=thanh-toan&id=$bookingId");
            exit;
        }
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

        if ($bookingId <= 0 || $amount <= 0) {
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

        if ($booking['trang_thai'] !== 'da_duyet' || (float)$booking['tong_tien'] <= 0) {
            $_SESSION['error'] = 'Đơn đặt bàn chưa đủ điều kiện thanh toán (chưa được duyệt hoặc tổng tiền bằng 0).';
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

        if (empty($code)) {
            $code = $this->paymentModel->generateTransactionCode($bookingId);
        }

        // Cấu hình ngân hàng thanh toán chuẩn
        $bankConfig = [
            'bank_id'      => 'MB',              // MBBank (Ngân hàng Quân Đội)
            'bank_name'    => 'MBBank (Ngân hàng TMCP Quân Đội)',
            'account_no'   => '0988888888',
            'account_name' => 'NHA HANG QHT BANQUET',
            'branch'       => 'Sở Giao Dịch Hà Nội',
        ];

        // Cú pháp nội dung chuyển khoản chuẩn
        $sdt = $booking['sdt_khach'] ?? $booking['so_dien_thoai'] ?? '';
        $transferContent = 'QHT DB' . $booking['id'] . ($sdt ? ' ' . preg_replace('/[^0-9]/', '', $sdt) : '');

        // Đường dẫn mã QR động chuẩn EMVCo Napas247 / VNPAY-QR
        $qrUrl = "https://img.vietqr.io/image/{$bankConfig['bank_id']}-{$bankConfig['account_no']}-compact2.png?amount={$amount}&addInfo=" . urlencode($transferContent) . "&accountName=" . urlencode($bankConfig['account_name']);

        $title = "Cổng Thanh Toán Trực Tuyến VNPAY-QR - QHT Banquet";
        // Render trực tiếp view gateway không qua layout main để giống hệt cổng VNPAY-QR thực tế
        require_once PATH_VIEW . 'payment/vnpay_gateway.php';
    }

    /**
     * Xử lý xác nhận khi khách quét mã VNPAY-QR thành công
     */
    public function vnpayCallback()
    {
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
