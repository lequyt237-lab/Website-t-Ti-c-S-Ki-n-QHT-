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
            SELECT db.*, nd.ho_ten AS khach_hang, nd.so_dien_thoai, bp.ten AS ten_ban, ld.ten AS ten_dip, st.ten AS ten_set
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
                   bp.ten AS ten_ban, bp.loai AS loai_ban, bp.suc_chua, bp.phu_phi,
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

        if ($status !== 'all' && in_array($status, ['cho_duyet', 'da_duyet', 'hoan_thanh', 'tu_choi', 'huy'])) {
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
                   bp.ten AS ten_ban, bp.loai AS loai_ban, bp.suc_chua, bp.phu_phi, bp.mo_ta AS mota_ban,
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

        // Lấy danh sách tất cả bàn & phòng để Admin xếp bàn
        $allTables = $this->pdo->query("SELECT * FROM ban_phong ORDER BY loai DESC, suc_chua ASC, id ASC")->fetchAll();

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

        // Kiểm tra xem vị trí này vào ngày & ca đó có bị trùng với đơn khác đã duyệt không (nếu đã xếp bàn)
        $conflictBooking = null;
        if (!empty($booking['banphong_id'])) {
            $sqlConflictCheck = "
                SELECT id, trang_thai 
                FROM dat_ban 
                WHERE banphong_id = :banphong_id 
                  AND ngay_dat = :ngay_dat 
                  AND ca = :ca 
                  AND trang_thai = 'da_duyet' 
                  AND id != :current_id
            ";
            $stmtConflict = $this->pdo->prepare($sqlConflictCheck);
            $stmtConflict->execute([
                ':banphong_id' => $booking['banphong_id'],
                ':ngay_dat'    => $booking['ngay_dat'],
                ':ca'          => $booking['ca'],
                ':current_id'  => $id
            ]);
            $conflictBooking = $stmtConflict->fetch();
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

    // 4. Xử lý Cập nhật trạng thái đơn đặt bàn (Duyệt, Xếp bàn, Từ chối, Hoàn thành, Hủy)
    public function updateBookingStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?action=admin-dat-ban');
            exit;
        }

        $id         = (int)($_POST['id'] ?? 0);
        $newStatus  = trim($_POST['trang_thai'] ?? '');
        $note       = trim($_POST['ghi_chu'] ?? '');
        $banphongId = isset($_POST['banphong_id']) && $_POST['banphong_id'] !== '' ? (int)$_POST['banphong_id'] : null;

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

        // Nếu không gửi banphong_id từ form (ví dụ duyệt nhanh từ list), giữ nguyên bàn cũ
        if (!isset($_POST['banphong_id'])) {
            $banphongId = $booking['banphong_id'];
        }

        // Nếu chuyển sang "Đã duyệt" -> BẮT BUỘC phải xếp bàn cho khách!
        if ($newStatus === 'da_duyet') {
            if (empty($banphongId)) {
                $_SESSION['error'] = 'Không thể duyệt đơn này! Bạn vui lòng chọn/xếp bàn hoặc phòng cho khách trước khi Duyệt đặt bàn.';
                header("Location: " . BASE_URL . "?action=admin-dat-ban-chi-tiet&id=$id");
                exit;
            }

            // Kiểm tra chống trùng bàn/phòng vào cùng ngày và ca
            $sqlConflict = "
                SELECT COUNT(*) FROM dat_ban 
                WHERE banphong_id = :banphong_id 
                  AND ngay_dat = :ngay_dat 
                  AND ca = :ca 
                  AND trang_thai = 'da_duyet' 
                  AND id != :id
            ";
            $stmtConf = $this->pdo->prepare($sqlConflict);
            $stmtConf->execute([
                ':banphong_id' => $banphongId,
                ':ngay_dat'    => $booking['ngay_dat'],
                ':ca'          => $booking['ca'],
                ':id'          => $id,
            ]);
            $isConflict = $stmtConf->fetchColumn() > 0;

            if ($isConflict) {
                $_SESSION['error'] = 'Không thể duyệt đơn này! Bàn/phòng này đã có một đơn khác được duyệt trong cùng ca và ngày.';
                header("Location: " . BASE_URL . "?action=admin-dat-ban-chi-tiet&id=$id");
                exit;
            }
        }

        // Tính lại tổng tiền nếu có phụ phí phòng VIP khi Admin xếp/đổi bàn
        $tongTien = (float)$booking['tong_tien'];
        if ($banphongId && $banphongId != $booking['banphong_id']) {
            // Lấy phụ phí của bàn mới
            $stmtNew = $this->pdo->prepare("SELECT phu_phi FROM ban_phong WHERE id = :id LIMIT 1");
            $stmtNew->execute([':id' => $banphongId]);
            $newRow = $stmtNew->fetch();
            $newFee = (float)($newRow['phu_phi'] ?? 0);

            // Lấy phụ phí của bàn cũ
            $oldFee = 0;
            if (!empty($booking['banphong_id'])) {
                $stmtOld = $this->pdo->prepare("SELECT phu_phi FROM ban_phong WHERE id = :id LIMIT 1");
                $stmtOld->execute([':id' => $booking['banphong_id']]);
                $oldRow = $stmtOld->fetch();
                $oldFee = (float)($oldRow['phu_phi'] ?? 0);
            }
            $tongTien = max(0, $tongTien - $oldFee + $newFee);
        }

        // Cập nhật trạng thái, bàn/phòng, tổng tiền và ghi chú
        $sqlUpdate = "UPDATE dat_ban SET banphong_id = :banphong_id, trang_thai = :trang_thai, tong_tien = :tong_tien, ghi_chu = :ghi_chu WHERE id = :id";
        $stmtUp = $this->pdo->prepare($sqlUpdate);
        $stmtUp->execute([
            ':banphong_id' => $banphongId,
            ':trang_thai'  => $newStatus,
            ':tong_tien'   => $tongTien,
            ':ghi_chu'     => $note,
            ':id'          => $id,
        ]);

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
    }

    /* ═══════════════════════════════════════════
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
}
