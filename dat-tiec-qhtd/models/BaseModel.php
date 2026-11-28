<?php

class BaseModel
{
    protected $table;
    protected $pdo;
    private static $bookingPaymentSchemaChecked = false;

    // Kết nối CSDL
    public function __construct()
    {
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_PORT, DB_NAME);

        try {
            $this->pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);
        } catch (PDOException $e) {
            // Xử lý lỗi kết nối
            die("Kết nối cơ sở dữ liệu thất bại: {$e->getMessage()}. Vui lòng thử lại sau.");
        }
    }

    /**
     * Đảm bảo các bảng và cột dùng cho đặt bàn, ghép bàn và thanh toán luôn tồn tại
     */
    protected function ensureBookingPaymentSchema()
    {
        if (self::$bookingPaymentSchemaChecked) {
            return;
        }
        self::$bookingPaymentSchemaChecked = true;

        try {
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS `thanh_toan` (
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
                    INDEX `idx_nguoidung` (`nguoidung_id`),
                    INDEX `idx_magd` (`ma_giao_dich`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");

            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS `datban_banphong` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `datban_id` INT NOT NULL,
                    `banphong_id` INT NOT NULL,
                    INDEX `idx_datban` (`datban_id`),
                    INDEX `idx_banphong` (`banphong_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");

            $cols = $this->pdo->query("SHOW COLUMNS FROM dat_ban")->fetchAll(PDO::FETCH_COLUMN);
            $alterCols = [
                'trang_thai_thanh_toan' => "ALTER TABLE dat_ban ADD COLUMN `trang_thai_thanh_toan` ENUM('chua_thanh_toan', 'da_dat_coc', 'da_thanh_toan', 'hoan_tien') NOT NULL DEFAULT 'chua_thanh_toan' AFTER `trang_thai`",
                'tien_coc'              => "ALTER TABLE dat_ban ADD COLUMN `tien_coc` DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER `trang_thai_thanh_toan`",
                'so_tien_da_coc'        => "ALTER TABLE dat_ban ADD COLUMN `so_tien_da_coc` DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER `tien_coc`",
                'so_tien_da_tra'        => "ALTER TABLE dat_ban ADD COLUMN `so_tien_da_tra` DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER `so_tien_da_coc`",
            ];
            foreach ($alterCols as $col => $sql) {
                if (!in_array($col, $cols, true)) {
                    $this->pdo->exec($sql);
                }
            }

            $statusCol = $this->pdo->query("SHOW COLUMNS FROM dat_ban LIKE 'trang_thai'")->fetch();
            if ($statusCol && (!str_contains($statusCol['Type'], 'yeu_cau_huy') || !str_contains($statusCol['Type'], 'da_huy'))) {
                $this->pdo->exec("
                    ALTER TABLE dat_ban
                    MODIFY COLUMN `trang_thai` ENUM('cho_duyet', 'da_duyet', 'yeu_cau_huy', 'tu_choi', 'hoan_thanh', 'da_huy', 'huy') NOT NULL DEFAULT 'cho_duyet'
                ");
            }
        } catch (Exception $e) {
            error_log("Booking/Payment schema check error: " . $e->getMessage());
        }
    }

    // Hủy kết nối CSDL
    public function __destruct()
    {
        $this->pdo = null;
    }
}
