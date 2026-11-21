-- Script tạo bảng và cập nhật cấu trúc CSDL cho tính năng Thanh Toán
USE qht_banquet;

-- 1. Bổ sung các cột thông tin thanh toán vào bảng dat_ban (nếu chưa có)
SET @dbname = DATABASE();
SET @tablename = "dat_ban";
SET @columnname = "trang_thai_thanh_toan";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      TABLE_SCHEMA = @dbname
      AND TABLE_NAME = @tablename
      AND COLUMN_NAME = @columnname
  ) > 0,
  "SELECT 1",
  "ALTER TABLE dat_ban ADD COLUMN trang_thai_thanh_toan ENUM('chua_thanh_toan', 'da_dat_coc', 'da_thanh_toan', 'hoan_tien') NOT NULL DEFAULT 'chua_thanh_toan' AFTER trang_thai"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

SET @columnname = "so_tien_da_coc";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      TABLE_SCHEMA = @dbname
      AND TABLE_NAME = @tablename
      AND COLUMN_NAME = @columnname
  ) > 0,
  "SELECT 1",
  "ALTER TABLE dat_ban ADD COLUMN so_tien_da_coc DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER trang_thai_thanh_toan"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

SET @columnname = "so_tien_da_tra";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      TABLE_SCHEMA = @dbname
      AND TABLE_NAME = @tablename
      AND COLUMN_NAME = @columnname
  ) > 0,
  "SELECT 1",
  "ALTER TABLE dat_ban ADD COLUMN so_tien_da_tra DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER so_tien_da_coc"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- 2. Tạo bảng thanh_toan nếu chưa tồn tại
CREATE TABLE IF NOT EXISTS thanh_toan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    datban_id INT NOT NULL,
    nguoidung_id INT NULL,
    ma_giao_dich VARCHAR(64) NOT NULL UNIQUE,
    so_tien DECIMAL(12, 2) NOT NULL DEFAULT 0,
    loai_thanh_toan ENUM('dat_coc', 'toan_bo', 'thanh_toan_them') NOT NULL DEFAULT 'dat_coc',
    phuong_thuc ENUM('chuyen_khoan', 'vnpay', 'momo', 'tien_mat') NOT NULL DEFAULT 'chuyen_khoan',
    trang_thai ENUM('cho_xac_nhan', 'thanh_cong', 'that_bai', 'da_huy') NOT NULL DEFAULT 'thanh_cong',
    ngay_thanh_toan DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    noi_dung VARCHAR(255) NULL,
    anh_bien_lai VARCHAR(255) NULL,
    ghi_chu_admin TEXT NULL,
    ngay_tao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_datban (datban_id),
    INDEX idx_nguoidung (nguoidung_id),
    INDEX idx_magd (ma_giao_dich),
    CONSTRAINT fk_thanhtoan_datban FOREIGN KEY (datban_id) REFERENCES dat_ban(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Dữ liệu mẫu giao dịch thanh toán
INSERT INTO thanh_toan (datban_id, nguoidung_id, ma_giao_dich, so_tien, loai_thanh_toan, phuong_thuc, trang_thai, ngay_thanh_toan, noi_dung, ngay_tao)
SELECT 2, 2, 'PAY-20261113-DB2-7712', 500000, 'dat_coc', 'chuyen_khoan', 'thanh_cong', '2026-11-13 14:30:00', 'Dat coc giu ban DB2', '2026-11-13 14:30:00'
WHERE NOT EXISTS (SELECT 1 FROM thanh_toan WHERE ma_giao_dich = 'PAY-20261113-DB2-7712');

UPDATE dat_ban SET trang_thai_thanh_toan = 'da_dat_coc', so_tien_da_coc = 500000, so_tien_da_tra = 500000 WHERE id = 2;
UPDATE dat_ban SET trang_thai_thanh_toan = 'da_thanh_toan', so_tien_da_tra = 4800000 WHERE id = 3;
