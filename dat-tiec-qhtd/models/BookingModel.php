<?php

class BookingModel extends BaseModel
{
    protected $table = 'dat_ban';


    // Lấy danh sách tất cả bàn & phòng
    public function getAllRoomsAndTables()
    {
        $stmt = $this->pdo->query("SELECT * FROM ban_phong ORDER BY loai DESC, id ASC");
        return $stmt->fetchAll();
    }

    // Lấy danh sách loại dịp
    public function getAllOccasions()
    {
        $stmt = $this->pdo->query("SELECT * FROM loai_dip ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    // Lấy thông tin 1 bàn/phòng
    public function getTableById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM ban_phong WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Kiểm tra bàn/phòng có bị trùng ngày & ca chưa
    public function checkTableBooked($banphong_id, $ngay_dat, $ca)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} 
                WHERE banphong_id = :banphong_id 
                  AND ngay_dat = :ngay_dat 
                  AND ca = :ca 
                  AND trang_thai NOT IN ('huy', 'tu_choi')";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':banphong_id' => $banphong_id,
            ':ngay_dat'    => $ngay_dat,
            ':ca'          => $ca,
        ]);
        return $stmt->fetchColumn() > 0;
    }

    // Tạo đơn đặt bàn mới
    public function createBooking($data)
    {
        // Lấy danh sách các cột thực tế đang có trong bảng dat_ban
        $cols = $this->pdo->query("SHOW COLUMNS FROM {$this->table}")->fetchAll(PDO::FETCH_COLUMN);

        $fields = [
            'nguoidung_id'    => $data['nguoidung_id'],
            'banphong_id'     => !empty($data['banphong_id']) ? $data['banphong_id'] : null,
            'loaidip_id'      => !empty($data['loaidip_id']) ? $data['loaidip_id'] : null,
            'set_id'          => $data['set_id'] ?? null,
            'ngay_dat'        => $data['ngay_dat'],
            'ca'              => $data['ca'],
            'so_nguoi'        => $data['so_nguoi'],
            'gia_set_luc_dat' => $data['gia_set_luc_dat'] ?? null,
            'tong_tien'       => $data['tong_tien'] ?? 0,
            'trang_thai'      => $data['trang_thai'] ?? 'cho_duyet',
            'ghi_chu'         => $data['ghi_chu'] ?? '',
        ];

        if (in_array('gio_den', $cols)) {
            $fields['gio_den'] = $data['gio_den'] ?? '18:30:00';
        }
        if (in_array('yeu_cau_phong_rieng', $cols)) {
            $fields['yeu_cau_phong_rieng'] = !empty($data['yeu_cau_phong_rieng']) ? 1 : 0;
        }

        // Nếu banphong_id trong DB là NOT NULL mà giá trị là null, gán bàn hợp lệ đầu tiên để không lỗi ràng buộc
        $banPhongCol = $this->pdo->query("SHOW COLUMNS FROM {$this->table} LIKE 'banphong_id'")->fetch();
        if ($banPhongCol && $banPhongCol['Null'] === 'NO' && empty($fields['banphong_id'])) {
            $defaultTableId = $this->pdo->query("SELECT id FROM ban_phong ORDER BY id ASC LIMIT 1")->fetchColumn();
            $fields['banphong_id'] = $defaultTableId ?: 1;
        }

        $columnNames = implode(', ', array_keys($fields));
        $placeholders = ':' . implode(', :', array_keys($fields));
        $sql = "INSERT INTO {$this->table} ($columnNames) VALUES ($placeholders)";

        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute($fields);

        if ($res) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    /**
     * Lấy danh sách đơn đặt bàn của một người dùng theo trạng thái (nếu có)
     */
    public function getBookingsByUserId($userId, $status = null)
    {
        $sql = "
            SELECT db.*,
                   (SELECT loai_thanh_toan FROM thanh_toan WHERE datban_id = db.id AND trang_thai = 'cho_xac_nhan' ORDER BY id DESC LIMIT 1) AS gd_cho_duyet_loai,
                   (SELECT ma_giao_dich FROM thanh_toan WHERE datban_id = db.id AND trang_thai = 'cho_xac_nhan' ORDER BY id DESC LIMIT 1) AS gd_cho_duyet_ma,
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
                   bp.phu_phi, bp.anh AS anh_ban,
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
                   (SELECT loai_thanh_toan FROM thanh_toan WHERE datban_id = db.id AND trang_thai = 'cho_xac_nhan' ORDER BY id DESC LIMIT 1) AS gd_cho_duyet_loai,
                   (SELECT ma_giao_dich FROM thanh_toan WHERE datban_id = db.id AND trang_thai = 'cho_xac_nhan' ORDER BY id DESC LIMIT 1) AS gd_cho_duyet_ma,
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
                   bp.phu_phi, bp.anh AS anh_ban,
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
                SUM(CASE WHEN trang_thai = 'yeu_cau_huy' THEN 1 ELSE 0 END) AS yeu_cau_huy,
                SUM(CASE WHEN trang_thai = 'tu_choi' THEN 1 ELSE 0 END) AS tu_choi,
                SUM(CASE WHEN trang_thai IN ('da_huy', 'huy') THEN 1 ELSE 0 END) AS da_huy
            FROM {$this->table}
            WHERE nguoidung_id = :user_id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $stats = $stmt->fetch();

        return [
            'total'       => (int)($stats['total'] ?? 0),
            'cho_duyet'   => (int)($stats['cho_duyet'] ?? 0),
            'da_duyet'    => (int)($stats['da_duyet'] ?? 0),
            'yeu_cau_huy' => (int)($stats['yeu_cau_huy'] ?? 0),
            'tu_choi'     => (int)($stats['tu_choi'] ?? 0),
            'da_huy'      => (int)($stats['da_huy'] ?? 0),
        ];
    }

    /**
     * Lấy chi tiết đơn đặt bàn
     */
    public function getBookingDetail($bookingId, $userId = null)
    {
        $sql = "
            SELECT db.*,
                   (SELECT loai_thanh_toan FROM thanh_toan WHERE datban_id = db.id AND trang_thai = 'cho_xac_nhan' ORDER BY id DESC LIMIT 1) AS gd_cho_duyet_loai,
                   (SELECT ma_giao_dich FROM thanh_toan WHERE datban_id = db.id AND trang_thai = 'cho_xac_nhan' ORDER BY id DESC LIMIT 1) AS gd_cho_duyet_ma,
                   nd.ho_ten AS ten_khach, nd.email AS email_khach, nd.so_dien_thoai AS sdt_khach,
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
                   bp.phu_phi, bp.mo_ta AS mota_ban, bp.anh AS anh_ban,
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
     * Khách hàng gửi yêu cầu hủy đơn đặt bàn
     */
    public function requestCancelBooking($bookingId, $userId, $lyDoHuy = '')
    {
        $hasLyDo = false;
        try {
            $check = $this->pdo->query("SHOW COLUMNS FROM {$this->table} LIKE 'ly_do_huy'")->fetch();
            $hasLyDo = !empty($check);
        } catch (Exception $e) {
            $hasLyDo = false;
        }

        if ($hasLyDo) {
            $sql = "
                UPDATE {$this->table}
                SET trang_thai = 'yeu_cau_huy',
                    ly_do_huy = :ly_do_huy
                WHERE id = :id AND nguoidung_id = :user_id AND trang_thai IN ('cho_duyet', 'da_duyet')
            ";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':ly_do_huy' => $lyDoHuy,
                ':id'        => $bookingId,
                ':user_id'   => $userId,
            ]);
        } else {
            $sql = "
                UPDATE {$this->table}
                SET trang_thai = 'yeu_cau_huy',
                    ghi_chu = CONCAT(COALESCE(ghi_chu, ''), ' | [YÊU CẦU HỦY: ', :ly_do_huy, ']')
                WHERE id = :id AND nguoidung_id = :user_id AND trang_thai IN ('cho_duyet', 'da_duyet')
            ";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':ly_do_huy' => $lyDoHuy,
                ':id'        => $bookingId,
                ':user_id'   => $userId,
            ]);
        }
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
                   (SELECT loai_thanh_toan FROM thanh_toan WHERE datban_id = db.id AND trang_thai = 'cho_xac_nhan' ORDER BY id DESC LIMIT 1) AS gd_cho_duyet_loai,
                   (SELECT ma_giao_dich FROM thanh_toan WHERE datban_id = db.id AND trang_thai = 'cho_xac_nhan' ORDER BY id DESC LIMIT 1) AS gd_cho_duyet_ma,
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

    /**
     * Lưu danh sách món ăn và set tiệc cho đơn đặt bàn, cập nhật lại tổng tiền
     */
    public function saveBookingDishes($bookingId, array $dishes, $setId = null, $extraNote = null)
    {
        try {
            $this->pdo->beginTransaction();

            // 1. Kiểm tra đơn đặt bàn
            $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id FOR UPDATE");
            $stmt->execute([':id' => $bookingId]);
            $booking = $stmt->fetch();
            if (!$booking) {
                $this->pdo->rollBack();
                return false;
            }

            // 2. Xóa các món cũ trong chitiet_mon của đơn này
            $delStmt = $this->pdo->prepare("DELETE FROM chitiet_mon WHERE datban_id = :datban_id");
            $delStmt->execute([':datban_id' => $bookingId]);

            // 3. Chèn các món mới vào chitiet_mon và tính tổng tiền món
            $totalDishesPrice = 0;
            if (!empty($dishes)) {
                $monIds = array_keys($dishes);
                $monIds = array_filter($monIds, fn($id) => is_numeric($id) && (int)$id > 0);

                if (!empty($monIds)) {
                    $inClause = implode(',', array_fill(0, count($monIds), '?'));
                    $stmtMon = $this->pdo->prepare("SELECT id, gia FROM mon_an WHERE id IN ($inClause)");
                    $stmtMon->execute(array_values($monIds));
                    $monPrices = $stmtMon->fetchAll(PDO::FETCH_KEY_PAIR);

                    $insertStmt = $this->pdo->prepare("
                        INSERT INTO chitiet_mon (datban_id, mon_id, so_luong, don_gia) 
                        VALUES (:datban_id, :mon_id, :so_luong, :don_gia)
                    ");

                    foreach ($dishes as $monId => $qty) {
                        $monId = (int)$monId;
                        $qty = (int)$qty;
                        if ($qty > 0 && isset($monPrices[$monId])) {
                            $unitPrice = (float)$monPrices[$monId];
                            $insertStmt->execute([
                                ':datban_id' => $bookingId,
                                ':mon_id'    => $monId,
                                ':so_luong'   => $qty,
                                ':don_gia'    => $unitPrice,
                            ]);
                            $totalDishesPrice += ($unitPrice * $qty);
                        }
                    }
                }
            }

            // 4. Xử lý Set tiệc nếu có
            $totalSetPrice = 0;
            $setPriceLucDat = null;
            if (!empty($setId)) {
                $stmtSet = $this->pdo->prepare("SELECT id, gia FROM set_tiec WHERE id = :id LIMIT 1");
                $stmtSet->execute([':id' => (int)$setId]);
                $setRow = $stmtSet->fetch();
                if ($setRow) {
                    $totalSetPrice = (float)$setRow['gia'];
                    $setPriceLucDat = $totalSetPrice;
                    $setId = (int)$setRow['id'];
                } else {
                    $setId = null;
                }
            } else {
                $setId = null;
            }

            // 5. Tính phụ phí bàn/phòng nếu có
            $phuPhi = 0;
            if (!empty($booking['banphong_id'])) {
                $stmtTable = $this->pdo->prepare("SELECT loai, phu_phi FROM ban_phong WHERE id = :id LIMIT 1");
                $stmtTable->execute([':id' => $booking['banphong_id']]);
                $tableRow = $stmtTable->fetch();
                if ($tableRow && $tableRow['loai'] === 'phong_rieng') {
                    $phuPhi = (float)$tableRow['phu_phi'];
                }
            }

            // 6. Tính tổng tiền mới
            $newTotal = $totalDishesPrice + $totalSetPrice + $phuPhi;

            // 7. Cập nhật ghi chú nếu có
            $finalNote = $booking['ghi_chu'] ?? '';
            if ($extraNote !== null) {
                $finalNote = trim($extraNote);
            }

            // 8. Cập nhật dat_ban
            $updateSql = "
                UPDATE {$this->table} 
                SET set_id = :set_id,
                    gia_set_luc_dat = :gia_set_luc_dat,
                    tong_tien = :tong_tien,
                    ghi_chu = :ghi_chu
                WHERE id = :id
            ";
            $upStmt = $this->pdo->prepare($updateSql);
            $upStmt->execute([
                ':set_id'          => $setId,
                ':gia_set_luc_dat' => $setPriceLucDat,
                ':tong_tien'       => $newTotal,
                ':ghi_chu'         => $finalNote,
                ':id'              => $bookingId,
            ]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("Lỗi đặt món: " . $e->getMessage());
            return false;
        }
    }
}

