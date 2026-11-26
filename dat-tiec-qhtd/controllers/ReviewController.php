<?php

class ReviewController
{
    private $reviewModel;
    private $bookingModel;

    public function __construct()
    {
        $this->reviewModel  = new ReviewModel();
        $this->bookingModel = new BookingModel();
    }

    /**
     * Trang xem danh sách đánh giá & bình luận công khai của khách hàng
     */
    public function index()
    {
        $starFilter = !empty($_GET['star']) ? (int)$_GET['star'] : null;
        $reviews    = $this->reviewModel->getPublicReviews(30, $starFilter);
        $stats      = $this->reviewModel->getStats();

        $userBookings = [];
        $isLoggedIn   = isset($_SESSION['user']);
        if ($isLoggedIn) {
            $userId       = $_SESSION['user']['id'];
            $userBookings = $this->reviewModel->getUserReviewableBookings($userId);
        }

        $title = 'Đánh Giá & Nhận Xét Của Thực Khách - QHT Banquet';
        $view  = 'reviews';
        require_once PATH_VIEW_MAIN;
    }

    /**
     * Xử lý gửi hoặc cập nhật đánh giá & nhận xét
     */
    public function submit()
    {
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
               || (isset($_POST['is_ajax']) && $_POST['is_ajax'] == '1');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=danh-gia');
            exit;
        }

        if (!isset($_SESSION['user'])) {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để gửi đánh giá.']);
                exit;
            }
            $_SESSION['error'] = 'Vui lòng đăng nhập để gửi đánh giá.';
            header('Location: ' . BASE_URL . '?action=dang-nhap');
            exit;
        }

        $userId    = $_SESSION['user']['id'];
        $role      = $_SESSION['user']['vai_tro'] ?? 'khach';
        $bookingId = (int)($_POST['datban_id'] ?? 0);
        $soSao     = (int)($_POST['so_sao'] ?? 5);
        $binhLuan  = trim($_POST['binh_luan'] ?? '');

        if ($bookingId <= 0) {
            $msg = 'Vui lòng chọn đơn đặt bàn cần đánh giá.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            $_SESSION['error'] = $msg;
            $this->redirectBack();
        }

        if ($soSao < 1 || $soSao > 5) {
            $soSao = 5;
        }

        // Kiểm tra đơn đặt bàn có tồn tại và thuộc về khách hàng này không
        $booking = $this->bookingModel->getBookingDetail($bookingId);
        if (!$booking) {
            $msg = 'Không tìm thấy thông tin đơn đặt bàn này.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            $_SESSION['error'] = $msg;
            $this->redirectBack();
        }

        if ($role !== 'admin' && (int)$booking['nguoidung_id'] !== (int)$userId) {
            $msg = 'Bạn không có quyền đánh giá đơn đặt bàn của khách khác.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            $_SESSION['error'] = $msg;
            $this->redirectBack();
        }

        if (in_array($booking['trang_thai'], ['da_huy', 'tu_choi'])) {
            $msg = 'Đơn đặt bàn đã hủy hoặc bị từ chối, không thể gửi đánh giá.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            $_SESSION['error'] = $msg;
            $this->redirectBack();
        }

        $res = $this->reviewModel->createOrUpdate($bookingId, $soSao, $binhLuan);

        if ($res) {
            $msg = "<b>Cảm ơn bạn!</b> Đánh giá {$soSao} sao và nhận xét của bạn đã được ghi nhận thành công.";
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => true, 'message' => $msg, 'so_sao' => $soSao]);
                exit;
            }
            $_SESSION['success'] = $msg;
        } else {
            $msg = 'Có lỗi xảy ra khi lưu đánh giá. Vui lòng thử lại.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            $_SESSION['error'] = $msg;
        }

        $this->redirectBack();
    }

    /**
     * Xóa đánh giá
     */
    public function delete()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để thực hiện thao tác này.';
            header('Location: ' . BASE_URL . '?action=dang-nhap');
            exit;
        }

        $userId    = $_SESSION['user']['id'];
        $role      = $_SESSION['user']['vai_tro'] ?? 'khach';
        $bookingId = (int)($_POST['booking_id'] ?? $_GET['booking_id'] ?? 0);
        $reviewId  = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

        if ($role === 'admin') {
            if ($reviewId > 0) {
                $this->reviewModel->deleteReview($reviewId, null);
            } elseif ($bookingId > 0) {
                $this->reviewModel->deleteByBookingId($bookingId, null);
            }
            $_SESSION['success'] = 'Đã xóa đánh giá thành công.';
        } else {
            if ($bookingId > 0) {
                $this->reviewModel->deleteByBookingId($bookingId, $userId);
                $_SESSION['success'] = 'Đã xóa nhận xét của bạn.';
            } elseif ($reviewId > 0) {
                $this->reviewModel->deleteReview($reviewId, $userId);
                $_SESSION['success'] = 'Đã xóa nhận xét của bạn.';
            }
        }

        $this->redirectBack();
    }

    /**
     * API AJAX lấy dữ liệu đánh giá của một đơn để điền vào modal sửa
     */
    public function getReviewAjax()
    {
        header('Content-Type: application/json; charset=utf-8');
        $bookingId = (int)($_GET['booking_id'] ?? 0);
        if ($bookingId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Mã đơn không hợp lệ']);
            exit;
        }

        $review = $this->reviewModel->getByBookingId($bookingId);
        if ($review) {
            echo json_encode(['success' => true, 'data' => $review]);
        } else {
            echo json_encode(['success' => false, 'data' => null]);
        }
        exit;
    }

    /**
     * Điều hướng quay lại trang trước
     */
    private function redirectBack()
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? (BASE_URL . '?action=danh-gia');
        header('Location: ' . $ref);
        exit;
    }
}
