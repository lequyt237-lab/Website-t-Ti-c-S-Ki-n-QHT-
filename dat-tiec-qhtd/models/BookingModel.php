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
        $sql = "INSERT INTO {$this->table} 
                (nguoidung_id, banphong_id, loaidip_id, set_id, ngay_dat, ca, so_nguoi, gia_set_luc_dat, tong_tien, trang_thai, ghi_chu) 
                VALUES 
                (:nguoidung_id, :banphong_id, :loaidip_id, :set_id, :ngay_dat, :ca, :so_nguoi, :gia_set_luc_dat, :tong_tien, :trang_thai, :ghi_chu)";
        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute([
            ':nguoidung_id'     => $data['nguoidung_id'],
            ':banphong_id'      => $data['banphong_id'],
            ':loaidip_id'       => $data['loaidip_id'],
            ':set_id'           => $data['set_id'] ?? null,
            ':ngay_dat'         => $data['ngay_dat'],
            ':ca'               => $data['ca'],
            ':so_nguoi'         => $data['so_nguoi'],
            ':gia_set_luc_dat'  => $data['gia_set_luc_dat'] ?? null,
            ':tong_tien'        => $data['tong_tien'] ?? 0,
            ':trang_thai'       => $data['trang_thai'] ?? 'cho_duyet',
            ':ghi_chu'          => $data['ghi_chu'] ?? '',
        ]);

        if ($res) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }
}

