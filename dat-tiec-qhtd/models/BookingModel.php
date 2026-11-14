<?php

class BookingModel extends BaseModel
{
    protected $table = 'dat_ban';

    /**
     * Lấy danh sách đơn đặt bàn của một người dùng theo trạng thái (nếu có)
     */
    public function getBookingsByUserId($userId, $status = null)
    {
        $sql = "
            SELECT db.*,
                   bp.ten AS ten_ban, bp.loai AS loai_ban, bp.suc_chua, bp.phu_phi, bp.anh AS anh_ban,
                   ld.ten AS ten_dip,
                   st.ten AS ten_set, st.gia AS gia_set, st.so_nguoi_phu_hop AS set_so_nguoi
            FROM {$this->table} db
            LEFT JOIN ban_phong bp ON db.banphong_id = bp.id
            LEFT JOIN loai_dip ld ON db.loaidip_id = ld.id
            LEFT JOIN set_tiec st ON db.set_id = st.id
            WHERE db.nguoidung_id = :user_id
        ";

        $params = [':user_id' => $userId];

        if (!empty($status) && in_array($status, ['cho_duyet', 'da_duyet', 'tu_choi', 'da_huy'])) {
            $sql .= " AND db.trang_thai = :trang_thai";
            $params[':trang_thai'] = $status;
        }

        $sql .= " ORDER BY db.id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Lấy các đơn đặt bàn mới nhất của người dùng (cho trang chủ)
     */
    public function getRecentBookingsByUserId($userId, $limit = 3)
    {
        $sql = "
            SELECT db.*,
                   bp.ten AS ten_ban, bp.loai AS loai_ban, bp.suc_chua, bp.phu_phi, bp.anh AS anh_ban,
                   ld.ten AS ten_dip,
                   st.ten AS ten_set, st.gia AS gia_set
            FROM {$this->table} db
            LEFT JOIN ban_phong bp ON db.banphong_id = bp.id
            LEFT JOIN loai_dip ld ON db.loaidip_id = ld.id
            LEFT JOIN set_tiec st ON db.set_id = st.id
            WHERE db.nguoidung_id = :user_id
            ORDER BY db.id DESC
            LIMIT :limit
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', (int)$userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Thống kê số lượng đơn đặt bàn theo các trạng thái của người dùng
     */
    public function getBookingStatsByUserId($userId)
    {
        $sql = "
            SELECT 
                COUNT(*) AS total,
                SUM(CASE WHEN trang_thai = 'cho_duyet' THEN 1 ELSE 0 END) AS cho_duyet,
                SUM(CASE WHEN trang_thai = 'da_duyet' THEN 1 ELSE 0 END) AS da_duyet,
                SUM(CASE WHEN trang_thai = 'tu_choi' THEN 1 ELSE 0 END) AS tu_choi,
                SUM(CASE WHEN trang_thai = 'da_huy' THEN 1 ELSE 0 END) AS da_huy
            FROM {$this->table}
            WHERE nguoidung_id = :user_id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $stats = $stmt->fetch();

        return [
            'total'     => (int)($stats['total'] ?? 0),
            'cho_duyet' => (int)($stats['cho_duyet'] ?? 0),
            'da_duyet'  => (int)($stats['da_duyet'] ?? 0),
            'tu_choi'   => (int)($stats['tu_choi'] ?? 0),
            'da_huy'    => (int)($stats['da_huy'] ?? 0),
        ];
    }

    /**
     * Lấy chi tiết đơn đặt bàn
     */
    public function getBookingDetail($bookingId, $userId = null)
    {
        $sql = "
            SELECT db.*,
                   nd.ho_ten AS ten_khach, nd.email AS email_khach, nd.so_dien_thoai AS sdt_khach,
                   bp.ten AS ten_ban, bp.loai AS loai_ban, bp.suc_chua, bp.phu_phi, bp.mo_ta AS mota_ban, bp.anh AS anh_ban,
                   ld.ten AS ten_dip, ld.mo_ta AS mota_dip,
                   st.ten AS ten_set, st.gia AS gia_set, st.mo_ta AS mota_set, st.so_nguoi_phu_hop AS set_so_nguoi
            FROM {$this->table} db
            LEFT JOIN nguoidung nd ON db.nguoidung_id = nd.id
            LEFT JOIN ban_phong bp ON db.banphong_id = bp.id
            LEFT JOIN loai_dip ld ON db.loaidip_id = ld.id
            LEFT JOIN set_tiec st ON db.set_id = st.id
            WHERE db.id = :id
        ";

        $params = [':id' => $bookingId];

        if ($userId !== null) {
            $sql .= " AND db.nguoidung_id = :user_id";
            $params[':user_id'] = $userId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * Lấy danh sách món ăn đã chọn kèm theo đơn đặt bàn
     */
    public function getOrderDishes($bookingId)
    {
        $sql = "
            SELECT cm.*, ma.ten AS ten_mon, ma.nhom_mon, ma.anh AS anh_mon
            FROM chitiet_mon cm
            JOIN mon_an ma ON cm.mon_id = ma.id
            WHERE cm.datban_id = :datban_id
            ORDER BY cm.id ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':datban_id' => $bookingId]);
        return $stmt->fetchAll();
    }

    /**
     * Hủy đơn đặt bàn nếu đơn đang ở trạng thái 'cho_duyet'
     */
    public function cancelBooking($bookingId, $userId)
    {
        $sql = "
            UPDATE {$this->table}
            SET trang_thai = 'da_huy'
            WHERE id = :id AND nguoidung_id = :user_id AND trang_thai = 'cho_duyet'
        ";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'      => $bookingId,
            ':user_id' => $userId,
        ]);
    }

    /**
     * Tra cứu đơn đặt bàn theo số điện thoại hoặc mã đơn
     */
    public function searchByPhoneOrCode($keyword)
    {
        $keyword = trim($keyword);
        // Loại bỏ tiền tố '#' hoặc 'DB' nếu người dùng nhập ví dụ #DB01 hoặc DB1
        $cleanId = preg_replace('/[^0-9]/', '', $keyword);

        $sql = "
            SELECT db.*,
                   nd.ho_ten AS ten_khach, nd.so_dien_thoai AS sdt_khach,
                   bp.ten AS ten_ban, bp.loai AS loai_ban, bp.suc_chua, bp.phu_phi,
                   ld.ten AS ten_dip,
                   st.ten AS ten_set
            FROM {$this->table} db
            JOIN nguoidung nd ON db.nguoidung_id = nd.id
            LEFT JOIN ban_phong bp ON db.banphong_id = bp.id
            LEFT JOIN loai_dip ld ON db.loaidip_id = ld.id
            LEFT JOIN set_tiec st ON db.set_id = st.id
            WHERE (nd.so_dien_thoai = :phone OR nd.so_dien_thoai LIKE :phone_like";

        $params = [
            ':phone'      => $keyword,
            ':phone_like' => '%' . $keyword . '%',
        ];

        if (!empty($cleanId)) {
            $sql .= " OR db.id = :booking_id";
            $params[':booking_id'] = (int)$cleanId;
        }

        $sql .= ") ORDER BY db.id DESC LIMIT 10";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
