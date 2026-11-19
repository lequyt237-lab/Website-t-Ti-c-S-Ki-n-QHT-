<?php

class ProfileController
{
    private $userModel;
    private $bookingModel;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->bookingModel = new BookingModel();
    }

    // Middleware kiểm tra đăng nhập
    private function checkAuth()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để truy cập trang cá nhân.';
            header('Location: ' . BASE_URL . '?action=dang-nhap');
            exit;
        }
    }

    // Hiển thị trang cá nhân
    public function index()
    {
        $this->checkAuth();

        $userId = $_SESSION['user']['id'];
        $user   = $this->userModel->findById($userId);

        if (!$user) {
            unset($_SESSION['user']);
            $_SESSION['error'] = 'Không tìm thấy thông tin tài khoản.';
            header('Location: ' . BASE_URL . '?action=dang-nhap');
            exit;
        }

        // Lấy thống kê đơn đặt bàn
        $stats = $this->bookingModel->getBookingStatsByUserId($userId);

        // Lấy danh sách 5 đơn đặt bàn gần nhất
        $recentBookings = $this->bookingModel->getRecentBookingsByUserId($userId, 5);

        // Tính tổng tiền chi tiêu từ các đơn đã duyệt hoặc hoàn thành
        $allBookings = $this->bookingModel->getBookingsByUserId($userId);
        $totalSpent = 0;
        foreach ($allBookings as $b) {
            if (in_array($b['trang_thai'], ['da_duyet', 'hoan_thanh'])) {
                $totalSpent += (float)($b['tong_tien'] ?? 0);
            }
        }

        $title = 'Trang cá nhân - ' . htmlspecialchars($user['ho_ten']) . ' | QHT Banquet';
        $view  = 'profile';
        require_once PATH_VIEW . 'main.php';
    }

    // Cập nhật thông tin cá nhân
    public function updateInfo()
    {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=tai-khoan');
            exit;
        }

        $userId      = $_SESSION['user']['id'];
        $hoTen       = trim($_POST['ho_ten'] ?? '');
        $soDienThoai = trim($_POST['so_dien_thoai'] ?? '');

        $errors = [];
        if (empty($hoTen)) {
            $errors[] = 'Họ và tên không được để trống.';
        }
        if (empty($soDienThoai)) {
            $errors[] = 'Số điện thoại không được để trống.';
        } elseif (!preg_match('/^[0-9]{9,12}$/', $soDienThoai)) {
            $errors[] = 'Số điện thoại không hợp lệ (9-12 chữ số).';
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: ' . BASE_URL . '?action=tai-khoan');
            exit;
        }

        $success = $this->userModel->updateProfile($userId, [
            'ho_ten'        => $hoTen,
            'so_dien_thoai' => $soDienThoai,
        ]);

        if ($success) {
            $_SESSION['user']['ho_ten']        = $hoTen;
            $_SESSION['user']['so_dien_thoai'] = $soDienThoai;
            $_SESSION['success'] = 'Cập nhật thông tin cá nhân thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại.';
        }

        header('Location: ' . BASE_URL . '?action=tai-khoan');
        exit;
    }

    // Đổi mật khẩu
    public function updatePassword()
    {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=tai-khoan');
            exit;
        }

        $userId      = $_SESSION['user']['id'];
        $oldPassword = trim($_POST['mat_khau_cu'] ?? '');
        $newPassword = trim($_POST['mat_khau_moi'] ?? '');
        $rePassword  = trim($_POST['nhap_lai_mat_khau'] ?? '');

        $errors = [];
        if (empty($oldPassword)) {
            $errors[] = 'Vui lòng nhập mật khẩu hiện tại.';
        }
        if (empty($newPassword) || strlen($newPassword) < 6) {
            $errors[] = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
        }
        if ($newPassword !== $rePassword) {
            $errors[] = 'Xác nhận mật khẩu mới không trùng khớp.';
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: ' . BASE_URL . '?action=tai-khoan#tab-security');
            exit;
        }

        $user = $this->userModel->findById($userId);

        // Kiểm tra mật khẩu cũ (hỗ trợ cả băm và thường)
        $isOldValid = password_verify($oldPassword, $user['mat_khau']) || ($oldPassword === $user['mat_khau']);

        if (!$isOldValid) {
            $_SESSION['error'] = 'Mật khẩu hiện tại không chính xác.';
            header('Location: ' . BASE_URL . '?action=tai-khoan#tab-security');
            exit;
        }

        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $success = $this->userModel->updatePassword($userId, $hashed);

        if ($success) {
            $_SESSION['success'] = 'Đổi mật khẩu thành công! Hãy ghi nhớ mật khẩu mới của bạn.';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi đổi mật khẩu, vui lòng thử lại.';
        }

        header('Location: ' . BASE_URL . '?action=tai-khoan#tab-security');
        exit;
    }
}
