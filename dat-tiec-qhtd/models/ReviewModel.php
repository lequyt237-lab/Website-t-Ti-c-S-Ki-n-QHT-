<?php

class ReviewModel extends BaseModel
{
    protected $table = 'danh_gia';

    public function __construct()
    {
        parent::__construct();
        $this->ensureTable();
    }

    /**
     * Đảm bảo bảng danh_gia tồn tại trong CSDL
     */
    private function ensureTable()
    {
        try {
            $sql = "
                CREATE TABLE IF NOT EXISTS `danh_gia` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `datban_id` int(11) NOT NULL,
                  `so_sao` tinyint(4) NOT NULL DEFAULT 5,
                  `binh_luan` text DEFAULT NULL,
                  `ngay` datetime NOT NULL DEFAULT current_timestamp(),
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `unique_datban` (`datban_id`),
                  CONSTRAINT `fk_danhgia_datban` FOREIGN KEY (`datban_id`) REFERENCES `dat_ban` (`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ";
            $this->pdo->exec($sql);
        } catch (Exception $e) {
            // Bảng đã có hoặc lỗi khác, bỏ qua
        }
    }

    /**
     * Lấy đánh giá theo ID đơn đặt bàn
     */
    public function getByBookingId($bookingId)
    {
        $sql = "
            SELECT dg.*, db.ngay_dat, db.ca, nd.ho_ten AS ten_khach
            FROM {$this->table} dg
            JOIN dat_ban db ON dg.datban_id = db.id
            LEFT JOIN nguoidung nd ON db.nguoidung_id = nd.id
            WHERE dg.datban_id = :datban_id
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':datban_id' => $bookingId]);
        return $stmt->fetch();
    }

    /**
     * Tạo mới hoặc cập nhật đánh giá (Upsert)
     */
    public function createOrUpdate($bookingId, $soSao, $binhLuan)
    {
        $sql = "
            INSERT INTO {$this->table} (datban_id, so_sao, binh_luan, ngay)
            VALUES (:datban_id, :so_sao, :binh_luan, NOW())
            ON DUPLICATE KEY UPDATE 
                so_sao = VALUES(so_sao), 
                binh_luan = VALUES(binh_luan),
                ngay = NOW()
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':datban_id' => $bookingId,
            ':so_sao'    => $soSao,
            ':binh_luan' => $binhLuan,
        ]);
    }

    /**
     * Xóa đánh giá theo review ID (kèm kiểm tra quyền sở hữu nếu có userId)
     */
    public function deleteReview($id, $userId = null)
    {
        if ($userId !== null) {
            $sql = "
                DELETE dg FROM {$this->table} dg
                JOIN dat_ban db ON dg.datban_id = db.id
                WHERE dg.id = :id AND db.nguoidung_id = :user_id
            ";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id' => $id, ':user_id' => $userId]);
        }

        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Xóa đánh giá theo booking ID
     */
    public function deleteByBookingId($bookingId, $userId = null)
    {
        if ($userId !== null) {
            $sql = "
                DELETE dg FROM {$this->table} dg
                JOIN dat_ban db ON dg.datban_id = db.id
                WHERE dg.datban_id = :datban_id AND db.nguoidung_id = :user_id
            ";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':datban_id' => $bookingId, ':user_id' => $userId]);
        }

        $sql = "DELETE FROM {$this->table} WHERE datban_id = :datban_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':datban_id' => $bookingId]);
    }

    /**
     * Lấy danh sách đánh giá công khai kèm thông tin tiệc & người dùng
     */
    public function getPublicReviews($limit = 30, $starFilter = null)
    {
        $limit = max(1, (int)$limit);
        $sql = "
            SELECT dg.*, 
                   db.id AS booking_id, db.ngay_dat, db.ca,
                   nd.ho_ten AS user_name,
                   bp.ten AS ban_ten, bp.loai AS ban_loai,
                   st.ten AS set_ten,
                   ld.ten AS dip_ten
            FROM {$this->table} dg
            JOIN dat_ban db ON dg.datban_id = db.id
            LEFT JOIN nguoidung nd ON db.nguoidung_id = nd.id
            LEFT JOIN ban_phong bp ON db.banphong_id = bp.id
            LEFT JOIN set_tiec st ON db.set_id = st.id
            LEFT JOIN loai_dip ld ON db.loaidip_id = ld.id
        ";

        $params = [];
        if ($starFilter !== null && $starFilter >= 1 && $starFilter <= 5) {
            $sql .= " WHERE dg.so_sao = :star";
            $params[':star'] = $starFilter;
        }

        $sql .= " ORDER BY dg.ngay DESC, dg.id DESC LIMIT {$limit}";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Lấy thống kê đánh giá (Điểm TB, tổng số đánh giá, số lượt từng sao)
     */
    public function getStats()
    {
        $sql = "
            SELECT 
                COUNT(*) AS total_reviews,
                COALESCE(AVG(so_sao), 5.0) AS avg_stars,
                SUM(CASE WHEN so_sao = 5 THEN 1 ELSE 0 END) AS star_5,
                SUM(CASE WHEN so_sao = 4 THEN 1 ELSE 0 END) AS star_4,
                SUM(CASE WHEN so_sao = 3 THEN 1 ELSE 0 END) AS star_3,
                SUM(CASE WHEN so_sao = 2 THEN 1 ELSE 0 END) AS star_2,
                SUM(CASE WHEN so_sao = 1 THEN 1 ELSE 0 END) AS star_1
            FROM {$this->table}
        ";
        $stmt = $this->pdo->query($sql);
        $row  = $stmt->fetch();

        return [
            'total_reviews' => (int)($row['total_reviews'] ?? 0),
            'avg_stars'     => round((float)($row['avg_stars'] ?? 5.0), 1),
            'star_5'        => (int)($row['star_5'] ?? 0),
            'star_4'        => (int)($row['star_4'] ?? 0),
            'star_3'        => (int)($row['star_3'] ?? 0),
            'star_2'        => (int)($row['star_2'] ?? 0),
            'star_1'        => (int)($row['star_1'] ?? 0),
        ];
    }

    /**
     * Lấy các đơn đặt bàn của thành viên để họ có thể chọn đánh giá
     */
    public function getUserReviewableBookings($userId)
    {
        $sql = "
            SELECT db.*,
                   bp.ten AS ban_ten,
                   st.ten AS set_ten,
                   ld.ten AS dip_ten,
                   dg.id AS danh_gia_id,
                   dg.so_sao AS danh_gia_sao,
                   dg.binh_luan AS danh_gia_binh_luan,
                   dg.ngay AS danh_gia_ngay
            FROM dat_ban db
            LEFT JOIN ban_phong bp ON db.banphong_id = bp.id
            LEFT JOIN set_tiec st ON db.set_id = st.id
            LEFT JOIN loai_dip ld ON db.loaidip_id = ld.id
            LEFT JOIN {$this->table} dg ON dg.datban_id = db.id
            WHERE db.nguoidung_id = :user_id 
              AND db.trang_thai NOT IN ('da_huy', 'tu_choi')
            ORDER BY db.id DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Dành cho quản trị viên: Xem toàn bộ đánh giá
     */
    public function getAllForAdmin()
    {
        $sql = "
            SELECT dg.*, 
                   db.id AS booking_id, db.ngay_dat, db.ca, db.trang_thai AS booking_status, db.tong_tien,
                   nd.id AS user_id, nd.ho_ten AS user_name, nd.email AS user_email, nd.so_dien_thoai AS user_phone,
                   bp.ten AS ban_ten,
                   st.ten AS set_ten
            FROM {$this->table} dg
            JOIN dat_ban db ON dg.datban_id = db.id
            LEFT JOIN nguoidung nd ON db.nguoidung_id = nd.id
            LEFT JOIN ban_phong bp ON db.banphong_id = bp.id
            LEFT JOIN set_tiec st ON db.set_id = st.id
            ORDER BY dg.ngay DESC, dg.id DESC
        ";
        return $this->pdo->query($sql)->fetchAll();
    }
}
