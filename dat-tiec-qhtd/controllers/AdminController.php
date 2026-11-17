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

    /* ═══════════════════════════════════════════
       QUẢN LÝ SET TIỆC
    ═══════════════════════════════════════════ */

    // Danh sách Set Tiệc
    public function setTiec()
    {
        $model = new SetTiecModel();
        $sets = $model->getAll();

        $title = 'Quản Lý Thực Đơn Set Tiệc';
        $adminView = 'set_tiec';

        require_once PATH_VIEW . 'admin/layout.php';
    }

    // Form thêm mới Set Tiệc
    public function setTiecCreate()
    {
        $model = new SetTiecModel();
        $allMonAn = $model->getAllMonAn();
        $nhomMon = $model->getNhomMon();

        $title = 'Thêm Set Tiệc Mới';
        $adminView = 'set_tiec_form';
        $formAction = BASE_URL . '?action=admin-set-tiec-store';
        $set = null;
        $selectedMonIds = [];

        require_once PATH_VIEW . 'admin/layout.php';
    }

    // Lưu Set Tiệc mới
    public function setTiecStore()
    {
        $model = new SetTiecModel();

        $data = [
            'ten'             => trim($_POST['ten'] ?? ''),
            'gia'             => floatval($_POST['gia'] ?? 0),
            'so_nguoi_phu_hop'=> intval($_POST['so_nguoi_phu_hop'] ?? 0),
            'mo_ta'           => trim($_POST['mo_ta'] ?? ''),
        ];

        // Validate
        if (empty($data['ten']) || $data['gia'] <= 0 || $data['so_nguoi_phu_hop'] <= 0) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin (tên, giá > 0, số người > 0).';
            header('Location: ' . BASE_URL . '?action=admin-set-tiec-create');
            exit;
        }

        $setId = $model->create($data);

        // Thêm món vào set
        $monIds = $_POST['mon_ids'] ?? [];
        if (!empty($monIds)) {
            $model->syncMonForSet($setId, $monIds);
        }

        $_SESSION['success'] = "Đã thêm Set Tiệc \"{$data['ten']}\" thành công!";
        header('Location: ' . BASE_URL . '?action=admin-set-tiec');
        exit;
    }

    // Form chỉnh sửa Set Tiệc
    public function setTiecEdit()
    {
        $id = intval($_GET['id'] ?? 0);
        $model = new SetTiecModel();
        $set = $model->getById($id);

        if (!$set) {
            $_SESSION['error'] = 'Không tìm thấy Set Tiệc này.';
            header('Location: ' . BASE_URL . '?action=admin-set-tiec');
            exit;
        }

        $allMonAn = $model->getAllMonAn();
        $nhomMon = $model->getNhomMon();
        $selectedMonIds = $model->getMonIdsBySetId($id);

        $title = 'Chỉnh Sửa: ' . htmlspecialchars($set['ten']);
        $adminView = 'set_tiec_form';
        $formAction = BASE_URL . '?action=admin-set-tiec-update&id=' . $id;

        require_once PATH_VIEW . 'admin/layout.php';
    }

    // Cập nhật Set Tiệc
    public function setTiecUpdate()
    {
        $id = intval($_GET['id'] ?? 0);
        $model = new SetTiecModel();
        $set = $model->getById($id);

        if (!$set) {
            $_SESSION['error'] = 'Không tìm thấy Set Tiệc này.';
            header('Location: ' . BASE_URL . '?action=admin-set-tiec');
            exit;
        }

        $data = [
            'ten'             => trim($_POST['ten'] ?? ''),
            'gia'             => floatval($_POST['gia'] ?? 0),
            'so_nguoi_phu_hop'=> intval($_POST['so_nguoi_phu_hop'] ?? 0),
            'mo_ta'           => trim($_POST['mo_ta'] ?? ''),
        ];

        if (empty($data['ten']) || $data['gia'] <= 0 || $data['so_nguoi_phu_hop'] <= 0) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin.';
            header('Location: ' . BASE_URL . '?action=admin-set-tiec-edit&id=' . $id);
            exit;
        }

        $model->update($id, $data);

        // Đồng bộ danh sách món
        $monIds = $_POST['mon_ids'] ?? [];
        $model->syncMonForSet($id, $monIds);

        $_SESSION['success'] = "Đã cập nhật Set Tiệc \"{$data['ten']}\" thành công!";
        header('Location: ' . BASE_URL . '?action=admin-set-tiec');
        exit;
    }

    // Xóa Set Tiệc
    public function setTiecDelete()
    {
        $id = intval($_GET['id'] ?? 0);
        $model = new SetTiecModel();
        $set = $model->getById($id);

        if (!$set) {
            $_SESSION['error'] = 'Không tìm thấy Set Tiệc.';
            header('Location: ' . BASE_URL . '?action=admin-set-tiec');
            exit;
        }

        // Kiểm tra set có đang được dùng trong đơn đặt bàn không
        if ($model->isSetInUse($id)) {
            $_SESSION['error'] = "Không thể xóa \"{$set['ten']}\" vì đang có đơn đặt bàn sử dụng set này.";
            header('Location: ' . BASE_URL . '?action=admin-set-tiec');
            exit;
        }

        $model->delete($id);
        $_SESSION['success'] = "Đã xóa Set Tiệc \"{$set['ten']}\" thành công!";
        header('Location: ' . BASE_URL . '?action=admin-set-tiec');
        exit;
    }

    // Chi tiết Set Tiệc (xem danh sách món)
    public function setTiecDetail()
    {
        $id = intval($_GET['id'] ?? 0);
        $model = new SetTiecModel();
        $set = $model->getById($id);

        if (!$set) {
            $_SESSION['error'] = 'Không tìm thấy Set Tiệc.';
            header('Location: ' . BASE_URL . '?action=admin-set-tiec');
            exit;
        }

        $monTrongSet = $model->getMonBySetId($id);

        $title = 'Chi Tiết: ' . htmlspecialchars($set['ten']);
        $adminView = 'set_tiec_detail';

        require_once PATH_VIEW . 'admin/layout.php';
    }
}

