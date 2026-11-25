<?php

class PaymentModel extends BaseModel
{
    protected $table = 'thanh_toan';

    public function __construct()
    {
        parent::__construct();
        $this->ensureSchema();
    }

    /**
     * Tự động kiểm tra và thêm bảng thanh_toan và các cột trong dat_ban nếu chưa có
     */
    private function ensureSchema()
    {
        try {
            // 1. Kiểm tra bảng thanh_toan
            $checkTable = $this->pdo->query("SHOW TABLES LIKE '{$this->table}'")->fetch();
            if (!$checkTable) {
                $createSql = "
                    CREATE TABLE IF NOT EXISTS `{$this->table}` (
                        `id` INT AUTO_INCREMENT PRIMARY KEY,
                        `datban_id` INT NOT NULL,
                        `nguoidung_id` INT NULL,
                        `ma_giao_dich` VARCHAR(64) NOT NULL UNIQUE,
                        `so_tien` DECIMAL(12, 2) NOT NULL DEFAULT 0,
                        `loai_thanh_toan` ENUM('dat_coc', 'toan_bo', 'thanh_toan_them') NOT NULL DEFAULT 'dat_coc',
                        `phuong_thuc` ENUM('chuyen_khoan', 'vnpay', 'momo', 'tien_mat') NOT NULL DEFAULT 'chuyen_khoan',
                        `trang_thai` ENUM('cho_xac_nhan', 'thanh_cong', 'that_bai', 'da_huy') NOT NULL DEFAULT 'thanh_cong',
                        `ngay_thanh_toan` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        `noi_dung` VARCHAR(255) NULL,
                        `anh_bien_lai` VARCHAR(255) NULL,
                        `ghi_chu_admin` TEXT NULL,
                        `ngay_tao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        INDEX `idx_datban` (`datban_id`),
                        INDEX `idx_magd` (`ma_giao_dich`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                ";
                $this->pdo->exec($createSql);
            }

            // 2. Kiểm tra các cột trong dat_ban
            $cols = $this->pdo->query("SHOW COLUMNS FROM dat_ban")->fetchAll(PDO::FETCH_COLUMN);

            if (!in_array('trang_thai_thanh_toan', $cols)) {
                $this->pdo->exec("
                    ALTER TABLE dat_ban 
                    ADD COLUMN `trang_thai_thanh_toan` ENUM('chua_thanh_toan', 'da_dat_coc', 'da_thanh_toan', 'hoan_tien') NOT NULL DEFAULT 'chua_thanh_toan' AFTER `trang_thai`
                ");
            }

            if (!in_array('tien_coc', $cols)) {
                $this->pdo->exec("
                    ALTER TABLE dat_ban 
                    ADD COLUMN `tien_coc` DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER `trang_thai_thanh_toan`
                ");
            }

            if (!in_array('so_tien_da_coc', $cols)) {
                $this->pdo->exec("
                    ALTER TABLE dat_ban 
                    ADD COLUMN `so_tien_da_coc` DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER `tien_coc`
                ");
            }

            if (!in_array('so_tien_da_tra', $cols)) {
                $this->pdo->exec("
                    ALTER TABLE dat_ban 
                    ADD COLUMN `so_tien_da_tra` DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER `so_tien_da_coc`
                ");
            }
        } catch (Exception $e) {
            error_log("PaymentModel schema check error: " . $e->getMessage());
        }
    }

    /**
     * Tạo mã giao dịch duy nhất
     */
    public function generateTransactionCode($bookingId)
    {
        $prefix = 'PAY-' . date('Ymd') . '-DB' . $bookingId;
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
        return $prefix . '-' . $random;
    }

    /**
     * Tạo bản ghi thanh toán mới và cập nhật trạng thái đơn đặt bàn
     */
    public function recordPayment(array $data)
    {
        try {
            $this->pdo->beginTransaction();

            $bookingId = (int)$data['datban_id'];
            $soTien    = (float)$data['so_tien'];
            $loai      = $data['loai_thanh_toan'] ?? 'dat_coc';
            $pt        = $data['phuong_thuc'] ?? 'chuyen_khoan';
            $status    = $data['trang_thai'] ?? 'thanh_cong';
            $maGd      = $data['ma_giao_dich'] ?? $this->generateTransactionCode($bookingId);
            $userId    = !empty($data['nguoidung_id']) ? (int)$data['nguoidung_id'] : null;
            $noiDung   = $data['noi_dung'] ?? '';
            $anhBill   = $data['anh_bien_lai'] ?? null;
            $adminNote = $data['ghi_chu_admin'] ?? null;

            // 1. Kiểm tra đơn đặt bàn
            $stmtBooking = $this->pdo->prepare("SELECT * FROM dat_ban WHERE id = :id FOR UPDATE");
            $stmtBooking->execute([':id' => $bookingId]);
            $booking = $stmtBooking->fetch();

            if (!$booking) {
                $this->pdo->rollBack();
                return false;
            }

            // 2. Chèn vào bảng thanh_toan
            $sqlInsert = "
                INSERT INTO {$this->table} 
                (datban_id, nguoidung_id, ma_giao_dich, so_tien, loai_thanh_toan, phuong_thuc, trang_thai, ngay_thanh_toan, noi_dung, anh_bien_lai, ghi_chu_admin, ngay_tao)
                VALUES 
                (:datban_id, :nguoidung_id, :ma_giao_dich, :so_tien, :loai_thanh_toan, :phuong_thuc, :trang_thai, NOW(), :noi_dung, :anh_bien_lai, :ghi_chu_admin, NOW())
            ";
            $stmtInsert = $this->pdo->prepare($sqlInsert);
            $stmtInsert->execute([
                ':datban_id'       => $bookingId,
                ':nguoidung_id'    => $userId,
                ':ma_giao_dich'    => $maGd,
                ':so_tien'         => $soTien,
                ':loai_thanh_toan' => $loai,
                ':phuong_thuc'     => $pt,
                ':trang_thai'      => $status,
                ':noi_dung'        => $noiDung,
                ':anh_bien_lai'    => $anhBill,
                ':ghi_chu_admin'   => $adminNote,
            ]);

            $paymentId = $this->pdo->lastInsertId();

            // 3. Nếu giao dịch thành công -> Cập nhật tổng số tiền đã thanh toán & trạng thái của dat_ban
            if ($status === 'thanh_cong') {
                $this->recalculateBookingPayment($bookingId);
            }

            $this->pdo->commit();
            return [
                'payment_id'   => $paymentId,
                'ma_giao_dich' => $maGd,
            ];
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("Lỗi ghi nhận thanh toán: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Tính toán lại số tiền đã trả và cập nhật trạng thái thanh toán của đơn đặt bàn.
     * Nếu thanh toán đủ 100%: chuyển trang_thai đơn → 'hoan_thanh' và giải phóng bàn.
     */
    public function recalculateBookingPayment($bookingId)
    {
        $bookingId = (int)$bookingId;

        // Lấy thông tin đơn đặt bàn
        $stmtB = $this->pdo->prepare("SELECT tong_tien, trang_thai, banphong_id FROM dat_ban WHERE id = :id");
        $stmtB->execute([':id' => $bookingId]);
        $booking = $stmtB->fetch();
        if (!$booking) return false;

        $tongTien = (float)$booking['tong_tien'];

        // Tính tổng tiền các giao dịch thành công
        $stmtPay = $this->pdo->prepare("
            SELECT 
                COALESCE(SUM(so_tien), 0) AS tong_da_tra,
                COALESCE(SUM(CASE WHEN loai_thanh_toan = 'dat_coc' THEN so_tien ELSE 0 END), 0) AS tong_da_coc
            FROM {$this->table}
            WHERE datban_id = :datban_id AND trang_thai = 'thanh_cong'
        ");
        $stmtPay->execute([':datban_id' => $bookingId]);
        $paySummary = $stmtPay->fetch();

        $tongDaTra = (float)($paySummary['tong_da_tra'] ?? 0);
        $tongDaCoc = (float)($paySummary['tong_da_coc'] ?? 0);

        // Xác định trạng thái thanh toán
        $trangThaiThanhToan = 'chua_thanh_toan';
        if ($tongDaTra >= $tongTien && $tongTien > 0) {
            $trangThaiThanhToan = 'da_thanh_toan';
        } elseif ($tongDaTra > 0) {
            $trangThaiThanhToan = 'da_dat_coc';
        }

        $stmtUp = $this->pdo->prepare("
            UPDATE dat_ban 
            SET so_tien_da_tra = :so_tien_da_tra,
                so_tien_da_coc = :so_tien_da_coc,
                trang_thai_thanh_toan = :trang_thai_thanh_toan
            WHERE id = :id
        ");
        $res = $stmtUp->execute([
            ':so_tien_da_tra'        => $tongDaTra,
            ':so_tien_da_coc'        => $tongDaCoc,
            ':trang_thai_thanh_toan' => $trangThaiThanhToan,
            ':id'                    => $bookingId,
        ]);

        // ══════════════════════════════════════════════════════
        // Nếu đã thanh toán đủ 100% → chuyển đơn sang 'hoan_thanh'
        // và giải phóng toàn bộ bàn (xóa datban_banphong + reset banphong_id = NULL)
        // ══════════════════════════════════════════════════════
        if ($trangThaiThanhToan === 'da_thanh_toan'
            && !in_array($booking['trang_thai'], ['hoan_thanh', 'da_huy', 'tu_choi'])
        ) {
            try {
                // 1. Chuyển trạng thái đơn sang hoàn thành
                $this->pdo->prepare("
                    UPDATE dat_ban SET trang_thai = 'hoan_thanh' WHERE id = :id
                ")->execute([':id' => $bookingId]);

                // 2. Xóa liên kết bàn ghép (datban_banphong)
                $this->pdo->prepare("
                    DELETE FROM datban_banphong WHERE datban_id = :datban_id
                ")->execute([':datban_id' => $bookingId]);

                // 3. Reset banphong_id về NULL để bàn hiển thị trống
                $this->pdo->prepare("
                    UPDATE dat_ban SET banphong_id = NULL WHERE id = :id
                ")->execute([':id' => $bookingId]);

            } catch (\Exception $e) {
                error_log("recalculateBookingPayment - giải phóng bàn lỗi: " . $e->getMessage());
            }
        }

        return $res;
    }

    /**
     * Lấy thông tin chi tiết 1 giao dịch thanh toán
     */
    public function getPaymentById($id)
    {
        $sql = "
            SELECT p.*, 
                   db.tong_tien, db.ngay_dat, db.ca, db.so_nguoi, db.trang_thai AS trang_thai_dat_ban,
                   db.trang_thai_thanh_toan, db.so_tien_da_tra,
                   nd.ho_ten AS ten_khach, nd.email AS email_khach, nd.so_dien_thoai AS sdt_khach,
                   bp.ten AS ten_ban, bp.loai AS loai_ban,
                   ld.ten AS ten_dip,
                   st.ten AS ten_set
            FROM {$this->table} p
            JOIN dat_ban db ON p.datban_id = db.id
            LEFT JOIN nguoidung nd ON db.nguoidung_id = nd.id
            LEFT JOIN ban_phong bp ON db.banphong_id = bp.id
            LEFT JOIN loai_dip ld ON db.loaidip_id = ld.id
            LEFT JOIN set_tiec st ON db.set_id = st.id
            WHERE p.id = :id
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Lấy thông tin giao dịch theo mã giao dịch
     */
    public function getPaymentByCode($code)
    {
        $sql = "
            SELECT p.*, 
                   db.tong_tien, db.ngay_dat, db.ca, db.so_nguoi, db.trang_thai AS trang_thai_dat_ban,
                   db.trang_thai_thanh_toan, db.so_tien_da_tra,
                   nd.ho_ten AS ten_khach, nd.email AS email_khach, nd.so_dien_thoai AS sdt_khach,
                   bp.ten AS ten_ban, bp.loai AS loai_ban,
                   ld.ten AS ten_dip,
                   st.ten AS ten_set
            FROM {$this->table} p
            JOIN dat_ban db ON p.datban_id = db.id
            LEFT JOIN nguoidung nd ON db.nguoidung_id = nd.id
            LEFT JOIN ban_phong bp ON db.banphong_id = bp.id
            LEFT JOIN loai_dip ld ON db.loaidip_id = ld.id
            LEFT JOIN set_tiec st ON db.set_id = st.id
            WHERE p.ma_giao_dich = :code
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':code' => $code]);
        return $stmt->fetch();
    }

    /**
     * Lấy tất cả các lần thanh toán của một đơn đặt bàn
     */
    public function getPaymentsByBookingId($bookingId)
    {
        $sql = "
            SELECT * FROM {$this->table}
            WHERE datban_id = :booking_id
            ORDER BY id DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':booking_id' => $bookingId]);
        return $stmt->fetchAll();
    }

    /**
     * Lấy giao dịch đang chờ xác nhận (nếu có) của một đơn đặt bàn
     */
    public function getPendingPaymentByBookingId($bookingId)
    {
        $sql = "
            SELECT * FROM {$this->table}
            WHERE datban_id = :booking_id AND trang_thai = 'cho_xac_nhan'
            ORDER BY id DESC
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':booking_id' => (int)$bookingId]);
        return $stmt->fetch();
    }

    /**
     * Lấy danh sách giao dịch thanh toán cho trang Admin với bộ lọc
     */
    public function getAllPayments(array $filters = [])
    {
        $sql = "
            SELECT p.*,
                   db.id AS booking_id, db.tong_tien, db.ngay_dat, db.ca, db.trang_thai AS trang_thai_dat_ban,
                   nd.ho_ten AS ten_khach, nd.so_dien_thoai AS sdt_khach,
                   bp.ten AS ten_ban
            FROM {$this->table} p
            JOIN dat_ban db ON p.datban_id = db.id
            LEFT JOIN nguoidung nd ON db.nguoidung_id = nd.id
            LEFT JOIN ban_phong bp ON db.banphong_id = bp.id
            WHERE 1=1
        ";
        $params = [];

        // Lọc trạng thái
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $sql .= " AND p.trang_thai = :status";
            $params[':status'] = $filters['status'];
        }

        // Lọc phương thức
        if (!empty($filters['method']) && $filters['method'] !== 'all') {
            $sql .= " AND p.phuong_thuc = :method";
            $params[':method'] = $filters['method'];
        }

        // Lọc ngày
        if (!empty($filters['date'])) {
            $sql .= " AND DATE(p.ngay_thanh_toan) = :date";
            $params[':date'] = $filters['date'];
        }

        // Tìm kiếm
        if (!empty($filters['q'])) {
            $q = trim($filters['q']);
            $sql .= " AND (p.ma_giao_dich LIKE :q OR nd.ho_ten LIKE :q OR nd.so_dien_thoai LIKE :q OR p.datban_id = :exact_bid)";
            $params[':q'] = "%$q%";
            $params[':exact_bid'] = (int)$q;
        }

        $sql .= " ORDER BY p.id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Admin cập nhật trạng thái của giao dịch (ví dụ duyệt giao dịch chuyển khoản)
     */
    public function updatePaymentStatus($id, $newStatus, $adminNote = null)
    {
        $id = (int)$id;
        $payment = $this->getPaymentById($id);
        if (!$payment) return false;

        $sql = "UPDATE {$this->table} SET trang_thai = :status";
        $params = [
            ':status' => $newStatus,
            ':id'     => $id
        ];

        if ($adminNote !== null) {
            $sql .= ", ghi_chu_admin = :ghi_chu_admin";
            $params[':ghi_chu_admin'] = $adminNote;
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute($params);

        if ($res) {
            $this->recalculateBookingPayment($payment['datban_id']);
            return true;
        }
        return false;
    }

    /**
     * Thiết lập số tiền cọc yêu cầu cho một đơn đặt bàn
     */
    public function setDepositRequirement($bookingId, $amount)
    {
        $stmt = $this->pdo->prepare("UPDATE dat_ban SET tien_coc = :amount WHERE id = :id");
        return $stmt->execute([
            ':amount' => (float)$amount,
            ':id'     => (int)$bookingId,
        ]);
    }

    /**
     * Thống kê thanh toán cho Dashboard
     */
    public function getPaymentStats()
    {
        $sql = "
            SELECT 
                COUNT(*) AS total_transactions,
                SUM(CASE WHEN trang_thai = 'thanh_cong' THEN so_tien ELSE 0 END) AS total_revenue,
                SUM(CASE WHEN trang_thai = 'cho_xac_nhan' THEN 1 ELSE 0 END) AS pending_confirm,
                SUM(CASE WHEN trang_thai = 'thanh_cong' THEN 1 ELSE 0 END) AS success_count,
                SUM(CASE WHEN DATE(ngay_thanh_toan) = CURDATE() AND trang_thai = 'thanh_cong' THEN so_tien ELSE 0 END) AS today_revenue
            FROM {$this->table}
        ";
        $stmt = $this->pdo->query($sql);
        $res = $stmt->fetch();

        return [
            'total_transactions' => (int)($res['total_transactions'] ?? 0),
            'total_revenue'      => (float)($res['total_revenue'] ?? 0),
            'pending_confirm'    => (int)($res['pending_confirm'] ?? 0),
            'success_count'      => (int)($res['success_count'] ?? 0),
            'today_revenue'      => (float)($res['today_revenue'] ?? 0),
        ];
    }
}
