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

        $hoTen              = trim($_POST['ho_ten'] ?? '');
        $soDienThoai        = trim($_POST['so_dien_thoai'] ?? '');
        $email              = trim($_POST['email'] ?? '');
        $loaidipId          = !empty($_POST['loaidip_id']) ? (int)$_POST['loaidip_id'] : null;
        $yeuCauPhongRieng   = !empty($_POST['yeu_cau_phong_rieng']) ? 1 : 0;
        $ngayDat            = trim($_POST['ngay_dat'] ?? '');
        $gioDen             = trim($_POST['gio_den'] ?? '');
        if (empty($gioDen)) {
            $gioDen = '18:30';
        }
        // Tự động phân loại ca dựa trên giờ đến (trước 15h: ca trưa, từ 15h: ca tối)
        $hour               = (int)explode(':', $gioDen)[0];
        $ca                 = ($hour < 15) ? 'trua' : 'toi';
        $soNguoi            = (int)($_POST['so_nguoi'] ?? 1);
        $ghiChu             = trim($_POST['ghi_chu'] ?? '');

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

        // Nếu có lỗi -> Lưu session và chuyển về form
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $_SESSION['booking_old'] = [
                'ho_ten'              => $hoTen,
                'so_dien_thoai'       => $soDienThoai,
                'email'               => $email,
                'loaidip_id'          => $loaidipId,
                'yeu_cau_phong_rieng' => $yeuCauPhongRieng,
                'ngay_dat'            => $ngayDat,
                'gio_den'             => $gioDen,
                'ca'                  => $ca,
                'so_nguoi'            => $soNguoi,
                'ghi_chu'             => $ghiChu,
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

        // 7. Tạo đơn đặt bàn (Bàn phòng do Quản trị viên sắp xếp sau khi kiểm tra)
        $bookingId = $this->bookingModel->createBooking([
            'nguoidung_id'        => $nguoidungId,
            'banphong_id'         => null,
            'loaidip_id'          => $loaidipId,
            'set_id'              => null,
            'ngay_dat'            => $ngayDat,
            'gio_den'             => $gioDen,
            'ca'                  => $ca,
            'so_nguoi'            => $soNguoi,
            'yeu_cau_phong_rieng' => $yeuCauPhongRieng,
            'gia_set_luc_dat'     => null,
            'tong_tien'           => 0,
            'trang_thai'          => 'cho_duyet',
            'ghi_chu'             => $ghiChu,
        ]);

        if ($bookingId) {
            unset($_SESSION['booking_old']);
feat-dat-mon
            $_SESSION['last_booking_id'] = $bookingId;
            $caText = $ca === 'trua' ? 'Ca trưa (11:00 - 14:00)' : 'Ca tối (17:00 - 21:30)';
            $roomPrefText = $yeuCauPhongRieng ? '<span class="badge bg-warning text-dark">Có yêu cầu phòng riêng VIP</span>' : 'Bàn sảnh chung';
            $_SESSION['booking_success'] = sprintf(
                '<b>Gửi yêu cầu đặt bàn thành công! (Mã đơn: #%d)</b><br>
                 Ngày: <b>%s</b> (%s) | Số khách: <b>%d người</b> | Nhu cầu: %s.<br>
                 Yêu cầu của bạn đã được tiếp nhận. Quản trị viên sẽ tiến hành xếp bàn phù hợp và liên hệ qua SĐT <b>%s</b> trong vòng 15 phút để xác nhận.<br>
                 <div class="mt-3 d-flex flex-wrap gap-2">
                     <a href="%s?action=dat-mon&booking_id=%d" class="btn btn-warning text-dark fw-bold btn-sm shadow-sm">
                         <i class="bi bi-cart-plus-fill me-1"></i> Đặt món ngay cho bàn này
                     </a>
                     <a href="%s?action=lich-su-dat-ban" class="btn btn-outline-success btn-sm">
                         <i class="bi bi-receipt me-1"></i> Xem lịch sử đặt bàn
                     </a>
                 </div>',

            $caText = $ca === 'trua' ? 'Ca trưa' : 'Ca tối';
            $roomPrefText = $yeuCauPhongRieng ? '<span class="badge bg-warning text-dark">Có yêu cầu phòng riêng VIP</span>' : 'Bàn sảnh chung';
            $_SESSION['booking_success'] = sprintf(
                '<b>Gửi yêu cầu đặt bàn thành công! (Mã đơn: #%d)</b><br>
                 Ngày đến: <b>%s</b> lúc <b>%s (%s)</b> | Số khách: <b>%d người</b> | Nhu cầu: %s.<br>
                 Yêu cầu của bạn đã được tiếp nhận. Quản trị viên sẽ tiến hành xếp bàn phù hợp và liên hệ qua SĐT <b>%s</b> trong vòng 15 phút để xác nhận.',
 main
                $bookingId,
                date('d/m/Y', strtotime($ngayDat)),
                date('H:i', strtotime($gioDen)),
                $caText,
                $soNguoi,
                $roomPrefText,
                htmlspecialchars($soDienThoai),
                BASE_URL,
                $bookingId,
                BASE_URL
            );
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi lưu thông tin đặt bàn. Vui lòng thử lại hoặc gọi Hotline hỗ trợ.';
        }

        header('Location: ' . BASE_URL . '#dat-ban');
        exit;
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
     * Giao diện chọn món & đặt món cho đơn đặt bàn
     */
    public function orderFood()
    {
        $bookingId  = (int)($_GET['booking_id'] ?? 0);
        $isLoggedIn = isset($_SESSION['user']);
        $userId     = $isLoggedIn ? $_SESSION['user']['id'] : null;

        if ($bookingId <= 0) {
            if ($isLoggedIn) {
                $userBookings = $this->bookingModel->getBookingsByUserId($userId);
                $activeBookings = array_filter($userBookings, fn($b) => !in_array($b['trang_thai'], ['da_huy', 'tu_choi']));
                if (!empty($activeBookings)) {
                    $first = reset($activeBookings);
                    header('Location: ' . BASE_URL . '?action=dat-mon&booking_id=' . $first['id']);
                    exit;
                }
            }
            $_SESSION['error'] = 'Vui lòng chọn đơn đặt bàn trước khi đặt món.';
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        // Lấy thông tin đơn đặt bàn
        $booking = $this->bookingModel->getBookingDetail($bookingId);
        if (!$booking) {
            $_SESSION['error'] = 'Không tìm thấy đơn đặt bàn #' . $bookingId;
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        // Kiểm tra quyền truy cập: nếu đã đăng nhập và không phải admin, đơn phải thuộc user
        if ($isLoggedIn && ($_SESSION['user']['vai_tro'] ?? '') !== 'admin') {
            if ((int)$booking['nguoidung_id'] !== (int)$userId) {
                $_SESSION['error'] = 'Bạn không có quyền quản lý đơn đặt bàn này.';
                header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
                exit;
            }
        }

        // Không cho phép đặt món nếu đơn đã bị hủy hoặc từ chối
        if (in_array($booking['trang_thai'], ['da_huy', 'tu_choi'])) {
            $_SESSION['error'] = 'Đơn đặt bàn #' . $bookingId . ' đã ở trạng thái "' . ($booking['trang_thai'] === 'da_huy' ? 'Đã hủy' : 'Từ chối') . '", không thể chỉnh sửa món ăn.';
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        // Lấy danh sách món ăn đã chọn trước đó (nếu có)
        $currentDishes = $this->bookingModel->getOrderDishes($bookingId);
        $currentDishesMap = [];
        foreach ($currentDishes as $cd) {
            $currentDishesMap[$cd['mon_id']] = [
                'so_luong' => (int)$cd['so_luong'],
                'don_gia'  => (float)$cd['don_gia'],
                'ten_mon'  => $cd['ten_mon'],
            ];
        }

        // Lấy danh sách tất cả món ăn & nhóm món
        $menuModel = new MenuModel();
        $dishes = $menuModel->getAllDishes();
        $categories = $menuModel->getCategories();

        // Lấy danh sách set tiệc
        $setTiecModel = new SetTiecModel();
        $sets = $setTiecModel->getAll();

        // Lấy danh sách món trong từng set để hiển thị
        $setDishes = [];
        foreach ($sets as $st) {
            $setDishes[$st['id']] = $setTiecModel->getMonBySetId($st['id']);
        }

        $title = 'Đặt món cho đơn #DB' . str_pad($bookingId, 4, '0', STR_PAD_LEFT) . ' - QHT Banquet';
        $view  = 'order_food';
        require_once PATH_VIEW . 'main.php';
    }

    /**
     * Xử lý lưu các món ăn và set tiệc khách đã chọn
     */
    public function saveFoodOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        $bookingId  = (int)($_POST['booking_id'] ?? 0);
        $isLoggedIn = isset($_SESSION['user']);
        $userId     = $isLoggedIn ? $_SESSION['user']['id'] : null;

        if ($bookingId <= 0) {
            $_SESSION['error'] = 'Mã đơn đặt bàn không hợp lệ.';
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        $booking = $this->bookingModel->getBookingDetail($bookingId);
        if (!$booking) {
            $_SESSION['error'] = 'Không tìm thấy thông tin đơn đặt bàn.';
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        if ($isLoggedIn && ($_SESSION['user']['vai_tro'] ?? '') !== 'admin') {
            if ((int)$booking['nguoidung_id'] !== (int)$userId) {
                $_SESSION['error'] = 'Bạn không có quyền thao tác trên đơn đặt bàn này.';
                header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
                exit;
            }
        }

        if (in_array($booking['trang_thai'], ['da_huy', 'tu_choi'])) {
            $_SESSION['error'] = 'Đơn đặt bàn đã hủy hoặc từ chối, không thể lưu món.';
            header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
            exit;
        }

        $setId = !empty($_POST['set_id']) ? (int)$_POST['set_id'] : null;
        $rawDishes = $_POST['dishes'] ?? [];
        $dishes = [];
        if (is_array($rawDishes)) {
            foreach ($rawDishes as $monId => $qty) {
                $qty = (int)$qty;
                if ($qty > 0) {
                    $dishes[(int)$monId] = $qty;
                }
            }
        }

        $ghiChu = trim($_POST['ghi_chu'] ?? '');

        $result = $this->bookingModel->saveBookingDishes($bookingId, $dishes, $setId, $ghiChu);

        if ($result) {
            $countDishes = count($dishes);
            $setInfo = $setId ? ' và 1 Set tiệc' : '';
            $_SESSION['success'] = "<b>Đặt món thành công!</b> Đơn đặt bàn <b>#DB" . str_pad($bookingId, 4, '0', STR_PAD_LEFT) . "</b> đã được cập nhật thực đơn gồm {$countDishes} món lẻ{$setInfo}. Quý khách có thể xem lại chi tiết ngay bên dưới.";
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi lưu món ăn. Vui lòng kiểm tra lại.';
        }

        header('Location: ' . BASE_URL . '?action=lich-su-dat-ban');
        exit;
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

