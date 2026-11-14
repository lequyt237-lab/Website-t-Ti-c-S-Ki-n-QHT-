<?php

class BookingController
{
    private $bookingModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
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
