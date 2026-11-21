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

        // Danh sách món ăn kèm theo đơn
        $dishes = $this->bookingModel->getOrderDishes($bookingId);

        // Lịch sử các lần thanh toán trước của đơn này
        $payments = $this->paymentModel->getPaymentsByBookingId($bookingId);

        // Tính toán các khoản tiền
        $tongTien = (float)$booking['tong_tien'];
        $daTra    = (float)($booking['so_tien_da_tra'] ?? 0);
        $conLai   = max(0, $tongTien - $daTra);

        // Gợi ý số tiền đặt cọc (30% tổng tiền, làm tròn đến chục nghìn)
        $suggestedDeposit = ceil(($tongTien * 0.3) / 10000) * 10000;
        if ($suggestedDeposit < 200000 && $tongTien >= 200000) {
            $suggestedDeposit = 200000;
        } elseif ($suggestedDeposit > $conLai) {
            $suggestedDeposit = $conLai;
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
        $transferContent = 'QHT DB' . $booking['id'] . ' ' . preg_replace('/[^0-9]/', '', $booking['so_dien_thoai']);

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

        $tongTien = (float)$booking['tong_tien'];
        $daTra    = (float)($booking['so_tien_da_tra'] ?? 0);
        $conLai   = max(0, $tongTien - $daTra);

        $paymentType   = trim($_POST['loai_thanh_toan'] ?? 'dat_coc');
        $paymentMethod = trim($_POST['phuong_thuc'] ?? 'chuyen_khoan');
        $customAmount  = (float)($_POST['so_tien'] ?? 0);

        // Xác định số tiền thanh toán thực tế
        $amount = 0;
        if ($paymentType === 'toan_bo') {
            $amount = $conLai;
        } elseif ($paymentType === 'dat_coc') {
            $suggestedDeposit = ceil(($tongTien * 0.3) / 10000) * 10000;
            if ($suggestedDeposit < 200000 && $tongTien >= 200000) {
                $suggestedDeposit = 200000;
            }
            $amount = min($suggestedDeposit, $conLai);
        } else {
            // Tùy chọn số tiền
            $amount = $customAmount;
        }

        if ($amount <= 0) {
            $_SESSION['error'] = 'Số tiền thanh toán phải lớn hơn 0.';
            header("Location: " . BASE_URL . "?action=thanh-toan&id=$bookingId");
            exit;
        }

        if ($amount > $conLai && $conLai > 0) {
            $amount = $conLai;
        }

        // Tạo mã giao dịch
        $maGiaoDich = $this->paymentModel->generateTransactionCode($bookingId);

        // Xử lý theo từng phương thức thanh toán:
        // 1. CỔNG VNPAY (Chuyển sang màn hình Cổng thanh toán VNPAY sandbox)
        if ($paymentMethod === 'vnpay') {
            header("Location: " . BASE_URL . "?action=vnpay-gateway&booking_id=$bookingId&amount=$amount&type=$paymentType&code=$maGiaoDich");
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

        // Trạng thái: Chuyển khoản hoặc Tiền mặt
        $trangThai = ($paymentMethod === 'tien_mat') ? 'cho_xac_nhan' : 'thanh_cong';

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
     * Màn hình mô phỏng Cổng thanh toán VNPAY Sandbox chuyên nghiệp
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

        if (empty($code)) {
            $code = $this->paymentModel->generateTransactionCode($bookingId);
        }

        $title = "Cổng Thanh Toán Trực Tuyến VNPAY - QHT Banquet";
        // Render trực tiếp view gateway không qua layout main để giống hệt cổng VNPAY thực tế
        require_once PATH_VIEW . 'payment/vnpay_gateway.php';
    }

    /**
     * Xử lý Callback khi thanh toán qua VNPAY thành công
     */
    public function vnpayCallback()
    {
        $bookingId  = (int)($_GET['booking_id'] ?? 0);
        $amount     = (float)($_GET['amount'] ?? 0);
        $code       = trim($_GET['code'] ?? '');
        $type       = trim($_GET['type'] ?? 'dat_coc');
        $bankCode   = trim($_GET['bank_code'] ?? 'NCB');
        $responseCode = trim($_GET['response_code'] ?? '00'); // '00' là thành công

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

        if ($responseCode === '00') {
            $userId = isset($_SESSION['user']) ? (int)$_SESSION['user']['id'] : ($booking['nguoidung_id'] ?? null);

            $res = $this->paymentModel->recordPayment([
                'datban_id'       => $bookingId,
                'nguoidung_id'    => $userId,
                'ma_giao_dich'    => $code,
                'so_tien'         => $amount,
                'loai_thanh_toan' => $type,
                'phuong_thuc'     => 'vnpay',
                'trang_thai'      => 'thanh_cong',
                'noi_dung'        => "Thanh toan VNPAY ($bankCode) cho don #DB$bookingId",
                'ghi_chu_admin'   => "Giao dịch VNPAY Sandbox qua ngân hàng $bankCode thành công (Mã phản hồi: 00)",
            ]);

            if ($res) {
                $_SESSION['success'] = "Thanh toán qua cổng VNPAY thành công!";
                header("Location: " . BASE_URL . "?action=thanh-toan-thanh-cong&code=$code");
                exit;
            }
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
