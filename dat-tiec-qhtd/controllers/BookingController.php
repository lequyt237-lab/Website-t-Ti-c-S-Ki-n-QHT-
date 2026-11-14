<?php

class BookingController
{
    private $bookingModel;

    private $userModel;



    public function __construct()
    {
        $this->bookingModel = new BookingModel();

        $this->userModel    = new UserModel();
    }

    // Xử lý gửi đơn đặt bàn/phòng từ form trang chủ
    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '#dat-ban');
            exit;
        }

        $hoTen       = trim($_POST['ho_ten'] ?? '');
        $soDienThoai = trim($_POST['so_dien_thoai'] ?? '');
        $email       = trim($_POST['email'] ?? '');
        $banphongId  = (int)($_POST['banphong_id'] ?? 0);
        $loaidipId   = (int)($_POST['loaidip_id'] ?? 0);
        $ngayDat     = trim($_POST['ngay_dat'] ?? '');
        $ca          = trim($_POST['ca'] ?? 'toi');
        $soNguoi     = (int)($_POST['so_nguoi'] ?? 1);
        $ghiChu      = trim($_POST['ghi_chu'] ?? '');

        // 1. Kiểm tra thông tin bắt buộc
        $errors = [];
        if (empty($hoTen)) {
            $errors[] = 'Vui lòng nhập họ và tên người đặt.';
        }
        if (empty($soDienThoai)) {
            $errors[] = 'Vui lòng nhập số điện thoại liên hệ.';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Vui lòng nhập địa chỉ email hợp lệ.';
        }
        if ($banphongId <= 0) {
            $errors[] = 'Vui lòng chọn bàn hoặc phòng tiệc.';
        }
        if ($loaidipId <= 0) {
            $errors[] = 'Vui lòng chọn loại dịp tổ chức tiệc.';
        }

        // 2. CHẶN THỜI GIAN TRONG QUÁ KHỨ
        $today = date('Y-m-d');
        if (empty($ngayDat)) {
            $errors[] = 'Vui lòng chọn ngày tổ chức tiệc.';
        } elseif ($ngayDat < $today) {
            $errors[] = 'Thời gian không hợp lệ! Không thể đặt bàn vào ngày trong quá khứ.';
        }

        // 3. KIỂM TRA SỐ LƯỢNG NGƯỜI
        if ($soNguoi <= 0) {
            $errors[] = 'Số lượng người phải lớn hơn 0.';
        } elseif ($soNguoi > 30) {
            $errors[] = 'Với đoàn tiệc lớn trên 30 khách, quý khách vui lòng liên hệ trực tiếp Quản trị viên qua Hotline: 0123 456 789 để được bố trí sảnh riêng.';
        }

        // 4. Lấy thông tin bàn/phòng và kiểm tra sức chứa
        $table = null;
        if ($banphongId > 0) {
            $table = $this->bookingModel->getTableById($banphongId);
            if (!$table) {
                $errors[] = 'Bàn hoặc phòng được chọn không tồn tại trong hệ thống.';
            } elseif ($soNguoi > $table['suc_chua']) {
                $errors[] = sprintf(
                    'Số lượng khách (%d người) vượt quá sức chứa của %s (tối đa %d người). Quý khách vui lòng chọn phòng lớn hơn.',
                    $soNguoi,
                    htmlspecialchars($table['ten']),
                    $table['suc_chua']
                );
            }
        }

        // 5. Kiểm tra trùng lịch đặt (chống trùng bàn/phòng trong cùng 1 ngày và ca)
        if (empty($errors) && $table) {
            $isBooked = $this->bookingModel->checkTableBooked($banphongId, $ngayDat, $ca);
            if ($isBooked) {
                $caText = $ca === 'trua' ? 'Ca trưa (11:00 - 14:00)' : 'Ca tối (17:00 - 21:30)';
                $errors[] = sprintf(
                    'Rất tiếc! %s đã có khách đặt vào %s ngày %s. Quý khách vui lòng chọn vị trí hoặc ca khác.',
                    htmlspecialchars($table['ten']),
                    $caText,
                    date('d/m/Y', strtotime($ngayDat))
                );
            }
        }

        // Nếu có lỗi -> Lưu session và chuyển về form
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $_SESSION['booking_old'] = [
                'ho_ten'        => $hoTen,
                'so_dien_thoai' => $soDienThoai,
                'email'         => $email,
                'banphong_id'   => $banphongId,
                'loaidip_id'    => $loaidipId,
                'ngay_dat'      => $ngayDat,
                'ca'            => $ca,
                'so_nguoi'      => $soNguoi,
                'ghi_chu'       => $ghiChu,
            ];
            header('Location: ' . BASE_URL . '#dat-ban');
            exit;
        }

        // 6. Xử lý người dùng đặt bàn:
        // Nếu đã đăng nhập -> Lấy id của tài khoản đang đăng nhập
        // Nếu chưa đăng nhập -> Tìm hoặc tạo tài khoản khách tự động
        if (isset($_SESSION['user']['id'])) {
            $nguoidungId = $_SESSION['user']['id'];
        } else {
            $existingUser = $this->userModel->findByEmail($email);
            if ($existingUser) {
                $nguoidungId = $existingUser['id'];
            } else {
                // Tạo tài khoản khách vãng lai
                $defaultPass = password_hash('123456', PASSWORD_DEFAULT);
                $this->userModel->create([
                    'ho_ten'        => $hoTen,
                    'email'         => $email,
                    'mat_khau'      => $defaultPass,
                    'so_dien_thoai' => $soDienThoai,
                    'vai_tro'       => 'khach',
                ]);
                $createdUser = $this->userModel->findByEmail($email);
                $nguoidungId = $createdUser['id'] ?? 1;
            }
        }

        // 7. Tính tổng tiền tạm tính (Phụ phí phòng riêng nếu có)
        $tongTien = (float)($table['phu_phi'] ?? 0);

        // 8. Tạo đơn đặt bàn
        $bookingId = $this->bookingModel->createBooking([
            'nguoidung_id'    => $nguoidungId,
            'banphong_id'     => $banphongId,
            'loaidip_id'      => $loaidipId,
            'set_id'          => null,
            'ngay_dat'        => $ngayDat,
            'ca'              => $ca,
            'so_nguoi'        => $soNguoi,
            'gia_set_luc_dat' => null,
            'tong_tien'       => $tongTien,
            'trang_thai'      => 'cho_duyet',
            'ghi_chu'         => $ghiChu,
        ]);

        if ($bookingId) {
            unset($_SESSION['booking_old']);
            $caText = $ca === 'trua' ? 'Ca trưa' : 'Ca tối';
            $_SESSION['booking_success'] = sprintf(
                '<b>Gửi yêu cầu đặt bàn thành công! (Mã đơn: #%d)</b><br>
                 Vị trí: <b>%s</b> | Ngày: <b>%s</b> (%s) | Khách: <b>%d người</b>.<br>
                 Bộ phận chăm sóc khách hàng của QHT Banquet sẽ liên hệ qua SĐT <b>%s</b> trong vòng 15 phút để xác nhận.',
                $bookingId,
                htmlspecialchars($table['ten']),
                date('d/m/Y', strtotime($ngayDat)),
                $caText,
                $soNguoi,
                htmlspecialchars($soDienThoai)
            );
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi lưu thông tin đặt bàn. Vui lòng thử lại hoặc gọi Hotline hỗ trợ.';
        }

        header('Location: ' . BASE_URL . '#dat-ban');
        exit;
    }
}


    }

    /**
     * Trang xem lịch sử đặt bàn của khách hàng
     */
    public function history()
    {
        $statusFilter = $_GET['status'] ?? '';
        $searchTerm   = trim($_GET['search'] ?? '');
        $isLoggedIn   = isset($_SESSION['user']);

        $bookings = [];
        $stats = [
            'total'     => 0,
            'cho_duyet' => 0,
            'da_duyet'  => 0,
            'tu_choi'   => 0,
            'da_huy'    => 0,
        ];

        if ($isLoggedIn) {
            $userId   = $_SESSION['user']['id'];
            $bookings = $this->bookingModel->getBookingsByUserId($userId, $statusFilter);
            $stats    = $this->bookingModel->getBookingStatsByUserId($userId);
        } elseif (!empty($searchTerm)) {
            // Khách chưa đăng nhập tra cứu theo SĐT hoặc mã đơn
            $bookings = $this->bookingModel->searchByPhoneOrCode($searchTerm);
        }

        $title = 'Lịch sử đặt bàn - QHT Banquet';
        $view  = 'booking_history';
        require_once PATH_VIEW . 'main.php';
    }

    /**
     * Lấy chi tiết đơn đặt bàn (Trả về JSON cho modal hoặc AJAX)
     */
    public function detail()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Mã đơn không hợp lệ']);
            exit;
        }

        $userId = null;
        if (isset($_SESSION['user']) && ($_SESSION['user']['vai_tro'] ?? '') !== 'admin') {
            $userId = $_SESSION['user']['id'];
        }

        $booking = $this->bookingModel->getBookingDetail($id, $userId);

        if (!$booking) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy thông tin đơn đặt bàn này']);
            exit;
        }

        // Lấy danh sách món ăn kèm theo đơn
        $dishes = $this->bookingModel->getOrderDishes($id);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => true,
            'data'    => [
                'booking' => $booking,
                'dishes'  => $dishes,
            ]
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Hủy đơn đặt bàn dành cho khách hàng
     */
    public function cancel()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để thực hiện thao tác này.';
            header('Location: ' . BASE_URL . '?action=dang-nhap');
            exit;
        }

        $bookingId = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
        $userId    = $_SESSION['user']['id'];

        if ($bookingId <= 0) {
            $_SESSION['error'] = 'Đơn đặt bàn không hợp lệ.';
            $this->redirectBack();
        }

        // Kiểm tra xem đơn có đúng là của khách và đang ở trạng thái 'cho_duyet' không
        $booking = $this->bookingModel->getBookingDetail($bookingId, $userId);
        if (!$booking) {
            $_SESSION['error'] = 'Không tìm thấy đơn đặt bàn hoặc bạn không có quyền hủy đơn này.';
            $this->redirectBack();
        }

        if ($booking['trang_thai'] !== 'cho_duyet') {
            $_SESSION['error'] = 'Chỉ có thể hủy đơn đặt bàn khi đang ở trạng thái Chờ duyệt.';
            $this->redirectBack();
        }

        $result = $this->bookingModel->cancelBooking($bookingId, $userId);

        if ($result) {
            $_SESSION['success'] = "Đã hủy đơn đặt bàn #{$bookingId} thành công!";
        } else {
            $_SESSION['error'] = 'Không thể hủy đơn đặt bàn. Vui lòng liên hệ nhà hàng để được hỗ trợ.';
        }

        $this->redirectBack();
    }

    /**
     * Điều hướng quay lại trang trước đó
     */
    private function redirectBack()
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? (BASE_URL . '?action=lich-su-dat-ban');
        header('Location: ' . $ref);
        exit;
    }
}

