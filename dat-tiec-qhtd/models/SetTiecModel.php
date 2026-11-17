<?php

class SetTiecModel extends BaseModel
{
    protected $table = 'set_tiec';

    /* ───────── SET TIỆC ───────── */

    // Lấy tất cả set tiệc
    public function getAll()
    {
        $sql = "
            SELECT st.*,
                   COUNT(sm.mon_id) AS so_mon
            FROM {$this->table} st
            LEFT JOIN set_mon sm ON sm.set_id = st.id
            GROUP BY st.id
            ORDER BY st.id DESC
        ";
        return $this->pdo->query($sql)->fetchAll();
    }

    // Lấy 1 set tiệc theo ID
    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Thêm set tiệc mới
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (ten, gia, so_nguoi_phu_hop, mo_ta)
                VALUES (:ten, :gia, :so_nguoi, :mo_ta)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ten'      => $data['ten'],
            ':gia'      => $data['gia'],
            ':so_nguoi' => $data['so_nguoi_phu_hop'],
            ':mo_ta'    => $data['mo_ta'] ?? null,
        ]);
        return $this->pdo->lastInsertId();
    }

    // Cập nhật set tiệc
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table}
                SET ten = :ten, gia = :gia, so_nguoi_phu_hop = :so_nguoi, mo_ta = :mo_ta
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'       => $id,
            ':ten'      => $data['ten'],
            ':gia'      => $data['gia'],
            ':so_nguoi' => $data['so_nguoi_phu_hop'],
            ':mo_ta'    => $data['mo_ta'] ?? null,
        ]);
    }

    // Xóa set tiệc (cascade xóa set_mon liên quan)
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /* ───────── SET_MON (Món trong Set) ───────── */

    // Lấy danh sách món ăn của 1 set
    public function getMonBySetId($setId)
    {
        $sql = "
            SELECT ma.*, sm.id AS set_mon_id
            FROM set_mon sm
            JOIN mon_an ma ON ma.id = sm.mon_id
            WHERE sm.set_id = :set_id
            ORDER BY ma.nhom_mon ASC, ma.ten ASC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':set_id' => $setId]);
        return $stmt->fetchAll();
    }

    // Lấy danh sách mon_id đã có trong set
    public function getMonIdsBySetId($setId)
    {
        $stmt = $this->pdo->prepare("SELECT mon_id FROM set_mon WHERE set_id = :set_id");
        $stmt->execute([':set_id' => $setId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Thêm món vào set (1 hoặc nhiều)
    public function addMonToSet($setId, array $monIds)
    {
        $sql = "INSERT IGNORE INTO set_mon (set_id, mon_id) VALUES (:set_id, :mon_id)";
        $stmt = $this->pdo->prepare($sql);
        $count = 0;
        foreach ($monIds as $monId) {
            $stmt->execute([':set_id' => $setId, ':mon_id' => $monId]);
            $count += $stmt->rowCount();
        }
        return $count;
    }

    // Xóa 1 món khỏi set
    public function removeMonFromSet($setMonId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM set_mon WHERE id = :id");
        return $stmt->execute([':id' => $setMonId]);
    }

    // Đồng bộ danh sách món cho set (xóa cũ, thêm mới)
    public function syncMonForSet($setId, array $monIds)
    {
        // Xóa tất cả món cũ
        $this->pdo->prepare("DELETE FROM set_mon WHERE set_id = :set_id")
                   ->execute([':set_id' => $setId]);

        // Thêm danh sách mới
        if (!empty($monIds)) {
            $this->addMonToSet($setId, $monIds);
        }
    }

    /* ───────── MÓN ĂN (để chọn) ───────── */

    // Lấy tất cả món ăn (cho dropdown chọn)
    public function getAllMonAn()
    {
        return $this->pdo->query("SELECT * FROM mon_an ORDER BY nhom_mon ASC, ten ASC")->fetchAll();
    }

    // Lấy nhóm món phân biệt
    public function getNhomMon()
    {
        return $this->pdo->query("SELECT DISTINCT nhom_mon FROM mon_an ORDER BY nhom_mon ASC")->fetchAll(PDO::FETCH_COLUMN);
    }

    /* ───────── KIỂM TRA ───────── */

    // Kiểm tra set có đang liên kết với đơn đặt bàn nào không
    public function isSetInUse($id)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM dat_ban WHERE set_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn() > 0;
    }
}
