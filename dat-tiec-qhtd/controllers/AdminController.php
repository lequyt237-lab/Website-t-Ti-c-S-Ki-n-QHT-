<?php

class AdminController extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->initCombineTableDb();
        $this->checkAdminAuth();
    }

    // Tự động khởi tạo bảng ghép bàn datban_banphong nếu chưa tồn tại
    private function initCombineTableDb()
    {
        try {
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS `datban_banphong` (
                  `id` INT AUTO_INCREMENT PRIMARY KEY,
                  `datban_id` INT NOT NULL,
                  `banphong_id` INT NOT NULL,
                  INDEX `idx_datban` (`datban_id`),
                  INDEX `idx_banphong` (`banphong_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
        } catch (Exception $e) {}
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

    // 1. Trang chủ Dashboard quản trị
    public function index()
    {
        // Thống kê tổng số
        $totalUsers      = $this->pdo->query("SELECT COUNT(*) FROM nguoidung")->fetchColumn();
        $totalTables     = $this->pdo->query("SELECT COUNT(*) FROM ban_phong")->fetchColumn();
        $totalDishes     = $this->pdo->query("SELECT COUNT(*) FROM mon_an")->fetchColumn();
        $totalSets       = $this->pdo->query("SELECT COUNT(*) FROM set_tiec")->fetchColumn();
        $totalBookings   = $this->pdo->query("SELECT COUNT(*) FROM dat_ban")->fetchColumn();
        $pendingBookings = $this->pdo->query("SELECT COUNT(*) FROM dat_ban WHERE trang_thai = 'cho_duyet'")->fetchColumn();

        // Thống kê thanh toán & doanh thu
        $paymentModel = new PaymentModel();
        $paymentStats = $paymentModel->getPaymentStats();

        // Danh sách đơn đặt bàn gần đây
        $sqlRecentBookings = "
            SELECT db.*, nd.ho_ten AS khach_hang, nd.so_dien_thoai,
                   COALESCE((SELECT GROUP_CONCAT(bp_sub.ten SEPARATOR ' + ') 
                             FROM datban_banphong dbp 
                             JOIN ban_phong bp_sub ON dbp.banphong_id = bp_sub.id 
                             WHERE dbp.datban_id = db.id), bp.ten) AS ten_ban,
                   (SELECT COUNT(*) FROM datban_banphong dbp WHERE dbp.datban_id = db.id) AS so_luong_ban_ghep,
                   ld.ten AS ten_dip, st.ten AS ten_set
            FROM dat_ban db
            JOIN nguoidung nd ON db.nguoidung_id = nd.id
            LEFT JOIN ban_phong bp ON db.banphong_id = bp.id
            LEFT JOIN loai_dip ld ON db.loaidip_id = ld.id
            LEFT JOIN set_tiec st ON db.set_id = st.id
            ORDER BY db.id DESC
            LIMIT 8
        ";
        $recentBookings = $this->pdo->query($sqlRecentBookings)->fetchAll();

        // Danh sách bàn/phòng
        $tables = $this->pdo->query("SELECT * FROM ban_phong ORDER BY id ASC")->fetchAll();

        $stats = [
            'users'            => $totalUsers,
            'tables'           => $totalTables,
            'dishes'           => $totalDishes,
            'sets'             => $totalSets,
            'bookings'         => $totalBookings,
            'pending'          => $pendingBookings,
            'revenue'          => $paymentStats['total_revenue'] ?? 0,
            'today_revenue'    => $paymentStats['today_revenue'] ?? 0,
            'pending_payments' => $paymentStats['pending_confirm'] ?? 0,
        ];

        $title      = 'Bảng điều khiển Quản trị - QHT Banquet';
        $activeMenu = 'dashboard';
        $adminView  = 'dashboard';

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

    // 2. Quản lý danh sách Đặt Bàn (Bộ lọc trạng thái, tìm kiếm, ngày tháng)
    public function bookings()
    {
        $status = trim($_GET['status'] ?? 'all');
        $search = trim($_GET['q'] ?? '');
        $date   = trim($_GET['date'] ?? '');

        // Đếm số lượng theo từng trạng thái để làm badge tab
        $counts = [
            'all'         => (int)$this->pdo->query("SELECT COUNT(*) FROM dat_ban")->fetchColumn(),
            'cho_duyet'   => (int)$this->pdo->query("SELECT COUNT(*) FROM dat_ban WHERE trang_thai = 'cho_duyet'")->fetchColumn(),
            'da_duyet'    => (int)$this->pdo->query("SELECT COUNT(*) FROM dat_ban WHERE trang_thai = 'da_duyet'")->fetchColumn(),
            'yeu_cau_huy' => (int)$this->pdo->query("SELECT COUNT(*) FROM dat_ban WHERE trang_thai = 'yeu_cau_huy'")->fetchColumn(),
            'hoan_thanh'  => (int)$this->pdo->query("SELECT COUNT(*) FROM dat_ban WHERE trang_thai = 'hoan_thanh'")->fetchColumn(),
            'tu_choi'     => (int)$this->pdo->query("SELECT COUNT(*) FROM dat_ban WHERE trang_thai = 'tu_choi'")->fetchColumn(),
            'huy'         => (int)$this->pdo->query("SELECT COUNT(*) FROM dat_ban WHERE trang_thai = 'huy'")->fetchColumn(),
        ];

        // Xây dựng câu truy vấn có lọc
        $sql = "
            SELECT db.*, 
                   nd.ho_ten AS khach_hang, nd.so_dien_thoai, nd.email,
                   COALESCE((SELECT GROUP_CONCAT(bp_sub.ten SEPARATOR ' + ') 
                             FROM datban_banphong dbp 
                             JOIN ban_phong bp_sub ON dbp.banphong_id = bp_sub.id 
                             WHERE dbp.datban_id = db.id), bp.ten) AS ten_ban,
                   bp.loai AS loai_ban,
                   COALESCE((SELECT SUM(bp_sub.suc_chua) 
                             FROM datban_banphong dbp 
                             JOIN ban_phong bp_sub ON dbp.banphong_id = bp_sub.id 
                             WHERE dbp.datban_id = db.id), bp.suc_chua) AS suc_chua,
                   (SELECT COUNT(*) FROM datban_banphong dbp WHERE dbp.datban_id = db.id) AS so_luong_ban_ghep,
                   bp.phu_phi,
                   ld.ten AS ten_dip,
                   st.ten AS ten_set,
                   (SELECT COUNT(*) FROM chitiet_mon cm WHERE cm.datban_id = db.id) AS so_mon_ngoai
            FROM dat_ban db
            JOIN nguoidung nd ON db.nguoidung_id = nd.id
            LEFT JOIN ban_phong bp ON db.banphong_id = bp.id
            LEFT JOIN loai_dip ld ON db.loaidip_id = ld.id
            LEFT JOIN set_tiec st ON db.set_id = st.id
            WHERE 1=1
        ";
        $params = [];

        if ($status !== 'all' && in_array($status, ['cho_duyet', 'da_duyet', 'yeu_cau_huy', 'hoan_thanh', 'tu_choi', 'huy'])) {
            $sql .= " AND db.trang_thai = :status";
            $params[':status'] = $status;
        }

        if (!empty($date)) {
            $sql .= " AND db.ngay_dat = :date";
            $params[':date'] = $date;
        }

        if (!empty($search)) {
            $sql .= " AND (nd.ho_ten LIKE :q OR nd.so_dien_thoai LIKE :q OR db.id = :exact_id)";
            $params[':q'] = "%$search%";
            $params[':exact_id'] = (int)$search;
        }

        $sql .= " ORDER BY db.id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $bookings = $stmt->fetchAll();

        $title      = 'Quản lý Duyệt Đặt Bàn - QHT Banquet';
        $activeMenu = 'dat-ban';
        $adminView  = 'bookings/index';

        require_once PATH_VIEW . 'admin/layout.php';
    }

    // 3. Xem chi tiết đơn đặt bàn
    public function bookingDetail()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error'] = 'Mã đơn đặt bàn không hợp lệ.';
            header('Location: ' . BASE_URL . '?action=admin-dat-ban');
            exit;
        }

        $sql = "
            SELECT db.*, 
                   nd.ho_ten AS khach_hang, nd.so_dien_thoai, nd.email,
                   COALESCE((SELECT GROUP_CONCAT(bp_sub.ten SEPARATOR ' + ') 
                             FROM datban_banphong dbp 
                             JOIN ban_phong bp_sub ON dbp.banphong_id = bp_sub.id 
                             WHERE dbp.datban_id = db.id), bp.ten) AS ten_ban,
                   bp.loai AS loai_ban,
                   COALESCE((SELECT SUM(bp_sub.suc_chua) 
                             FROM datban_banphong dbp 
                             JOIN ban_phong bp_sub ON dbp.banphong_id = bp_sub.id 
                             WHERE dbp.datban_id = db.id), bp.suc_chua) AS suc_chua,
                   (SELECT COUNT(*) FROM datban_banphong dbp WHERE dbp.datban_id = db.id) AS so_luong_ban_ghep,
                   bp.phu_phi, bp.mo_ta AS mota_ban,
                   ld.ten AS ten_dip, ld.mo_ta AS mota_dip,
                   st.ten AS ten_set, st.gia AS gia_set
            FROM dat_ban db
            JOIN nguoidung nd ON db.nguoidung_id = nd.id
            LEFT JOIN ban_phong bp ON db.banphong_id = bp.id
            LEFT JOIN loai_dip ld ON db.loaidip_id = ld.id
            LEFT JOIN set_tiec st ON db.set_id = st.id
            WHERE db.id = :id
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $booking = $stmt->fetch();

        if (!$booking) {
            $_SESSION['error'] = "Không tìm thấy đơn đặt bàn #$id.";
            header('Location: ' . BASE_URL . '?action=admin-dat-ban');
            exit;
        }

        // Lấy danh sách các bàn/phòng đang gán cho đơn này
        $assignedTableIds = [];
        try {
            $stmtAssigned = $this->pdo->prepare("SELECT banphong_id FROM datban_banphong WHERE datban_id = :id");
            $stmtAssigned->execute([':id' => $id]);
            $assignedTableIds = $stmtAssigned->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {}
        if (empty($assignedTableIds) && !empty($booking['banphong_id'])) {
            $assignedTableIds = [(int)$booking['banphong_id']];
        }

        // Lấy danh sách tất cả bàn & phòng trong hệ thống
        $allTables = $this->pdo->query("SELECT * FROM ban_phong ORDER BY loai DESC, suc_chua ASC, id ASC")->fetchAll();

        // Tách danh sách: Phòng VIP và Bàn sảnh
        $phongVIPList = array_values(array_filter($allTables, fn($t) => in_array($t['loai'], ['phong_vip', 'phong_rieng'])));
        $banSanhList  = array_values(array_filter($allTables, fn($t) => !in_array($t['loai'], ['phong_vip', 'phong_rieng'])));

        // Lấy danh sách món ăn gọi thêm trong chi tiết nếu có
        $sqlExtraDishes = "
            SELECT cm.*, ma.ten AS ten_mon, ma.nhom_mon, ma.anh AS anh_mon, ma.mo_ta AS mota_mon
            FROM chitiet_mon cm
            JOIN mon_an ma ON cm.mon_id = ma.id
            WHERE cm.datban_id = :datban_id
            ORDER BY ma.nhom_mon ASC, ma.ten ASC
        ";
        $stmtExtra = $this->pdo->prepare($sqlExtraDishes);
        $stmtExtra->execute([':datban_id' => $id]);
        $extraDishes = $stmtExtra->fetchAll();

        // Lấy danh sách các món ăn trong Set Tiệc nếu khách có đặt Set
        $setDishes = [];
        if (!empty($booking['set_id'])) {
            $sqlSetDishes = "
                SELECT ma.*, sm.id AS set_mon_id
                FROM set_mon sm
                JOIN mon_an ma ON sm.mon_id = ma.id
                WHERE sm.set_id = :set_id
                ORDER BY ma.nhom_mon ASC, ma.ten ASC
            ";
            $stmtSet = $this->pdo->prepare($sqlSetDishes);
            $stmtSet->execute([':set_id' => $booking['set_id']]);
            $setDishes = $stmtSet->fetchAll();
        }

        // Tính các khoản tiền chi tiết
        $setPrice = (float)($booking['gia_set_luc_dat'] ?? 0);
        $vipFee   = (!empty($booking['loai_ban']) && $booking['loai_ban'] === 'phong_rieng') ? (float)($booking['phu_phi'] ?? 0) : 0;
        $totalExtraPrice = 0;
        foreach ($extraDishes as $ed) {
            $totalExtraPrice += ((float)$ed['don_gia'] * (int)$ed['so_luong']);
        }

        // Lấy các bàn đã bị đơn khác duyệt vào cùng ngày & ca (Occupied Table Map)
        $occupiedTableMap = [];
        try {
            $sqlOcc = "
                SELECT dbp.banphong_id, db.id AS booking_id
                FROM datban_banphong dbp
                JOIN dat_ban db ON dbp.datban_id = db.id
                WHERE db.ngay_dat = :ngay_dat AND db.ca = :ca AND db.trang_thai = 'da_duyet' AND db.id != :curr_id
                UNION
                SELECT db.banphong_id, db.id AS booking_id
                FROM dat_ban db
                WHERE db.ngay_dat = :ngay_dat AND db.ca = :ca AND db.trang_thai = 'da_duyet' AND db.banphong_id IS NOT NULL AND db.id != :curr_id
            ";
            $stmtOcc = $this->pdo->prepare($sqlOcc);
            $stmtOcc->execute([
                ':ngay_dat' => $booking['ngay_dat'],
                ':ca'       => $booking['ca'],
                ':curr_id'  => $id
            ]);
            foreach ($stmtOcc->fetchAll() as $row) {
                if (!empty($row['banphong_id'])) {
                    $occupiedTableMap[$row['banphong_id']] = $row['booking_id'];
                }
            }
        } catch (Exception $e) {
            $sqlOccFallback = "
                SELECT banphong_id, id AS booking_id
                FROM dat_ban
                WHERE ngay_dat = :ngay_dat AND ca = :ca AND trang_thai = 'da_duyet' AND id != :curr_id AND banphong_id IS NOT NULL
            ";
            $stmtOcc = $this->pdo->prepare($sqlOccFallback);
            $stmtOcc->execute([
                ':ngay_dat' => $booking['ngay_dat'],
                ':ca'       => $booking['ca'],
                ':curr_id'  => $id
            ]);
            foreach ($stmtOcc->fetchAll() as $row) {
                if (!empty($row['banphong_id'])) {
                    $occupiedTableMap[$row['banphong_id']] = $row['booking_id'];
                }
            }
        }

        // Kiểm tra xem vị trí hiện tại có bị xung đột không
        $conflictBooking = null;
        foreach ($assignedTableIds as $tId) {
            if (isset($occupiedTableMap[$tId])) {
                $conflictBooking = ['id' => $occupiedTableMap[$tId], 'banphong_id' => $tId];
                break;
            }
        }

        // Lấy lịch sử giao dịch thanh toán của đơn này
        $paymentModel = new PaymentModel();
        $payments = $paymentModel->getPaymentsByBookingId($id);
        $daTra = (float)($booking['so_tien_da_tra'] ?? 0);
        $conLai = max(0, (float)$booking['tong_tien'] - $daTra);

        $title      = "Chi tiết Đơn Đặt Bàn #$id - QHT Banquet";
        $activeMenu = 'dat-ban';
        $adminView  = 'bookings/detail';

        require_once PATH_VIEW . 'admin/layout.php';
    }

    // 4. Xử lý Cập nhật trạng thái đơn đặt bàn (Duyệt, Xếp bàn, Ghép bàn, Từ chối, Hoàn thành, Hủy)
    public function updateBookingStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=admin-dat-ban');
            exit;
        }

        $id        = (int)($_POST['id'] ?? 0);
        $newStatus = trim($_POST['trang_thai'] ?? '');
        $note      = trim($_POST['ghi_chu'] ?? '');
        $banOption = trim($_POST['ban_option'] ?? ''); // 'phong_vip' hoặc 'ghep_ban'

        $validStatuses = ['cho_duyet', 'da_duyet', 'tu_choi', 'hoan_thanh', 'huy'];
        if (!in_array($newStatus, $validStatuses)) {
            $_SESSION['error'] = 'Trạng thái cập nhật không hợp lệ.';
            header("Location: " . BASE_URL . "?action=admin-dat-ban-chi-tiet&id=$id");
            exit;
        }

        // Lấy thông tin đơn hiện tại
        $stmtCurr = $this->pdo->prepare("SELECT * FROM dat_ban WHERE id = :id LIMIT 1");
        $stmtCurr->execute([':id' => $id]);
        $booking = $stmtCurr->fetch();

        if (!$booking) {
            $_SESSION['error'] = 'Đơn đặt bàn không tồn tại.';
            header('Location: ' . BASE_URL . '?action=admin-dat-ban');
            exit;
        }

        // Thu thập danh sách bàn/phòng được chọn
        $selectedTableIds = [];
        $hasSubmittedTables = false;

        if ($banOption === 'phong_vip') {
            $hasSubmittedTables = true;
            if (!empty($_POST['banphong_vip_id'])) {
                $selectedTableIds = [(int)$_POST['banphong_vip_id']];
            }
        } elseif ($banOption === 'ghep_ban') {
            $hasSubmittedTables = true;
            if (!empty($_POST['ban_ids']) && is_array($_POST['ban_ids'])) {
                $selectedTableIds = array_map('intval', $_POST['ban_ids']);
                $selectedTableIds = array_values(array_filter($selectedTableIds, fn($v) => $v > 0));
            }
        } else {
            // Hỗ trợ fallback từ form đơn giản (nếu có)
            if (!empty($_POST['ban_ids']) && is_array($_POST['ban_ids'])) {
                $hasSubmittedTables = true;
                $selectedTableIds = array_map('intval', $_POST['ban_ids']);
                $selectedTableIds = array_values(array_filter($selectedTableIds, fn($v) => $v > 0));
            } elseif (isset($_POST['banphong_id'])) {
                $hasSubmittedTables = true;
                if (!empty($_POST['banphong_id'])) {
                    $selectedTableIds = [(int)$_POST['banphong_id']];
                }
            }
        }

        // Nếu không có submit bàn (ví dụ duyệt nhanh từ danh sách), lấy lại các bàn cũ
        if (!$hasSubmittedTables) {
            try {
                $stmtOldAssigned = $this->pdo->prepare("SELECT banphong_id FROM datban_banphong WHERE datban_id = :id");
                $stmtOldAssigned->execute([':id' => $id]);
                $selectedTableIds = $stmtOldAssigned->fetchAll(PDO::FETCH_COLUMN);
            } catch (Exception $e) {}
            if (empty($selectedTableIds) && !empty($booking['banphong_id'])) {
                $selectedTableIds = [(int)$booking['banphong_id']];
            }
        }

        // Nếu chuyển sang "Đã duyệt" -> BẮT BUỘC phải xếp bàn cho khách!
        if ($newStatus === 'da_duyet') {
            if (empty($selectedTableIds)) {
                $_SESSION['error'] = 'Không thể duyệt đơn này! Bạn vui lòng chọn Phòng VIP hoặc tích chọn ít nhất 1 bàn sảnh (ghép bàn) cho khách trước khi Duyệt.';
                header("Location: " . BASE_URL . "?action=admin-dat-ban-chi-tiet&id=$id");
                exit;
            }

            // Kiểm tra chống trùng bàn/phòng vào cùng ngày và ca cho TẤT CẢ các bàn được chọn
            $inPlaceholders = implode(',', array_fill(0, count($selectedTableIds), '?'));
            $sqlConflict = "
                SELECT dbp.banphong_id, db.id AS booking_id, bp.ten AS ten_ban
                FROM datban_banphong dbp
                JOIN dat_ban db ON dbp.datban_id = db.id
                JOIN ban_phong bp ON dbp.banphong_id = bp.id
                WHERE dbp.banphong_id IN ($inPlaceholders)
                  AND db.ngay_dat = ?
                  AND db.ca = ?
                  AND db.trang_thai = 'da_duyet'
                  AND db.id != ?
                UNION
                SELECT db.banphong_id, db.id AS booking_id, bp.ten AS ten_ban
                FROM dat_ban db
                JOIN ban_phong bp ON db.banphong_id = bp.id
                WHERE db.banphong_id IN ($inPlaceholders)
                  AND db.ngay_dat = ?
                  AND db.ca = ?
                  AND db.trang_thai = 'da_duyet'
                  AND db.id != ?
                LIMIT 1
            ";
            $paramsConflict = array_merge(
                $selectedTableIds,
                [$booking['ngay_dat'], $booking['ca'], $id],
                $selectedTableIds,
                [$booking['ngay_dat'], $booking['ca'], $id]
            );
            $stmtConf = $this->pdo->prepare($sqlConflict);
            $stmtConf->execute($paramsConflict);
            $conflict = $stmtConf->fetch();

            if ($conflict) {
                $_SESSION['error'] = "Không thể duyệt đơn này! Bàn \"{$conflict['ten_ban']}\" đã có một đơn khác (#{$conflict['booking_id']}) được duyệt trong cùng ca và ngày.";
                header("Location: " . BASE_URL . "?action=admin-dat-ban-chi-tiet&id=$id");
                exit;
            }
        }

        // Tính lại tổng tiền nếu có phụ phí phòng VIP khi Admin xếp/đổi bàn
        $tongTien = (float)$booking['tong_tien'];
        if ($hasSubmittedTables) {
            // Lấy phụ phí cũ
            $oldFee = 0;
            try {
                $stmtOldFee = $this->pdo->prepare("
                    SELECT COALESCE(SUM(bp.phu_phi), 0) 
                    FROM datban_banphong dbp 
                    JOIN ban_phong bp ON dbp.banphong_id = bp.id 
                    WHERE dbp.datban_id = :id
                ");
                $stmtOldFee->execute([':id' => $id]);
                $oldFee = (float)$stmtOldFee->fetchColumn();
            } catch (Exception $e) {}
            if ($oldFee == 0 && !empty($booking['banphong_id'])) {
                $stmtOldSingle = $this->pdo->prepare("SELECT phu_phi FROM ban_phong WHERE id = :id");
                $stmtOldSingle->execute([':id' => $booking['banphong_id']]);
                $oldFee = (float)$stmtOldSingle->fetchColumn();
            }

            // Lấy phụ phí mới của các bàn/phòng được chọn
            $newFee = 0;
            if (!empty($selectedTableIds)) {
                $inNewPlaceholders = implode(',', array_fill(0, count($selectedTableIds), '?'));
                $stmtNewFee = $this->pdo->prepare("SELECT COALESCE(SUM(phu_phi), 0) FROM ban_phong WHERE id IN ($inNewPlaceholders)");
                $stmtNewFee->execute($selectedTableIds);
                $newFee = (float)$stmtNewFee->fetchColumn();
            }

            $tongTien = max(0, $tongTien - $oldFee + $newFee);
        }

        $primaryBanphongId = !empty($selectedTableIds) ? reset($selectedTableIds) : null;

        // Cập nhật bảng dat_ban
        $sqlUpdate = "UPDATE dat_ban SET banphong_id = :banphong_id, trang_thai = :trang_thai, tong_tien = :tong_tien, ghi_chu = :ghi_chu WHERE id = :id";
        $stmtUp = $this->pdo->prepare($sqlUpdate);
        $stmtUp->execute([
            ':banphong_id' => $primaryBanphongId,
            ':trang_thai'  => $newStatus,
            ':tong_tien'   => $tongTien,
            ':ghi_chu'     => $note,
            ':id'          => $id,
        ]);

        // Đồng bộ danh sách bàn ghép vào datban_banphong
        if ($hasSubmittedTables) {
            try {
                $this->pdo->prepare("DELETE FROM datban_banphong WHERE datban_id = :id")->execute([':id' => $id]);
                if (!empty($selectedTableIds)) {
                    $stmtIns = $this->pdo->prepare("INSERT INTO datban_banphong (datban_id, banphong_id) VALUES (:datban_id, :banphong_id)");
                    foreach ($selectedTableIds as $bid) {
                        $stmtIns->execute([':datban_id' => $id, ':banphong_id' => $bid]);
                    }
                }
            } catch (Exception $e) {}
        }

        $statusLabels = [
            'cho_duyet'   => 'Chờ duyệt',
            'da_duyet'    => 'Đã duyệt',
            'yeu_cau_huy' => 'Yêu cầu hủy',
            'tu_choi'     => 'Từ chối',
            'hoan_thanh'  => 'Hoàn thành',
            'huy'         => 'Đã hủy',
        ];

        $_SESSION['success'] = "Cập nhật đơn đặt bàn #$id sang trạng thái [{$statusLabels[$newStatus]}] thành công!";

        // Chuyển hướng
        $redirect = $_POST['redirect'] ?? 'list';
        if ($redirect === 'detail') {
            header("Location: " . BASE_URL . "?action=admin-dat-ban-chi-tiet&id=$id");
        } else {
            header('Location: ' . BASE_URL . '?action=admin-dat-ban');
        }
        exit;
    }    /* ═══════════════════════════════════════════
       QUẢN LÝ THANH TOÁN & THU TIỀN (ADMIN)
    ═══════════════════════════════════════════ */

    // 5. Trang danh sách quản lý giao dịch thanh toán
    public function payments()
    {
        $status = trim($_GET['status'] ?? 'all');
        $method = trim($_GET['method'] ?? 'all');
        $search = trim($_GET['q'] ?? '');
        $date   = trim($_GET['date'] ?? '');

        $paymentModel = new PaymentModel();
        $filters = [
            'status' => $status,
            'method' => $method,
            'q'      => $search,
            'date'   => $date,
        ];

        $payments = $paymentModel->getAllPayments($filters);
        $stats    = $paymentModel->getPaymentStats();

        $title      = 'Quản Lý Giao Dịch Thanh Toán - QHT Banquet';
        $activeMenu = 'thanh-toan';
        $adminView  = 'payments/index';

        require_once PATH_VIEW . 'admin/layout.php';
    }

    // 6. Cập nhật trạng thái giao dịch thanh toán (Duyệt cọc, Xác nhận chuyển khoản...)
    public function updatePaymentStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=admin-thanh-toan');
            exit;
        }

        $id        = (int)($_POST['id'] ?? 0);
        $newStatus = trim($_POST['trang_thai'] ?? '');
        $adminNote = trim($_POST['ghi_chu_admin'] ?? '');
        $redirect  = $_POST['redirect'] ?? 'list';
        $bookingId = (int)($_POST['booking_id'] ?? 0);

        $validStatuses = ['cho_xac_nhan', 'thanh_cong', 'that_bai', 'da_huy'];
        if ($id <= 0 || !in_array($newStatus, $validStatuses)) {
            $_SESSION['error'] = 'Trạng thái giao dịch không hợp lệ.';
            header('Location: ' . BASE_URL . '?action=admin-thanh-toan');
            exit;
        }

        $paymentModel = new PaymentModel();
        $res = $paymentModel->updatePaymentStatus($id, $newStatus, $adminNote);

        if ($res) {
            $_SESSION['success'] = "Cập nhật trạng thái giao dịch #$id thành công!";
        } else {
            $_SESSION['error'] = 'Không thể cập nhật trạng thái giao dịch này.';
        }

        if ($redirect === 'detail' && $bookingId > 0) {
            header("Location: " . BASE_URL . "?action=admin-dat-ban-chi-tiet&id=$bookingId");
        } else {
            header('Location: ' . BASE_URL . '?action=admin-thanh-toan');
        }
        exit;
    }

    // 7. Admin thu tiền trực tiếp tại quầy / ghi nhận tiền cọc cho đơn đặt bàn
    public function adminRecordPayment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=admin-dat-ban');
            exit;
        }

        $bookingId = (int)($_POST['datban_id'] ?? 0);
        $amount    = (float)($_POST['so_tien'] ?? 0);
        $type      = trim($_POST['loai_thanh_toan'] ?? 'dat_coc');
        $method    = trim($_POST['phuong_thuc'] ?? 'tien_mat');
        $note      = trim($_POST['noi_dung'] ?? 'Thu ngân nhận tiền tại quầy');

        if ($bookingId <= 0 || $amount <= 0) {
            $_SESSION['error'] = 'Vui lòng nhập số tiền thu hợp lệ lớn hơn 0.';
            header("Location: " . BASE_URL . "?action=admin-dat-ban-chi-tiet&id=$bookingId");
            exit;
        }

        // Lấy thông tin đơn đặt bàn
        $stmtBooking = $this->pdo->prepare("SELECT * FROM dat_ban WHERE id = :id LIMIT 1");
        $stmtBooking->execute([':id' => $bookingId]);
        $booking = $stmtBooking->fetch();

        if (!$booking) {
            $_SESSION['error'] = 'Đơn đặt bàn không tồn tại.';
            header('Location: ' . BASE_URL . '?action=admin-dat-ban');
            exit;
        }

        $paymentModel = new PaymentModel();
        $maGiaoDich = $paymentModel->generateTransactionCode($bookingId);

        $res = $paymentModel->recordPayment([
            'datban_id'       => $bookingId,
            'nguoidung_id'    => $booking['nguoidung_id'],
            'ma_giao_dich'    => $maGiaoDich,
            'so_tien'         => $amount,
            'loai_thanh_toan' => $type,
            'phuong_thuc'     => $method,
            'trang_thai'      => 'thanh_cong',
            'noi_dung'        => $note,
            'ghi_chu_admin'   => 'Admin ' . ($_SESSION['user']['ho_ten'] ?? 'Quản trị viên') . ' trực tiếp thu tiền',
        ]);

        if ($res) {
            $_SESSION['success'] = "Đã thu thành công " . number_format($amount, 0, ',', '.') . " đ cho đơn #DB$bookingId! (Mã GD: $maGiaoDich)";
        } else {
            $_SESSION['error'] = 'Lỗi ghi nhận khoản thu. Vui lòng thử lại.';
        }

        header("Location: " . BASE_URL . "?action=admin-dat-ban-chi-tiet&id=$bookingId");
        exit;
    }

    /* ═══════════════════════════════════════════
     * QUẢN LÝ MÓN ĂN
     * ═══════════════════════════════════════════ */

    public function monAn()
    {
        $model   = new MenuModel();
        $monList = $model->getAllDishes();
        $nhomList = $model->getCategories();

        // Món đang edit (nếu có ?edit=id)
        $editMon = null;
        if (!empty($_GET['edit'])) {
            $editMon = $model->getById((int)$_GET['edit']);
        }

        $title      = 'Quản lý Món Ăn - QHT Banquet';
        $activeMenu = 'mon-an';
        $adminView  = 'mon_an';
        require_once PATH_VIEW . 'admin/layout.php';
    }

    public function monAnStore()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=admin-mon-an'); exit;
        }

        $ten     = trim($_POST['ten'] ?? '');
        $nhom    = trim($_POST['nhom_mon'] ?? '');
        $gia     = (float)($_POST['gia'] ?? 0);
        $moTa    = trim($_POST['mo_ta'] ?? '');

        $errors = [];
        if (empty($ten))   $errors[] = 'Vui lòng nhập tên món.';
        if (empty($nhom))  $errors[] = 'Vui lòng chọn hoặc nhập nhóm món.';
        if ($gia <= 0)     $errors[] = 'Giá món phải lớn hơn 0.';

        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: ' . BASE_URL . '?action=admin-mon-an'); exit;
        }

        $model = new MenuModel();
        $model->create(['ten' => $ten, 'nhom_mon' => $nhom, 'gia' => $gia, 'mo_ta' => $moTa]);

        $_SESSION['success'] = "Đã thêm món \"$ten\" thành công!";
        header('Location: ' . BASE_URL . '?action=admin-mon-an'); exit;
    }

    public function monAnEdit()
    {
        $id = (int)($_GET['id'] ?? 0);
        header('Location: ' . BASE_URL . '?action=admin-mon-an&edit=' . $id);
        exit;
    }

    public function monAnUpdate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=admin-mon-an'); exit;
        }

        $id   = (int)($_POST['id'] ?? 0);
        $ten  = trim($_POST['ten'] ?? '');
        $nhom = trim($_POST['nhom_mon'] ?? '');
        $gia  = (float)($_POST['gia'] ?? 0);
        $moTa = trim($_POST['mo_ta'] ?? '');

        $errors = [];
        if ($id <= 0)      $errors[] = 'ID không hợp lệ.';
        if (empty($ten))   $errors[] = 'Vui lòng nhập tên món.';
        if (empty($nhom))  $errors[] = 'Vui lòng nhập nhóm món.';
        if ($gia <= 0)     $errors[] = 'Giá món phải lớn hơn 0.';

        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: ' . BASE_URL . '?action=admin-mon-an&edit=' . $id); exit;
        }

        $model = new MenuModel();
        $model->update($id, ['ten' => $ten, 'nhom_mon' => $nhom, 'gia' => $gia, 'mo_ta' => $moTa]);

        $_SESSION['success'] = "Đã cập nhật món \"$ten\" thành công!";
        header('Location: ' . BASE_URL . '?action=admin-mon-an'); exit;
    }

    public function monAnQuickPrice()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=admin-mon-an'); exit;
        }

        $id  = (int)($_POST['id'] ?? 0);
        $gia = (float)($_POST['gia'] ?? 0);

        if ($id <= 0 || $gia <= 0) {
            $_SESSION['error'] = 'Giá món ăn phải lớn hơn 0.';
        } else {
            $stmt = $this->pdo->prepare("UPDATE mon_an SET gia = :gia WHERE id = :id");
            $stmt->execute([':gia' => $gia, ':id' => $id]);
            $_SESSION['success'] = "Cập nhật giá thành công: " . number_format($gia, 0, ',', '.') . "đ!";
        }
        header('Location: ' . BASE_URL . '?action=admin-mon-an'); exit;
    }

    public function monAnDelete()
    {
        $id = (int)($_GET['id'] ?? (int)($_POST['id'] ?? 0));
        if ($id <= 0) {
            $_SESSION['error'] = 'ID không hợp lệ.';
            header('Location: ' . BASE_URL . '?action=admin-mon-an'); exit;
        }

        $model = new MenuModel();
        $mon = $model->getById($id);
        if (!$mon) {
            $_SESSION['error'] = 'Món ăn không tồn tại.';
            header('Location: ' . BASE_URL . '?action=admin-mon-an'); exit;
        }

        if ($model->isInUse($id)) {
            $_SESSION['error'] = "Không thể xóa \"" . htmlspecialchars($mon['ten']) . "\" vì đang được dùng trong set tiệc hoặc đơn đặt.";
            header('Location: ' . BASE_URL . '?action=admin-mon-an'); exit;
        }

        $model->delete($id);
        $_SESSION['success'] = "Đã xóa món \"" . htmlspecialchars($mon['ten']) . "\" thành công!";
        header('Location: ' . BASE_URL . '?action=admin-mon-an'); exit;
    }

    /* ═══════════════════════════════════════════
     * QUẢN LÝ BÀN & PHÒNG
     * ═══════════════════════════════════════════ */

    // Danh sách + form thêm mới
    public function banPhong()
    {
        $banPhongList = $this->pdo->query("SELECT * FROM ban_phong ORDER BY loai DESC, suc_chua ASC, id ASC")->fetchAll();

        // Lấy các đơn đang hoạt động (từ hôm nay trở đi) gắn với từng bàn/phòng (bao gồm cả ghép bàn)
        $sqlActive = "
            SELECT db.id AS booking_id, dbp.banphong_id, db.ngay_dat, db.ca,
                   db.so_nguoi, db.trang_thai, nd.ho_ten, nd.so_dien_thoai
            FROM dat_ban db
            JOIN datban_banphong dbp ON db.id = dbp.datban_id
            JOIN nguoidung nd ON db.nguoidung_id = nd.id
            WHERE db.trang_thai IN ('da_duyet','cho_duyet','yeu_cau_huy')
              AND db.ngay_dat >= CURDATE()
            UNION
            SELECT db.id AS booking_id, db.banphong_id, db.ngay_dat, db.ca,
                   db.so_nguoi, db.trang_thai, nd.ho_ten, nd.so_dien_thoai
            FROM dat_ban db
            JOIN nguoidung nd ON db.nguoidung_id = nd.id
            WHERE db.trang_thai IN ('da_duyet','cho_duyet','yeu_cau_huy')
              AND db.banphong_id IS NOT NULL
              AND db.ngay_dat >= CURDATE()
            ORDER BY ngay_dat ASC, ca ASC
        ";
        $activeBookings = [];
        try {
            $activeBookings = $this->pdo->query($sqlActive)->fetchAll();
        } catch (Exception $e) {
            $sqlFallback = "
                SELECT db.id AS booking_id, db.banphong_id, db.ngay_dat, db.ca,
                       db.so_nguoi, db.trang_thai, nd.ho_ten, nd.so_dien_thoai
                FROM dat_ban db
                JOIN nguoidung nd ON db.nguoidung_id = nd.id
                WHERE db.trang_thai IN ('da_duyet','cho_duyet','yeu_cau_huy')
                  AND db.banphong_id IS NOT NULL
                  AND db.ngay_dat >= CURDATE()
                ORDER BY db.ngay_dat ASC, db.ca ASC
            ";
            $activeBookings = $this->pdo->query($sqlFallback)->fetchAll();
        }

        // Nhóm theo banphong_id để dễ tra cứu trong view
        $bookingsByTable = [];
        foreach ($activeBookings as $ab) {
            $bookingsByTable[$ab['banphong_id']][] = $ab;
        }

        $title      = 'Quản lý Bàn & Phòng - QHT Banquet';
        $activeMenu = 'ban-phong';
        $adminView  = 'ban_phong';

        require_once PATH_VIEW . 'admin/layout.php';
    }

    // Lưu bàn/phòng mới
    public function banPhongStore()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=admin-ban-phong');
            exit;
        }

        $ten      = trim($_POST['ten'] ?? '');
        $loai     = $_POST['loai'] ?? 'ban_thuong';
        $sucChua  = (int)($_POST['suc_chua'] ?? 0);
        $phuPhi   = (float)($_POST['phu_phi'] ?? 0);
        $moTa     = trim($_POST['mo_ta'] ?? '');

        // Validate
        $errors = [];
        if (empty($ten)) {
            $errors[] = 'Vui lòng nhập tên bàn/phòng.';
        }
        if (!in_array($loai, ['ban_thuong', 'phong_vip'])) {
            $errors[] = 'Loại không hợp lệ.';
        }
        if ($sucChua <= 0) {
            $errors[] = 'Sức chứa phải lớn hơn 0.';
        }
        if ($phuPhi < 0) {
            $errors[] = 'Phụ phí không được âm.';
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: ' . BASE_URL . '?action=admin-ban-phong');
            exit;
        }

        $stmt = $this->pdo->prepare("INSERT INTO ban_phong (ten, loai, suc_chua, phu_phi, mo_ta) VALUES (:ten, :loai, :suc_chua, :phu_phi, :mo_ta)");
        $stmt->execute([
            ':ten'      => $ten,
            ':loai'     => $loai,
            ':suc_chua' => $sucChua,
            ':phu_phi'  => $phuPhi,
            ':mo_ta'    => $moTa ?: null,
        ]);

        $loaiLabel = $loai === 'phong_vip' ? 'Phòng VIP' : 'Bàn thường';
        $_SESSION['success'] = "Đã thêm {$loaiLabel} \"$ten\" thành công!";
        header('Location: ' . BASE_URL . '?action=admin-ban-phong');
        exit;
    }

    // Xóa bàn/phòng
    public function banPhongDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=admin-ban-phong');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error'] = 'ID không hợp lệ.';
            header('Location: ' . BASE_URL . '?action=admin-ban-phong');
            exit;
        }

        // Kiểm tra xem bàn/phòng này có đơn đặt đang hoạt động không (kể cả trong các đơn ghép bàn)
        $countActive = 0;
        try {
            $stmtCheck = $this->pdo->prepare("
                SELECT COUNT(*) FROM (
                    SELECT datban_id FROM datban_banphong WHERE banphong_id = :id1
                    UNION
                    SELECT id FROM dat_ban WHERE banphong_id = :id2
                ) active_ids
                JOIN dat_ban db ON active_ids.datban_id = db.id
                WHERE db.trang_thai IN ('cho_duyet','da_duyet','yeu_cau_huy')
            ");
            $stmtCheck->execute([':id1' => $id, ':id2' => $id]);
            $countActive = (int)$stmtCheck->fetchColumn();
        } catch (Exception $e) {
            $stmtCheckFallback = $this->pdo->prepare("
                SELECT COUNT(*) FROM dat_ban
                WHERE banphong_id = :id
                  AND trang_thai IN ('cho_duyet','da_duyet','yeu_cau_huy')
            ");
            $stmtCheckFallback->execute([':id' => $id]);
            $countActive = (int)$stmtCheckFallback->fetchColumn();
        }

        if ($countActive > 0) {
            $_SESSION['error'] = "Không thể xóa vì bàn/phòng này đang có $countActive đơn đặt đang hoạt động.";
            header('Location: ' . BASE_URL . '?action=admin-ban-phong');
            exit;
        }

        // Lấy tên trước khi xóa
        $stmtName = $this->pdo->prepare("SELECT ten FROM ban_phong WHERE id = :id");
        $stmtName->execute([':id' => $id]);
        $row = $stmtName->fetch();

        if (!$row) {
            $_SESSION['error'] = 'Bàn/Phòng không tồn tại.';
            header('Location: ' . BASE_URL . '?action=admin-ban-phong');
            exit;
        }

        $stmtDel = $this->pdo->prepare("DELETE FROM ban_phong WHERE id = :id");
        $stmtDel->execute([':id' => $id]);

        $_SESSION['success'] = "Đã xóa \"" . htmlspecialchars($row['ten']) . "\" khỏi hệ thống.";
        header('Location: ' . BASE_URL . '?action=admin-ban-phong');
        exit;
    }
}