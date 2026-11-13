<?php

class AdminController extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->checkAdminAuth();
    }

    // Middleware kiểm tra quyền admin
    private function checkAdminAuth()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập với tài khoản Quản trị viên để tiếp tục.';
            header('Location: ' . BASE_URL . '?action=dang-nhap');
            exit;
        }

        if (($_SESSION['user']['vai_tro'] ?? '') !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập vào trang Quản trị viên.';
            header('Location: ' . BASE_URL);
            exit;
        }
    }

    // Trang chủ Dashboard quản trị
    public function index()
    {
        // 1. Thống kê tổng số
        $totalUsers = $this->pdo->query("SELECT COUNT(*) FROM nguoidung")->fetchColumn();
        $totalTables = $this->pdo->query("SELECT COUNT(*) FROM ban_phong")->fetchColumn();
        $totalDishes = $this->pdo->query("SELECT COUNT(*) FROM mon_an")->fetchColumn();
        $totalSets   = $this->pdo->query("SELECT COUNT(*) FROM set_tiec")->fetchColumn();
        $totalBookings = $this->pdo->query("SELECT COUNT(*) FROM dat_ban")->fetchColumn();
        $pendingBookings = $this->pdo->query("SELECT COUNT(*) FROM dat_ban WHERE trang_thai = 'cho_duyet'")->fetchColumn();

        // 2. Danh sách đơn đặt bàn gần đây
        $sqlRecentBookings = "
            SELECT db.*, nd.ho_ten AS khach_hang, nd.so_dien_thoai, bp.ten AS ten_ban, ld.ten AS ten_dip, st.ten AS ten_set
            FROM dat_ban db
            JOIN nguoidung nd ON db.nguoidung_id = nd.id
            JOIN ban_phong bp ON db.banphong_id = bp.id
            JOIN loai_dip ld ON db.loaidip_id = ld.id
            LEFT JOIN set_tiec st ON db.set_id = st.id
            ORDER BY db.id DESC
            LIMIT 8
        ";
        $recentBookings = $this->pdo->query($sqlRecentBookings)->fetchAll();

        // 3. Danh sách bàn/phòng
        $tables = $this->pdo->query("SELECT * FROM ban_phong ORDER BY id ASC")->fetchAll();

        $stats = [
            'users'    => $totalUsers,
            'tables'   => $totalTables,
            'dishes'   => $totalDishes,
            'sets'     => $totalSets,
            'bookings' => $totalBookings,
            'pending'  => $pendingBookings,
        ];

        $title = 'Bảng điều khiển Quản trị - QHT Banquet';
        $adminView = 'dashboard';

        require_once PATH_VIEW . 'admin/layout.php';
    }
}

